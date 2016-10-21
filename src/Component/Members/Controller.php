<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Members;

use Speedwork\Core\Controller as BaseController;
use Speedwork\Util\Utility;

/**
 * Controller to manage members registration process.
 *
 * @author sankar <sankar.suda@gmail.com>
 */
class Controller extends BaseController
{
    protected $afterlogin;

    public function beforeRender()
    {
        $this->afterlogin = config('auth.account.onlogin');
        $this->get('assets')->addScript('assets::members.js');
    }

    /* DEFAULT */
    public function index()
    {
        $task = $this->input('task');
        if ($task == 'auto') {
            $term = $this->input('q');

            $rows = $this->database->find('#__users_view', 'all', [
                'conditions' => [
                    'or' => [
                        'username like' => $term.'%',
                        'user like'     => $term.'%',
                    ],
                    'active'     => 1,
                    'userid NOT' => $this->get('userid'),
                ],
                'order' => ['user'],
                'limit' => 10,
            ]);

            $auto = [];
            foreach ($rows as &$row) {
                $label = '<img class="auto-avatar" src="'.$this->config('location.users').$row['avatar'].'" />';
                $label .= '<div class="auto-info"><div class="auto-title">'.$row['user'].'</div></div>';
                $label .= '<div class="clearfix"></div>';

                $auto[] = [
                    'id'    => $row['userid'],
                    'label' => $label,
                    'value' => $row['user'],
                ];
            }

            return $auto;
        }

        if ($this->is_user_logged_in) {
            return $this->redirect($this->afterlogin);
        }
    }

    /* REGISTER */
    public function register()
    {
        if ($this->is_user_logged_in) {
            return $this->redirect($this->afterlogin);
        }

        $task = $this->input('task');

        if ($task == 'validate') {
            $save = $this->post('data');

            return $this->model->validate($save);
        }

        if (empty($task)) {
            $social = $this->config('auth.account.social');
            if ($social['login'] === true) {
                $link = $this->getHelper('social.members');

                return [
                    'social'    => $social,
                    'providers' => $link->getProviderList(),
                ];
            }

            return true;
        }

        //validate token
        if (true !== $token = $this->resolver()->helper('security')->isValidToken($this->post('token'))
        ) {
            return $token;
        }

        $data = $this->post('data');

        if ($this->post('password')) {
            $data['password'] = $this->post('password');
        }

        $response = $this->model->register($data);

        if ($response['status'] == 'OK') {
            $this->assign('userid', $response['data']['userid']);
            unset($response['data']);
        }

        return $response;
    }

    /* LOGIN */
    public function login()
    {
        if ($this->is_user_logged_in) {
            return $this->redirect($this->afterlogin);
        }

        if ($this->post('username')) {
            $referer = $this->server('HTTP_REFERER');
            $next    = $this->getSession('login.next');
            $data    = $this->post();

            $data['next'] = $next ?: $referer;

            return $this->model->login($data);
        }

        $this->setSession('login.next', urldecode($this->input('next')));

        $social = config('auth.account.social');
        if ($social['login'] === true) {
            $link = $this->getHelper('social.members');

            return [
                'social'    => $social,
                'providers' => $link->getProviderList(),
            ];
        }
    }

    public function auth()
    {
        $key    = $this->query('key');
        $userid = $this->query('id');
        $time   = $this->query('t');

        $time_check = true;
        if ($this->query('s')) {
            $data = $this->model->tokenValidate(true);
            if (!is_numeric($data['data']['userid'])) {
                return [
                    'status'  => 'ERROR',
                    'message' => trans($data['message']),
                ];
            }

            $userid = $data['data']['userid'];

            $record = $this->database->find('#__users', 'first', [
                'fields'     => ['password'],
                'conditions' => ['userid' => $userid],
            ]);

            if (empty($record)) {
                return [
                    'status'  => 'ERROR',
                    'message' => trans('Unable to Authenicate. Provided details are not valid.'),
                ];
            }
            $key        = $record['password'];
            $time_check = false;
        }

        $fields   = $this->get('acl')->getLoginFields();
        $fields[] = 'userid';

        $conditions = ['userid' => $userid];
        if ($time_check !== false) {
            $conditions[] = ['last_signin' => $time];
        }

        $row = $this->database->find(
            '#__users', 'first', [
            'fields'     => $fields,
            'conditions' => $conditions,
            ]
        );

        if (!isset($row['userid'])) {
            return [
                'status'  => 'ERROR',
                'message' => trans('Unable to Authenicate. Provided details are not valid.'),
            ];
        }

        $redirect = ($this->query('redirect')) ? $this->query('redirect') : $this->afterlogin;

        $this->get('acl')->logout();
        foreach ($fields as $field) {
            $login = $this->get('acl')->LogUserIn($row[$field], $key, false, false);

            if ($login === true) {
                return $this->redirect($redirect);
            }
        }

        return [
            'status'  => 'ERROR',
            'message' => trans('Unable to Authenicate. Provided details are not valid.'),
        ];
    }

