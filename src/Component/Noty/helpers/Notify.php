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

use Speedwork\Core\Helper;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Notify extends Helper
{
    public function unread()
    {
        $relNum = 1;
        $uid    = $this->userid;

        $count      = 0;
        $parent     = $this->parentDetails($uid);
        $user2Notes = [];

        while ($parent) {
            $uid   = $parent['fkuserid'];
            $notes = $this->database->find('#__notifications', 'all', [
                'conditions' => [
                    'user_id' => $uid,
                    'role_id' => $this->user['role_id'],
                    'status'  => 1,
                ],
                'ignore' => true,
                ]
            );
            foreach ($notes as $note) {
                $check = $this->database->find('#__notification_status', 'count', [
                        'conditions' => ['user_id' => $this->userid, 'noty_id' => $note['id']],
                    ]
                );
                if ($check == 0) {
                    ++$count;
                    $user2Notes[] = $note['id'];
                }
            }

            $relNum = ++$relNum;
            $parent = $this->parentDetails($uid);
        }

        $data          = [];
        $data['count'] = $count;
        $data['notes'] = $user2Notes;

        return $data;
    }

    public function unreadCount()
    {
        $joins   = [];
        $joins[] = [
            'table'      => '#__notification_status',
            'alias'      => 's',
            'type'       => 'LEFT',
            'conditions' => ['s.fkuserid' => $this->userid, 'n.id = s.noty_id'],
        ];

        return $this->database->find(
            '#__notifications', 'count', [
                'alias'      => 'n',
                'conditions' => ['n.status' => 1, 's.fkuserid IS NULL'],
                'order'      => ['n.created DESC'],
                'joins'      => $joins,
            ]
        );
    }

    public function listNotes()
    {
        //list notification which are not read yet
        $joins   = [];
        $joins[] = [
            'table'      => '#__notification_status',
            'alias'      => 's',
            'type'       => 'LEFT',
            'conditions' => ['s.fkuserid' => $this->userid, 'n.id = s.noty_id'],
        ];

        $rows = $this->database->find(
            '#__notifications', 'all', [
                'alias'      => 'n',
                'conditions' => ['n.status' => 1, 's.fkuserid IS NULL'],
                'fields'     => ['n.id', 'n.message', 'n.created'],
                'order'      => ['n.created DESC'],
                'limit'      => 10,
                'joins'      => $joins,
            ]
        );

        $this->mark($rows);

        return $rows;
    }

    public function mark(&$rows)
    {
        $save = [];
        foreach ($rows as $row) {
            $save[] = [
                'noty_id'  => $row['id'],
                'fkuserid' => $this->userid,
                'created'  => time(),
            ];
        }

        if (!empty($save)) {
            $this->database->save('#__notification_status', $save);
        }
    }
}
