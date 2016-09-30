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

use Speedwork\Core\Api as BaseApi;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Api extends BaseApi
{
    public function index()
    {
        $notifications = $this->get('resolver')->helper('notify.noty');

        $rows = $notifications->listNotes();

        return [
            'rows'  => $rows,
            'count' => count($rows),
        ];
    }
}
