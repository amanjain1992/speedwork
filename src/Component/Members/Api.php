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
     * Login to user account by using the username and password.
     *
     * @return array
     */
    public function login()
    {
        $username = $this->input('username');
        $password = $this->input('password');

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
     * Logout from account.
     *
     * @return array
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
     * Create a user account.
     *
     * @return array
     */
    public function register()
    {
        $members = $this->get('resolver')->requestModel('members');

        return $members->register($this->input());
    }
}