    /* LOGOUT */
    public function logout()
    {
        $this->get('acl')->logout();
        $last = $this->config('auth.account.onlogout');
        $last = $last ?: '/';

        $this->redirect($last);

        return [
            'status'   => 'OK',
            'message'  => trans('Logged out successfully.'),
            'redirect' => $last,
        ];
    }

    /* CHANGE PASSWORD */
    public function changepass()
    {
        if (!empty($this->post('password'))) {
            $newpass      = $this->post('password');
            $repass       = $this->post('repassword');
            $old_password = $this->post('oldpassword');

            $status = [];

            if ($newpass != $repass) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Your passwords does n\'t match.');

                return $status;
            }

            $match_password = $this->get('acl')->isValidPassword($old_password);

            if (!$match_password) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Your existing password does not match');

                return $status;
            }

            if (!preg_match('/'.config('app.patterns.password').'/', $this->post('password'))) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Password does not meet required complexity');

                return $status;
            }

            $res = $this->get('acl')->updatePassword($newpass);

            if ($res) {
                //call the hooks
                $this->fire('members.after.changepass', [
                    'userid'       => $this->userid,
                    'old_password' => $old_password,
                    'new_password' => $newpass,
                ]);

                $url = 'index.php?option=members&view=login';

                $status['status']   = 'OK';
                $status['message']  = trans('Your Password reseted successfully. Please login again.');
                $status['redirect'] = $url;
            } else {
                $status['status']  = 'ERROR';
                $status['message'] = trans('An error occured while resetting password. Please try again...');
            }
        }

        return $status;
    }

    /**
     * [changeusername description].
     *
     * @return [type] [description]
     */
    public function changeLogin()
    {
        $task = $this->post('task');

        if ($task == 'save') {
            $status = [];
            $login  = $this->post('login');
            $login  = ($login) ? $login : 'username';

            $save         = [];
            $save[$login] = $this->post('login_field');

            $exist = $this->get('acl')->checkUserByLogin($save, [], true);

            if ($exist !== true) {
                $status['status'] = 'ERROR';
                if ($exist[0] == 'required') {
                    $status['message'] = trans('Please enter a valid :0', [$exist[1]]);
                } else {
                    $status['message'] = trans('This :0 was already registered with us.', [$exist[1]]);
                }

                return $status;
            }

            $res = $this->database->update('#__users', $save, ['userid' => $this->get('userid')]);

            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans(':0 Changed successfully', [ucfirst($login)]) : trans('An error occured');

            return $status;
        }
    }

    public function verifyLogin()
    {
        $task   = $this->post('task');
        $login  = $this->post('login');
        $login  = ($login) ? $login : 'email';
        $status = [];

        if ($task == 'save') {
            $code  = $this->post('code');
            $otp   = $this->get('session')->get('verify.otp');
            $field = $this->get('session')->get('verify.login');
            $id    = $this->get('userid');

            if ($code != $otp) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Invalid code. Please try again.');

                return $status;
            }

            $save         = [];
            $save[$login] = $field;

            $exist = $this->get('acl')->checkUserByLogin($save, [], true);

            if ($exist !== true) {
                $status['status'] = 'ERROR';
                if ($exist[0] == 'required') {
                    $status['message'] = trans('Please enter a valid :0', [$exist[1]]);
                } else {
                    $status['message'] = trans('This :0 was already registered with us.', [$exist[1]]);
                }

                return $status;
            }

            $res = $this->database->update(
                '#__users', $save, ['userid' => $id], [
                'name'  => 'verify',
                'field' => $login,
                ]
            );

            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans(':0 Changed successfully', [ucfirst($login)]) : trans('An error occured');

            return $status;
        }

        $emailhelper = $this->getHelper('email');

        if ($task == 'verify' || $task == 'resend') {
            $id = $this->get('userid');

            if ($task != 'resend') {
                $code  = substr(rand(), 0, 6);
                $field = $this->post('login_field');

                $this->get('session')->set('verify.otp', $code);
                $this->get('session')->set('verify.login', $field);

                $save         = [];
                $save[$login] = $field;

                $exist = $this->get('acl')->checkUserByLogin($save, [], true);

                if ($exist !== true) {
                    $status['status'] = 'ERROR';
                    if ($exist[0] == 'required') {
                        $status['message'] = trans('Please enter a valid :0', [$exist[1]]);
                    } else {
                        $status['message'] = trans('This :0 was already registered with us.', [$exist[1]]);
                    }

                    return $status;
                }
            } else {
                $code  = $this->get('session')->get('verify.otp');
                $field = $this->get('session')->get('verify.login');
            }

            $tags          = [];
            $tags['login'] = $login;
            $tags['user']  = $this->get('acl')->getUserBy($login, $id);
            $tags['code']  = $code;

            $subject = trans('Verification email from :0', [_SITENAME]);

            $emailhelper->sendEmail([
                'tags'     => $tags,
                'to'       => $field,
                'template' => 'email_verify.tpl',
                'subject'  => $subject,
            ]);

            $status            = [];
            $status['code']    = $code;
            $status['status']  = 'OK';
            $status['message'] = trans('Verification code is sent to your Email Address');

            return $status;
        }
    }

    public function verifyMobile()
    {
        $task   = $this->post('task');
        $login  = $this->post('login');
        $login  = ($login) ? $login : 'mobile';
        $status = [];

        if ($task == 'save') {
            $code  = $this->post('code');
            $otp   = $this->get('session')->get('verify.otp');
            $field = $this->get('session')->get('verify.login');
            $id    = $this->get('userid');

            if ($code != $otp) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Invalid code. Please try again.');

                return $status;
            }

            $save         = [];
            $save[$login] = $field;

            $res = $this->database->update('#__users', $save, ['userid' => $id], [
                'name'  => 'verify',
                'field' => $login,
            ]);

            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans(':0 Changed successfully', [ucfirst($login)]) : trans('An error occured');

            return $status;
        }

        $smshelper = $this->getHelper('sms');

        if ($task == 'verify' || $task == 'resend') {
            $id = $this->get('userid');

            if ($task != 'resend') {
                $code  = substr(rand(), 0, 6);
                $field = $this->post('login_field');

                $this->get('session')->set('verify.otp', $code);
                $this->get('session')->set('verify.login', $field);
            } else {
                $code  = $this->get('session')->get('verify.otp');
                $field = $this->get('session')->get('verify.login');
            }

            $tags          = [];
            $tags['login'] = $login;
            $tags['user']  = $this->get('acl')->getUserBy($login, $id);
            $tags['code']  = $code;

            $message = "The OTP for verifying your account is : $code";

            $smshelper->sendSms(
                [
                'to'      => $field,
                'message' => $message,
                'tags'    => $tags,
                ]
            );

            $status            = [];
            $status['code']    = $code;
            $status['status']  = 'OK';
            $status['message'] = trans('Verification code is sent to your mobile number');

            return $status;
        }
    }

    /* RESET PASSWORD */
    public function resetpass()
    {
        $email = strtolower(trim($this->post('reset_email')));

        if (!$email) {
            return;
        }

        $status = [];
        $row    = $this->get('acl')->getUserByLogin($email);

        if (empty($row['userid'])) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('No account has been found.<br>You should contact the webmaster if you think this is a mistake.');

            return $status;
        }

        if (empty($row['email'])) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('We are not able find email address associate with this account.');

            return $status;
        }

        //get user details
        $userid = $row['userid'];
        $name   = $row['name'];
        $email  = $row['email'];
        $config = config('auth.account');

        //get encryption helper
        $encryption  = $this->getHelper('encryption');
        $emailhelper = $this->getHelper('email');

        if ($config['pwreset_token_verify']) {
            $pwd_token = substr(mt_rand(), 0, 6);
            $this->get('session')->set('pwd_verify_token', $pwd_token);
            $this->get('session')->set('user_data', $row);

            return   $this->model->sendTokenMail($row);
        }
        $activation_link = 'index.php?option=members&view=pwreset';
        $activation_link .= '&u='.$encryption->encrypt($userid);
        $activation_link .= '&key='.$encryption->encrypt($email);
        $activation_link .= '&t='.$encryption->encrypt(time());

        $link = $this->link($activation_link);

        $tags             = [];
        $tags['name']     = $name;
        $tags['username'] = $email;
        $tags['link']     = $link;
        $tags['user']     = $row;

        $mailsubject = trans('Password reset request from :0', [_SITENAME]);

        $mail = $emailhelper->sendEmail(
            [
            'tags'     => $tags,
            'to'       => $email,
            'subject'  => $mailsubject,
            'template' => 'email_forgot_password.tpl',
            ]
        );

        if ($mail) {
            $status['status']  = 'OK';
            $status['message'] = trans('Password reset link sent to your registered email address.Please check your email.');
        } else {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Oops an error occured while sending an email. Please try again.');
        }

        return $status;
    }

    public function activate()
    {
        $encryption = $this->getHelper('encryption');

        $status = [];
        $do     = $this->input('do');
        $userid = $this->query('u');
        $time   = $this->query('t');
        $key    = $this->query('k');
        $task   = $this->input('task');

        $status['do']     = $do;
        $status['time']   = $time;
        $status['userid'] = $userid;
        $status['key']    = $key;

        if ($do == 'verify' || $do == 'activate') {
            //is verification
            if ($do == 'verify') {
                $userid = $this->input('u');
                $time   = $this->input('t');
                $key    = $this->input('k');
            }
            if ($userid == '' && $key == '') {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Invalid Request. Missing activation key or userid');

                return $status;
            }

            $time = $encryption->decrypt($time);
            //check is expired
            $expire = strtotime('-24 HOUR');
            if ($time < $expire) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Request time has expired. Please request again.');

                return $status;
            }

            $userid = $encryption->decrypt($userid);
            $user   = $this->get('acl')->getUserBy('userid', $userid);

            if (empty($user['userid'])) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Requested user does not exist with us.');

                return $status;
            }

            if ($task == 'resend') {
                $config = config('auth.account');

                if ($config['activation'] && ($config['sms_activation'] || $config['mail_otp_activation'])) {
                    $activation_key = substr(mt_rand(), 0, 6);

                    $result = $this->database->update('#__users', ['activation_key' => $activation_key], ['userid' => $userid]);

                    if ($result) {
                        $this->model->sendActivateOtp($user, $activation_key);

                        $status['status']  = 'OK';
                        $status['message'] = trans('Activation code is resent successfully.');

                        return $status;
                    } else {
                        $status['status']  = 'ERROR';
                        $status['message'] = trans('Some error occured.');

                        return $status;
                    }
                } else {
                    $status['status']  = 'ERROR';
                    $status['message'] = trans('Requested feature is not enabled.');

                    return $status;
                }
            }

            //if key does n't match
            if ($user['activation_key'] && strcasecmp($user['activation_key'], $key)) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Verification key does n\'t match.');

                return $status;
            }

            if ($user['activation_key']) {
                $userid = $user['userid'];

                $result = $this->database->update(
                    '#__users',
                    ['status' => 1, 'activation_key' => '', 'activated_at' => time()],
                    ['userid' => $userid]
                );
            } else {
                $result = true;
            }

            if ($result) {
                $this->fire('members.after.activation', [
                    'userid' => $userid,
                ]);

                $setpass                = config('auth.account.activation_set_password');
                $status['status']       = 'OK';
                $status['message']      = trans('Your account verified successfully..');
                $status['verified']     = true;
                $status['set_password'] = ($setpass) ? $setpass : $this->query('setp');

                if (empty($status['set_password'])) {
                    $status['redirect'] = $this->link('members/login');
                }
            } else {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Something went wrong at our end. Please try again.');
            }

            return $status;
        }

        if ($do == 'setpass') {
            $userid = $this->post('u');
            $time   = $this->post('t');
            $key    = $this->post('k');

            $newpass = $this->post('password');
            $repass  = $this->post('repassword');

            $userid = $encryption->decrypt($userid);

            if (empty($newpass) || $newpass != $repass) {
                $status['status']  = 'ERROR';
                $status['message'] = trans("Your passwords does n't match.");

                return $status;
            }

            if (!preg_match('/'.config('app.patterns.password').'/', $newpass)) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Password does not meet required complexity');

                return $status;
            }

            $res = $this->get('acl')->updatePassword($newpass, $userid);

            if ($res) {
                $row = $this->get('acl')->getUserBy('userid', $userid);

                $emailhelper = $this->getHelper('email');

                $email = $row['email'];

                $tags                  = [];
                $tags['mail_username'] = $email;
                $tags['mail_password'] = $newpass;
                $tags['domain_url']    = $this->link('index.php');
                $tags['mail_ip']       = Utility::ip();
                $tags['user']          = $user;

                $data             = [];
                $data['tags']     = $tags;
                $data['to'][]     = ['email' => $email, 'name' => $row['name']];
                $data['template'] = 'email_registration.tpl';

                $emailhelper->sendEmail($data);

                $status['status']  = 'OK';
                $status['message'] = trans('Password changed successfully. Please login again.');
            } else {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Something went wrong at our end. Please try again.');
            }

            return $status;
        }

        if ($do == 'success') {
            //check is expired
            $time = $encryption->decrypt($time);

            $expire = strtotime('-24 HOUR');
            if ($time < $expire) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Request time has expired. Please request again.');

                return $status;
            }

            $userid = $encryption->decrypt($userid);
            $user   = $this->get('acl')->getUserBy('userid', $userid);

            if (empty($user['userid'])) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Requested user does not exist with us.');

                return $status;
            }

            $task = $this->query('task');

            //For resending the activation link to email id
            if ($task == 'resend') {
                $emailhelper = $this->getHelper('email');
                $config      = config('auth.account');

                $mail_username = [];
                foreach ($config['login_fields'] as $fields) {
                    if (isset($save[$fields])) {
                        $mail_username[] = $save[$fields];
                    }
                }

                $mail_username = implode(' OR ', $mail_username);

                $tags                        = [];
                $tags['mail_username']       = $mail_username;
                $tags['first_name']          = $user['name'];
                $tags['mail_ip']             = ip();
                $tags['mail_activation_key'] = $user['activation_key'];

                $activation_link = 'members/activate?do=activate';
                $activation_link .= '&u='.$encryption->encrypt($userid);
                $activation_link .= '&k='.$user['activation_key'].'&t='.$encryption->encrypt(time());

                if ($config['activation_set_password']) {
                    $activation_link = '&setp=1';
                }

                $tags['mail_activation_link'] = $this->link($activation_link);

                $subject = 'Activation link';

                $emailhelper->sendEmail([
                    'tags'     => $tags,
                    'to'       => $user['email'],
                    'template' => 'email_registration_resend.tpl',
                    'subject'  => $subject,
                ]);

                $status['status']  = 'OK';
                $status['message'] = trans('Activation link resent successfully');

                return $status;
            }

            return [
                'do'   => 'success',
                'user' => $user,
                'u'    => $encryption->encrypt($userid),
                't'    => $encryption->encrypt($time),
            ];
        }

        return [
            'userid' => $userid,
            'do'     => $do,
            'time'   => $time,
            'key'    => $key,
        ];
    }

    public function pwreset()
    {
        $do = $this->post('do');

        $userid = $this->input('u');
        $key    = trim($this->input('key'));
        $time   = $this->input('t');
        $status = [];

        if ($userid == '' || $key == '') {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Invalid Request.');

            return $status;
        }

        $encryption = $this->getHelper('encryption');

        $time = $encryption->decrypt($time);
        //check is expired
        $expire = strtotime('-24 HOUR');
        if ($time < $expire) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Requested time has expired. Please request again.');

            return $status;
        }

        $userid = $encryption->decrypt($userid);
        $email  = $encryption->decrypt($key);

        $row = $this->get('acl')->getUserBy('userid', $userid);

        if (empty($row['userid'])) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Requested user does not exist in our database');

            return $status;
        }

        $status['status'] = 'OK';

        if ($do == 'pwdreset') {
            $newpass = $this->post('password');
            $result  = $this->get('acl')->updatePassword($newpass, $userid);

            if ($result) {
                $mailsubject      = trans('Your new account details from :0', [_SITENAME]);
                $tags             = [];
                $tags['password'] = $newpass;
                $tags['name']     = $row['name'];
                $tags['username'] = $row['email'];
                $tags['user']     = $row;

                $emailhelper = $this->getHelper('email');

                $emailhelper->sendEmail([
                    'tags'     => $tags,
                    'to'       => $email,
                    'subject'  => $mailsubject,
                    'template' => 'email_send_password.tpl',
                ]);

                $url                = 'index.php?option=members&view=login';
                $status['status']   = 'OK';
                $status['message']  = trans('Your password reseted successfully');
                $status['redirect'] = $this->link($url);

                $this->redirect($url);
            } else {
                $status['status']  = 'ERROR';
                $status['message'] = trans('An error occured. while resetting password. Please try again.');
            }
        }

        return $status;
    }

    public function me()
    {
        $task = $this->post('task');
        $data = &$this->post;

        if ($task == 'save') {
            $save = $data['user'];

            $res = $this->database->update('#__users', $save, ['userid' => $this->userid]);
            // Contact details
            $rows = $this->database->find('#__user_contact_details', 'count');

            $details = $data['details'];

            if ($rows > 0) {
                $res = $this->database->update('#__user_contact_details', $details);
            } elseif (count(array_filter($details))) {
                $res = $this->database->save('#__user_contact_details', $details);
            }

            $status            = [];
            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans('Details Updated Successfully') : trans('Some Error Occured');

            return $status;
        }

        $user = $this->database->find('#__users', 'first', [
            'alias'      => 'u',
            'conditions' => ['u.userid' => $this->userid],
        ]);

        $user['name'] = $user['first_name'].' '.$user['last_name'];

        $details = $this->database->find('#__user_contact_details', 'first');

        return [
            'row' => [
                'user'    => $user,
                'details' => $details,
            ],
            'api' => ['row.user', 'row.details'],
        ];
    }

    /**
     * [lastlogins description].
     *
     * @return [type] [description]
     */
    public function history()
    {
        $rows = $this->database->find('#__user_login_history', 'all', [
            'order'      => ['id DESC'],
            'limit'      => 5,
            'conditions' => ['status <> 9'],
        ]);

        $i = 0;
        foreach ($rows as &$row) {
            $row['serial'] = ++$i;
        }

        return [
            'rows' => $rows,
        ];
    }

    public function social()
    {
        $network = $this->query('network') ?: $this->session->get('network');
        $network = $this->query('hauth.done') ?: $network;
        $this->get('session')->set('login.next', urldecode($this->input('next')));

        $social = $this->getHelper('social.members');
        if ($social->setProvider($network)) {
            $status = $social->connect();

            if (false === $status) {
                $status            = [];
                $status['status']  = 'ERROR';
                $status['message'] = trans('Failed to connect your account');
            }

            if ($status['redirect']) {
                $this->redirect($status['redirect']);
            }

            return $status;
        } else {
            $status            = [];
            $status['status']  = 'ERROR';
            $status['message'] = trans('An error occured. Please try again.');

            return $status;
        }
    }

    public function endpoint()
    {
        $this->getHelper('social.members')->endpoint();
    }

    /**
     * Function to verify token sent over email for password reset.
     *
     * @return [type] [description]
     */
    public function verifyToken()
    {
        $task   = $this->input('task');
        $row    = $this->get('session')->get('user_data');
        $status = [];

        if ($task == 'resend') {
            return $this->model->sendTokenMail($row);
        }

        $encryption = $this->getHelper('encryption');

        if ($task == 'verify') {
            $code = $this->input('token');

            $token  = $this->get('session')->get('pwd_verify_token');
            $row    = $this->get('session')->get('user_data');
            $email  = $row['email'];
            $userid = $row['userid'];

            $url = 'index.php?option=members&view=pwreset';
            $url .= '&u='.$encryption->encrypt($userid);
            $url .= '&key='.$encryption->encrypt($email);
            $url .= '&t='.$encryption->encrypt(time());
            $link = $this->link($url);

            if ($code == $token) {
                $status['status']   = 'OK';
                $status['message']  = trans('Token verifed successfully.');
                $status['redirect'] = $this->link($link);

                return $status;
            }

            if ($code != $token) {
                $status['status']  = 'ERROR';
                $status['message'] = trans('Token doesnt match.please enter valid token');

                return $status;
            }
        }

        return ['row' => $row];
    }
}
