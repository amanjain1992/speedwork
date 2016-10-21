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
namespace Turbo\Speedwork\Widget;

use Speedwork\Core\Widget;

class Crop extends Widget
{
    protected $defaultOptions = [
        'aspectRatio' => 1,
        'minSize'     => [200, 200],
        'setSelect'   => [200, 200, 50, 50],
        'onSelect'    => "js:function(c) {
            $('.co-croping').val(JSON.stringify(c));
        }",
    ];

    public function beforeRun()
    {
        $this->get('assets')->addScript('static::Jcrop/js/Jcrop.min.js');
        $this->get('assets')->addStyleSheet('static::Jcrop/css/Jcrop.min.css');
    }

    public function run()
    {
        $this->setRun('Jcrop');
    }
}
