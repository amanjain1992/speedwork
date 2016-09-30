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

use Speedwork\Core\Model as BaseModel;
use Speedwork\Core\Router;

class Model extends BaseModel
{
    public function getAccess($id)
    {
        $list = [0 => 'public', 1 => 'Registered', 2 => 'Special'];

        return $list[$id];
    }

    public function fixLink($url)
    {
        if ($url && $url != '#' && $url != '/') {
            return Router::fixLink($url);
        }

        return $url;
    }
}
