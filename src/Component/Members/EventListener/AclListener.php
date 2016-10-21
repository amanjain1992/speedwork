<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Members\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class AclEventListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function onLoginFailed(GenericEvent $event)
    {
        $attempt_id = $this->app['session']->get('attempt_id');
        if ($attempt_id) {
            return $this->app['database']->update('#__user_login_attempts', [
                'attempts = attempts + 1',
                'last_attempt_at' => time(),
                ], ['id' => $attempt_id]
            );
        }

        $save                    = [];
        $save['username']        = $event['username'];
        $save['ip_address']      = ip();
        $save['attempts']        = 1;
        $save['last_attempt_at'] = time();

        $this->app['database']->save('#__user_login_attempts', $save);
    }

    public function onBeforeLogin(GenericEvent $event)
    {
        // Check is account blocked
        $row = $this->app['database']->find('#__user_login_attempts', 'first', [
            'conditions' => [
                'OR' => ['username' => $event['username'], 'ip_address' => ip()],
            ],
            'order' => ['id DESC'],
        ]);

        if (empty($row['id'])) {
            return true;
        }

        $attempts = $row['attempts'];
        $this->app['session']->set('login_attempts', $attempts);
        $this->app['session']->set('attempt_id', $row['id']);

        if ($attempts < 10) {
            return true;
        }

        $last_attempt = $row['last_attempt_at'];
        if ($last_attempt < strtotime('-1 HOUR')) {
            return true;
        }

        $event->stopPropagation();

        $event->results = [
            'status'  => 'ERROR',
            'message' => trans('Your account is temporarly blocked for an hour due to multiple invalid attempts.'),
        ];
    }

    public function onLoginSucess(GenericEvent $event)
    {
        $attempt_id = $this->app['session']->get('attempt_id');

        if ($attempt_id) {
            $this->app['database']->delete('#__user_login_attempts',
                ['OR' => ['username' => $event['user']['username'], 'ip_address' => ip()]]
            );

            $this->app['session']->remove('attempt_id');
        }

        // Check is fake login
        if ($this->app['route'] == 'members.auth') {
            return true;
        }

        //save in login history
        if (empty($event['userid'])) {
            return true;
        }

        $save               = [];
        $save['session_id'] = $this->app['session']->getId();
        $save['source']     = 'Website';
        $save['ip']         = ip();
        $save['host']       = env('HTTP_HOST');
        $save['agent']      = env('HTTP_USER_AGENT');
        $save['referer']    = env('HTTP_REFERER');
        $save['created']    = time();
        $save['status']     = 1;

        $this->app['database']->save('#__user_login_history', $save);

        $id = $this->app['database']->lastInsertId();
        $this->app['session']->set('login_history_id', $id);

        $user = $event['user'];
        // Time to change the password
        $change_password = $this->link('members/changepass');

        //1 : force change
        //2 : advice to change

        if ($user['last_pw_change'] && $user['last_pw_change'] < strtotime('-90 DAY')) {
            $this->app['session']->set('password_change_required', 2);
            $this->app['session']->getFlashBag()->add('flash', trans("It's been 90 days since you changed your password. Please change it now!"));

            $this->redirect($change_password);

            return true;
        }

        // Password is week
        $pattern = $this->app['config']->get('auth.account.patterns.password');
        if ($user['plain_password'] && !preg_match($pattern, $user['plain_password'])) {
            $this->app['session']->set('password_change_required', 2);
            $this->app['session']->getFlashBag()->add('flash', trans('Your Password is not strong enough. Please change it now!'));

            $this->redirect($change_password);

            return true;
        }

        // Password contain username
        if (stristr($user['plain_password'], $user['username']) !== false) {
            $this->app['session']->set('password_change_required', 1);
            $this->app['session']->getFlashBag()->add('flash', trans('Your password contains username in it. please change.'));

            $this->redirect($change_password);

            return true;
        }

        if ($change_required) {
            $this->app['session']->set('password_change_required', $change_required);
            $this->redirect($change_password);
        }
    }

    public function onBeforeLogout()
    {
        $id = $this->app['session']->get('login_history_id');
        if ($id) {
            $this->app['database']->update('#__user_login_history', [
                'status' => 2, 'modified' => time(),
                ], ['id' => $id]
            );
        }
    }

    /**
     * Runs before filters.
     *
     * @param GetResponseEvent $event The event to handle
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->app['is_ajax_request']) {
            return true;
        }

        $request = $event->getRequest();
    }

    public static function getSubscribedEvents()
    {
        return [
            'members.before.login'  => ['onBeforeLogin', 10],
            'members.after.login'   => ['onLoginSucess', 10],
            'members.after.failed'  => ['onLoginFailed', 10],
            'members.before.logout' => ['onBeforeLogout', 10],
            KernelEvents::REQUEST   => ['onKernelRequest', 10],
        ];
    }
}
