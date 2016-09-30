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
namespace Turbo\Speedwork\Widgets;

use Speedwork\Core\Widget;

class Raty extends Widget
{
    public $defaultOptions = [
        'starType'  => 'i',
        'scoreName' => 'rating',
    ];

    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('raty/lib/jquery.raty.css', 'bower');
        $this->get('assets')->addScript('raty/lib/jquery.raty.js', 'bower');
    }

    public function run()
    {
        $this->setRun('raty');
    }
}
