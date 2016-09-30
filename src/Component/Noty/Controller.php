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
    /**
     * Index Function.
     *
     * @return List of Signatures
     */
    public function index()
    {
        // Get data for searching.
        $data = &$this->get;

        // Condition.
        $conditions = $this->get('resolver')->conditions($data);

        // Ordered by.
        $order = $this->get('resolver')->ordering($data, ['id DESC']);

        // List the notifications.
        $rows = $this->database->paginate(
            '#__notifications', 'all', [
                'conditions' => $conditions,
                'order'      => $order,
                ]
        );

        $this->ajaxRequest('index.php?option=noty', $rows['total']);

        return [
            'rows' => $rows,
        ];
    }

    /**
     * Add function.
     *
     * @return Status
     */
    public function add()
    {
        $task = $this->post['task'];
        $id   = $this->post['id'];

        $status = [];

        if ($task == 'save') {
            $save = $this->post['data'];

            $status = [];

            // If action is editing, update the announcement.
            if ($id) {
                $res = $this->database->update('#__notifications', $save, ['id' => $id]);
                // Checking any errors is there.
                $status['status']  = ($res) ? 'OK' : 'ERROR';
                $status['message'] = ($res) ? trans('Announcement updated Successfully') : trans('Some Error Occured');
            } else {
                // Else add new.
                $save['created'] = time();

                // Add new notification if not already exists.
                $res = $this->database->save('#__notifications', $save);
                // Checking any errors is there.
                $status['status']  = ($res) ? 'OK' : 'ERROR';
                $status['message'] = ($res) ? trans('Announcement added Successfully') : trans('Some Error Occured');
            }

            // Return the status to display.
            return $status;
        }

        // Get details to edit it.
        $id = $this->get['id'];
        if (isset($id)) {
            $row = $this->database->find('#__notifications', 'first', [
                'conditions' => ['id' => $id],
                ]
            );

            // Assign signature details for editing.
            return [
                'row' => $row,
            ];
        }
    }

    /**
     * Delete Function.
     *
     * @return Status
     */
    public function delete()
    {
        $id = $this->get['id'];

        $res = $this->database->delete(
            '#__notifications', ['id' => $id]
        );

        $status = [];

        $status['status']  = ($res) ? 'OK' : 'ERROR';
        $status['message'] = ($res) ? trans('Announcement deleted successfully..') : trans('An error occured While deleting');

        return $status;
    }

    public function notifys()
    {
        $notifications = $this->get('resolver')->helper('notify.noty');

        $rows = $notifications->listNotes();

        $data = [];
        if ($rows) {
            foreach ($rows as &$row) {
                $row['posted'] = date('Y-m-d', $row['created']);
            }
            $data['count'] = count($rows);
            $data['list']  = $rows;
        } else {
            $data['count'] = 0;
        }

        return [
            'rows' => $data,
        ];
    }

    public function allnotes()
    {
        $joins   = [];
        $joins[] = [
            'table'      => '#__notification_status',
            'alias'      => 's',
            'type'       => 'LEFT',
            'conditions' => ['s.fkuserid' => $this->userid, 'n.id = s.noty_id'],
        ];

        $rows = $this->database->paginate(
            '#__notifications', 'all', [
                'alias'      => 'n',
                'conditions' => ['n.status' => 1, 's.fkuserid IS NULL'],
                'fields'     => ['n.*', 's.created as read'],
                'order'      => ['n.created DESC'],
                'limit'      => 10,
                'joins'      => $joins,
            ]
        );

        $this->database->update('#__notification_status', ['status' => 1]);

        // Assign data.
        $this->ajaxRequest('index.php?option=noty&view=allnotes', $rows['total']);

        return [
            'rows' => $rows,
        ];
    }
}
