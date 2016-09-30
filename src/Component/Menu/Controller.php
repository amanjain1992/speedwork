<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Component\Menu;

use Speedwork\Core\Controller as BaseController;

/**
 * @author Sankar Suda <sankar.suda@gmail.com>
 */
class Controller extends BaseController
{
    /**
     * Index Function.
     *
     * @return List of Url
     */
    public function index()
    {
        // Condition.
        $conditions = $this->get('resolver')->conditions($this->get);
        // Ordered by.
        $order = $this->get('resolver')->ordering($this->get, ['id DESC']);

        $rows = $this->database->paginate(
            '#__core_menu_types', 'all', [
                'conditions' => $conditions,
                'order'      => $order,
            ]
        );
        // Assign data.
        $this->ajaxRequest('index.php?option=menu', $rows['total']);

        return ['rows' => $rows];
    }

    /**
     * Function to add primary keyword.
     */
    public function add()
    {
        $task = $this->post['task'];
        $id   = $this->post['id'];

        $status = [];

        if ($task == 'save') {
            //check if this id is belong to this user
            $save = $this->post['data'];

            $status = [];

            if ($id) {
                $res               = $this->database->update('#__core_menu_types', $save, ['id' => $id]);
                $status['status']  = ($res) ? 'OK' : 'ERROR';
                $status['message'] = ($res) ? trans('Updated Successfully') : trans('Some Error Occured');
            } else {
                //check menu type already exists
                $rows = $this->database->find('#__core_menu_types', 'count', [
                    'conditions' => ['menu_type' => $save['menu_type']],
                ]);

                if ($rows > 0) {
                    $status            = [];
                    $status['status']  = 'ERROR';
                    $status['message'] = trans('Menu Type Already Exists.');

                    return $status;
                }

                $res = $this->database->save('#__core_menu_types', $save);

                $status['status']  = ($res) ? 'OK' : 'ERROR';
                $status['message'] = ($res) ? trans('Added Successfully') : trans('Some Error Occured');
            }

            return $status;
        }

        $id = $this->get['id'];

        if (isset($id)) {
            $row = $this->database->find('#__core_menu_types', 'first', [
                'conditions' => ['id' => $id],
                'ignore'     => true,
            ]);

            return ['row' => $row];
        }
    }

    /**
     * Function to delete.
     *
     * @return [type]
     */
    public function delete()
    {
        $id = $this->get['id'];

        $status = [];

        $res = $this->database->delete('#__core_menu_types', ['id' => $id]);

        $status['status']  = ($res) ? 'OK' : 'ERROR';
        $status['message'] = ($res) ? trans('Deleted Successfully..') : trans('An error occured');

        return $status;
    }

    /**
     * Index Function.
     *
     * @return List of Url
     */
    public function items()
    {
        $task = $this->data['task'];

        if ($task == 'delete') {
            $id = $this->get['id'];

            $status            = [];
            $res               = $this->database->delete('#__core_menu', ['menu_id' => $id]);
            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans('Deleted successfully..') : trans('An error occured');

            return $status;
        }

        if ($task == 'status') {
            $id  = $this->get['id'];
            $res = $this->database->update('#__core_menu', ['status' => $this->get['status']], ['menu_id' => $id]);

            $status            = [];
            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans('Status changed Successfully..') : trans('An error occured');

            return $status;
        }

        if ($task == 'sorting') {
            $sorting = $this->data['sorting'];
            $this->get('resolver')->helper('Speed')->sorting('#__core_menu', $sorting, 'menu_id');
        }

        $conditions = $this->get('resolver')->conditions($this->data);

        $order = $this->get('resolver')->ordering($this->data, ['parent_id ASC', 'ordering']);

        $menu_type    = $this->data['t'];
        $conditions[] = ['menu_type' => $menu_type];

        // List the signatures.
        $rows = $this->database->paginate(
            '#__core_menu', 'all', [
                'conditions' => $conditions,
                'order'      => $order,
            ]
        );

        $item = [];
        foreach ($rows['data'] as &$row) {
            if ($row['parent_id'] && !isset($item[$row['parent_id']])) {
                $menu = $this->database->find('#__core_menu', 'first', [
                    'conditions' => ['menu_id' => $row['parent_id']],
                    'fields'     => ['name'],
                ]);

                $item[$row['parent_id']] = $menu['name'];
            }

            $row['parent_menu'] = $item[$row['parent_id']];
            $row['access']      = $this->model->getAccess($row['access']);
        }

        // Assign data.
        $this->ajaxRequest('index.php?option=menu&view=items&t='.$menu_type, $rows['total']);

        return ['rows' => $rows, 'menu_type' => $menu_type];
    }

    /**
     * Function to add primary keyword.
     */
    public function item()
    {
        $task      = $this->post['task'];
        $id        = $this->post['id'];
        $menu_id   = $this->get['menu_id'];
        $menu_type = $this->data['menu_type'];

        $status = [];

        if ($task == 'save') {
            //check if this id is belong to this user
            $save = $this->post['data'];
            $attr = [];
            $keys = count($this->post['key']);

            for ($i = 0; $i < $keys; ++$i) {
                if (empty($this->post['key'][$i])) {
                    continue;
                }

                $attr[$this->post['key'][$i]] = $this->post['value'][$i];
            }
            $save['attributes'] = json_encode($attr);
            $save['parent_id']  = $this->post['category'][0];
            $save['link']       = $this->model->fixLink($save['link']);

            $status = [];

            if ($id) {
                if ($id == $save['parent_id']) {
                    $status            = [];
                    $status['status']  = 'ERROR';
                    $status['message'] = trans('Can not be a submenu for itself.');
                } else {
                    $res               = $this->database->update('#__core_menu', $save, ['menu_id' => $id]);
                    $status['status']  = ($res) ? 'OK' : 'ERROR';
                    $status['message'] = ($res) ? trans('Updated Successfully') : trans('Some Error Occured');
                }
            } else {
                $save['menu_type'] = $menu_type;
                $save['ordering']  = $this->get('resolver')->helper('Speed')->nextOrder('#__core_menu', ['parent_id' => $save['parent_id']]);

                $res               = $this->database->save('#__core_menu', $save);
                $status['status']  = ($res) ? 'OK' : 'ERROR';
                $status['message'] = ($res) ? trans('Added Successfully') : trans('Some Error Occured');
            }

            return $status;
        }

        if ($menu_id) {
            $row = $this->database->find('#__core_menu', 'first', [
                'conditions' => ['menu_id' => $menu_id],
                'ignore'     => true,
            ]);

            if ($row['attributes']) {
                $row['attributes'] = (array) json_decode($row['attributes']);
            }
        }

        $menu_helper = $this->get('resolver')->helper('menu');
        $menutree    = $menu_helper->menuSelect($menu_type, false, $row['parent_id'], false, 1, true);

        return [
            'menutree'  => $menutree,
            'menu_type' => $menu_type,
            'row'       => $row,
        ];
    }
}
