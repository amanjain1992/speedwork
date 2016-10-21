<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Widget\Bootstrap;

use Speedwork\Core\Widget;

class Datetime extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('static::eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
        $this->get('assets')->addScript('static::eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
    }

    public function run()
    {
        $this->setRun('datetimepicker');
    }
}
