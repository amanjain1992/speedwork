<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Noty\Helpers;

use Speedwork\Core\Helper as BaseHelper;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Notify extends BaseHelper
{
    public function add($params = [])
    {
        $details = [];
        if (!empty($params['user_id'])) {
            $details = ['ignore' => true];
        }

        $save               = [];
        $save['created']    = time();
        $save['status']     = 0;
        $save['meta']       = json_encode($params['meta']);
        $save['message']    = $params['message'];
        $save['user_id']    = $params['user_id'];
        $save['noty_group'] = $params['group'];

        $this->database->save('#__notifications', $save, $details);

        return $this;
    }

    public function unreadCount()
    {
        return $this->database->find('#__notifications', 'count', [
            'conditions' => ['status' => 0],
        ]);
    }

    public function mark($id = null)
    {
        $conditions = [];
        if ($id !== null) {
            $conditions[] = ['id' => $id];
        }

        $this->database->update('#__notifications',
            ['status' => 1, 'modified' => time()],
            $conditions
        );
    }

    public function format($row)
    {
        $row['meta'] = json_decode($row['meta'], true);

        return $row;
    }
}
