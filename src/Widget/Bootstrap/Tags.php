<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

/**
 * JuiWidget class file.
 *
 * This is the base class for all JUI widget classes.
 */
namespace Turbo\Speedwork\Widget\Bootstrap;

use Speedwork\Core\Widget;

class Tags extends Widget
{
    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('static::bootstrap-tagsinput/dist/bootstrap-tagsinput.css');
        $this->get('assets')->addScript('static::bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js');
    }

    public function run()
    {
        $this->setRun('tagsinput');
    }
}
