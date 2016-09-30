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

use Speedwork\Core\Api as BaseApi;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Api extends BaseApi
{
    /**
     * Enter description here ...
     * function login.
     */
    public function login()
    {
        $username = $this->data['username'];
        $password = $this->data['password'];

        if (empty($username)) {
            return [
                'status'  => 'E401',
                'message' => trans('Username is missing'),
            ];
        }

        if (empty($password)) {
            return [
                'status'  => 'E401',
                'message' => trans('Password is missing'),
            ];
        }

        $user = $this->get('acl')->isValidUser($username, $password);

        if (isset($user['userid'])) {
            unset($user['password']);
            $user['name'] = trim($user['first_name'].' '.$user['last_name']);

            return [
                'status'  => 'OK',
                'message' => trans('Login success'),
                'data'    => array_merge([
                    'id' => $user['userid'],
                ], $user),
            ];
        }

        return [
            'status'  => 'E501',
            'message' => 'Not a valid user',
        ];
    }

    /**
     * Enter description here ...
     * function login.
     */
    public function signin()
    {
        $username = $this->data['username'];

        if (empty($username)) {
            return [
                'status'  => 'E401',
                'message' => trans('Username is missing'),
            ];
        }

        $user = $this->get('acl')->isValidUser($username, null);

        if (isset($user['userid'])) {
            unset($user['password']);
            $user['name'] = trim($user['first_name'].' '.$user['last_name']);

            $code = substr(mt_rand(), -6);

            $message = trans(':0 is your activation key for login into :1.', [$code, _SITENAME]);

            $smshelper = $this->get('resolver')->helper('sms');
            $smshelper->sendSms([
                'to'      => $user['mobile'],
                'message' => $message,
            ]);

            return [
                'status'  => 'OK',
                'message' => trans('Activation key sent successfully'),
                'data'    => array_merge([
                    'id'   => $user['userid'],
                    'code' => $code,
                ], $user),
            ];
        }

        return [
            'status'  => 'E501',
            'message' => trans('Not a valid user'),
        ];
    }

    /**
     * Enter description here ...
     * functin Logout.
     */
    public function logout()
    {
        $this->get('acl')->logout();

        return [
            'status'  => 'OK',
            'message' => trans('Your are successfully loged out.'),
        ];
    }

    /**
     * Enter description here ...
     * functin validate.
     */
    public function validate()
    {
        $username = $this->data['username'];
        $password = $this->data['password'];

        if (empty($username)) {
            return [
                'status'  => 'E502',
                'message' => trans('Username is missing'),
            ];
        }

        $user = $this->get('acl')->isValidUser($username, $password);
        if (empty($user)) {
            return [
                'status'  => 'E503',
                'message' => trans('Invalid credentials'),
            ];
        }

        //form save array
        $save                = [];
        $save['user_id']     = $user['userid'];
        $save['service']     = $this->post['service'];
        $save['public_key']  = $this->post['public_key'];
        $save['private_key'] = $this->post['private_key'];
        $save['status']      = 1;

        //get auth token
        $info = $this->database->find('#__user_auth_tokens', 'first', [
            'fields'     => ['token_id'],
            'conditions' => ['user_id' => $user['userid']],
            ]
        );

        //save/update the auth token
        if (empty($info)) {
            $save['created'] = time();

            $res = $this->database->save('#__user_auth_tokens', $save);
        } else {
            $save['modified'] = time();

            $res = $this->database->update('#__user_auth_token', $save, ['token_id' => $info['token_id']]);
        }

        //response
        if ($res) {
            return [
                'status'  => 'OK',
                'message' => trans('Valid User'),
                'data'    => ['id' => $user['userid']],
            ];
        } else {
            return [
                'status'  => 'E504',
                'message' => trans('Some error occured'),
            ];
        }
    }

    public function register()
    {
        $members = $this->get('resolver')->requestModel('members');

        return $members->register($this->post);
    }
}
