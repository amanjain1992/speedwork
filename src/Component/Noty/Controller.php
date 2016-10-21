<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Noty;

use Speedwork\Core\Controller as BaseController;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Controller extends BaseController
{
    public function index()
    {
        $task = $this->input('task');
        $noty = $this->getHelper('notify.noty');

        if ($task == 'mark') {
            $id = $this->query('id');
            $noty->mark($id);

            return [
                'status' => 'OK',
            ];
        }

        // Get data for searching.
        $order      = $this->ordering($this->input(), ['id DESC']);
        $conditions = $this->conditions($this->input());

        // List the notifications.
        $rows = $this->database->paginate('#__notifications', 'all', [
            'conditions' => $conditions,
            'order'      => $order,
        ]);

        foreach ($rows['data'] as &$row) {
            $row = $noty->format($row);
        }
        $this->session->set('notification_count', 0);
        $this->ajax('index.php?option=noty', $rows['total']);

        return [
            'rows' => $rows,
        ];
    }

    /**
     * Index Function.
     *
     * @return List of Signatures
     */
    public function recent()
    {
        // Get data for searching.
        $data = $this->input();

        $order        = $this->get('resolver')->ordering($data, ['id DESC']);
        $conditions   = $this->get('resolver')->conditions($data);
        $conditions[] = ['status' => 0];

        // List the notifications.
        $rows = $this->database->find('#__notifications', 'all', [
            'conditions' => $conditions,
            'order'      => $order,
            'limit'      => 10,
        ]);

        $noty = $this->get('resolver')->helper('notify.noty');

        $id = [];
        foreach ($rows as &$row) {
            $row  = $noty->format($row);
            $id[] = $row['id'];
        }

        if (!empty($id)) {
            $noty->mark($id);
            $this->session->set('notification_count', 0);
        }

        return [
            'rows'  => $rows,
            'total' => count($rows),
        ];
    }

    public function unread()
    {
        $noty = $this->get('resolver')->helper('notify.noty');

        return [
            'count' => intval($noty->unreadCount()),
        ];
    }

    /**
     * Delete Function.
     *
     * @return Status
     */
    public function delete()
    {
        $id = $this->query('id');

        $this->database->update('#__notifications',
            ['status' => 1, 'modified' => time()], ['id' => $id]
        );

        $status           = [];
        $status['status'] = 'OK';

        return $status;
    }
}
