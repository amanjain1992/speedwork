<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Module\Menu;

use Speedwork\Core\Module as BaseModule;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Module extends BaseModule
{
    public function index(&$options = [])
    {
        $menu = $options['menu'];
        if ($menu) {
            $menuHelper = $this->get('resolver')->helper('menu.menu');
            $layout     = $options['layout'];
            if (empty($layout)) {
                $layout = 'bootstrap';
            }

            return [
                'items'  => $menuHelper->display($menu),
                'layout' => $layout,
            ];
        }
    }
}
