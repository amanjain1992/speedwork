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

use Speedwork\Core\Model as BaseModel;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Model extends BaseModel
{
    public function register(&$save = [], $contact = [])
    {
        $status = [];

        if (!config('auth.account.enable_online_registration')) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Registrations are disabled.');

            return $status;
        }

        //Merging the required fields
        if ($save['m']) {
            foreach ($save['m'] as $key => $value) {
                $save[$key] = implode('', $value);
            }
            unset($save['m']);
        }

        if (empty($save['last_name'])) {
            $name = explode(' ', $save['first_name'], 2);

            $save['first_name'] = $name[0];
            $save['last_name']  = $name[1];
        }

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

        $config = config('auth.account');

        $role_id = $config['default_user_role'];

        if ($role_id && is_array($role_id)) {
            if (empty($save['role_id']) && !in_array($save['role_id'], $role_id)) {
                return [
                    'status'  => 'ERROR',
                    'message' => trans('Please choose valid group'),
                ];
            } else {
                $role_id = $save['role_id'];
            }
        }

        //get last group of the groups
        if (empty($role_id)) {
            $row = $this->database->find('#__user_roles', 'first', [
                'order' => ['role_id DESC'],
            ]);

            $role_id = $row['role_id'];
        }

        if (!$config['auto_increment_userid'] && empty($save['userid'])) {
            $userid         = substr(uniqid(time(), true), 0, 10);
            $save['userid'] = $userid;
        }

        $activation = $config['activation'];

        //If password is not there then setting some random password
        $pass = ($save['password']) ? $save['password'] : substr((mt_rand()), 0, 6);

        $save['password'] = salt(trim($pass));
        $save['ip']       = ip();
        $save['status']   = ($activation) ? 0 : 1;
        $save['created']  = time();
        $save['token']    = md5(uniqid());

        if (empty($save['username']) && !empty($save['email'])) {
            $save['username'] = $this->generateUsername($save['email']);
        }

        if (config('auth.role')) {
            $save['role_id'] = $role_id;
        }

        if ($activation) {
            $save['activation_key'] = substr(mt_rand(), 0, 6);
        }

        $res = $this->database->save('#__users', $save);

        if (!$res) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Sorry. an error occured while registering..');
            $status['error']   = $this->database->showQuery();

            return $status;
        }

        if ($config['auto_increment_userid']) {
            $userid = $this->database->lastInsertId();
        }

        if (!empty($contact)) {
            $contact['user_id'] = $userid;
            $this->database->save('#__user_contact_details', $contact);
        }

        //call the hooks
        $this->dispatch('event.members.after.register', [
            'userid'  => $userid,
            'user'    => $save,
            'role_id' => $role_id,
        ]);

        if ($role_id && !config('auth.power')) {
            $this->database->save('#__user_to_role', [
                'user_id' => $userid, 'role_id' => $role_id,
            ]);
        }

        $emailhelper = $this->get('resolver')->helper('email');
        $encryption  = $this->get('resolver')->helper('encryption');
        $smshelper   = $this->get('resolver')->helper('sms');

        $mail_username = [];
        foreach ($config['login_fields'] as $fields) {
            if (isset($save[$fields])) {
                $mail_username[] = $save[$fields];
            }
        }

        $mail_username = implode(' OR ', $mail_username);

        $array_content                        = [];
        $array_content['mail_username']       = $mail_username;
        $array_content['mail_password']       = $pass;
        $array_content['first_name']          = $save['first_name'];
        $array_content['last_name']           = $save['last_name'];
        $array_content['mail_ip']             = ip();
        $array_content['user']                = $save;
        $array_content['contact']             = $contact;
        $array_content['mail_activation_key'] = $save['activation_key'];

        if ($activation && $config['email_activation']) {
            //send activation link to email also..
            $activation_link = '';

            if ($config['activation_set_password']) {
                $domain          = $this->release('domain_url');
                $activation_link = ($domain) ? $domain : '';
            }

            //If sms activaton is there then we dont need to send activation link to email
            if (!$config['sms_activation']) {
                $activation_link .= 'index.php?option=members&view=activate&do=activate';
                $activation_link .= '&u='.$encryption->encrypt($userid);
                $activation_link .= '&k='.$save['activation_key'].'&t='.$encryption->encrypt(time());

                $array_content['mail_activation_link'] = $this->link($activation_link);
            }

            $emailhelper->sendEmail([
                'tags'     => $array_content,
                'to'       => $save['email'],
                'template' => 'email_registration2.tpl',
                'subject'  => $mailsubject,
                ]
            );

            $status['message'] = trans('Your account has been created but currently inactive.<br>Follow your email to activate');

            $url = 'members/activate?do=success';
            $url .= '&u='.$encryption->encrypt($userid).'&t='.$encryption->encrypt(time());
            $status['redirect'] = $this->link($url);
        }

        if ($activation && $config['sms_activation'] && $save['mobile']) {
            $message = trans("Thank you for registering with :0. Your activation key is ':1'.", [_SITENAME, $save['activation_key']]);

            $smshelper->sendSms([
                'tags'    => $array_content,
                'to'      => $save['mobile'],
                'message' => $message,
            ]);

            $url = 'members/activate';
            $url .= '?u='.$encryption->encrypt($userid).'&t='.$encryption->encrypt(time());
            $status['redirect'] = $this->link($url);
        }

        if (!$activation) {
            $emailhelper->sendEmail([
                'tags'     => $array_content,
                'to'       => $save['email'],
                'template' => 'email_registration.tpl',
                'subject'  => $mailsubject,
            ]);

            $smshelper->sendSms([
                'tags'     => $array_content,
                'to'       => $save['mobile'],
                'template' => 'email_registration.txt',
            ]);

            $status['message']  = trans('Your account has been created successfully. Please login to use your account.');
            $status['redirect'] = $this->link('members/login');
        }

        $status['status'] = 'OK';
        $status['data']   = [
            'userid'   => $userid,
            'password' => $pass,
            'token'    => $save['token'],
        ];

        return $status;
    }

    public function generateUsername($email)
    {
        $username = explode('@', $email);
        $username = trim($username[0]);

        //Validate username exists in database
        $count = $this->database->find('#__users', 'count', [
            'conditions' => ['username like' => $username.'%'],
        ]);

        if ($count) {
            return $this->generateUsername($username.($count + 1));
        }

        return $username;
    }

    public function validate($data = [])
    {
        $exist = $this->get('acl')->checkUserByLogin($data, [], true);

        if ($exist !== true) {
            $status['status'] = 'ERROR';
            if ($exist[0] == 'required') {
                $status['message'] = trans('Please enter a valid :0', [$exist[1]]);
            } else {
                $status['message'] = trans('This :0 was already registered with us.', [$exist[1]]);
            }

            return $status;
        } else {
            return [
                'status' => 'OK',
            ];
        }
    }

    public function isPasswordChanged()
    {
        $data = $this->database->find('#__users', 'first', [
            'fields'     => ['activation_key'],
            'conditions' => ['userid' => $this->userid],
            ]
        );

        return (empty($data['activation_key'])) ? false : true;
    }

    public function login($data, $hash = true)
    {
        $status   = [];
        $remember = $data['remember'];
        $login    = $this->get('acl')->LogUserIn($data['username'], $data['password'], $remember, $hash);

        if ($login === true) {
            $next = ($data['next']) ? $data['next'] : config('auth.account.onlogin');

            $status['status']  = 'OK';
            $status['message'] = trans('Login success. Please wait...');

            if ($next != 1) {
                $status['redirect'] = $this->link($next);
            }

            return $status;
        }

        if (is_array($login)) {
            return $login;
        }

        if ($login == 2) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Your Account is suspended or You have not activated your account yet!<br>Please activate your account or contact admin at :0.', [_ADMIN_MAIL]);

            return $status;
        }

        if ($login == 3) {
            $status['status']  = 'ERROR';
            $status['message'] = trans('Your Account is suspended. Please contact admin at :0.', [_ADMIN_MAIL]);

            return $status;
        }

        $status['status']  = 'ERROR';
        $status['message'] = trans('Invalid username or password! please try again.');

        return $status;
    }

    public function removeActivationCode()
    {
        $this->database->update('#__users', ['activation_key' => ''], ['userid' => $this->userid]);
    }

    public function tokenValidate($signature = false)
    {
        if ($signature) {
            //time based signature validation
            $sign = base64_decode(urldecode($this->get['s']));
            $time = base64_decode(urldecode($this->get['t']));
            $key  = trim(md5(strtoupper(config('app.service').env('HTTP_HOST').$time)));
            if ($sign != $key || (($time + 30) < time())) {
                return [
                    'status'  => 'ERROR',
                    'message' => trans('Invalid request'),
                ];
            }
        }

        //rsa decrypt
        $public = base64_decode(urldecode($this->get['k']));
        $data   = base64_decode(urldecode($this->get['d']));
        $user   = base64_decode(urldecode($this->get['a']));

        //get token info from db
        $row = $this->database->find('#__user_auth_token', 'first', [
                'conditions' => ['status' => 1,
                        'public_key'      => $public,
                        'fkuserid'        => $user,
                    ],
                'fields' => ['private_key', 'fkuserid'],
                'ignore' => true,
            ]
        );
        if (empty($row)) {
            return [
                'status'  => 'ERROR',
                'message' => trans('Invalid request'),
            ];
        }

        $crypt = $this->get('resolver')->helper('crypt');
        $crypt->start('tripledes', $row['private_key']);
        $data = json_decode(trim($crypt->decrypt($data), "\0"), true);

        if (empty($data)) {
            return [
                'status'  => 'ERROR',
                'message' => trans('Invalid request'),
            ];
        }
        if ($data['userid'] != $row['fkuserid']) {
            return [
                'status'  => 'ERROR',
                'message' => trans('Invalid request'),
            ];
        }

        return [
            'status'  => 'OK',
            'message' => trans('Success'),
            'data'    => $data,
        ];
    }

    public function tokenGenerate($signature = false, $info = [])
    {
        //form login url params
        $time      = time();
        $signature = trim(md5(strtoupper($info['service_key'].$info['host'].$time)));

        $data  = json_encode(['userid' => $info['account_id']]);
        $crypt = $this->get('resolver')->helper('crypt');
        $crypt->start('tripledes', $info['private_key']);
        $data = $crypt->encrypt($data);

        $login_url = $info['api'].'?s='.urlencode(base64_encode($signature));
        $login_url .= '&t='.urlencode(base64_encode($time));
        $login_url .= '&k='.urlencode(base64_encode($info['public_key']));
        $login_url .= '&d='.urlencode(base64_encode($data));
        $login_url .= '&a='.urlencode(base64_encode($info['account_id']));

        return $login_url;
    }
}
