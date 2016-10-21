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
 * TinyMCE - Javascript WYSIWYG Editor
 * TinyMCE has the ability to convert HTML TEXTAREA fields or
 * other HTML elements to editor instances.
 * TinyMCE is very easy to integrate.
 *
 * It encapsulates the excellent plugin
 * ({@link http://tinymce.moxiecode.com/}).
 *
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->get('resolver')->widget('tinymce', array(
 *     'selector' => '#container',
 *	   'type'	  => 'full|basic'
 *     // additional javascript options for the this plugin
 *     'options'=>array(
 *         'theme'=>'advanced',
 *     ),
 * ));
 *
 *
 * </pre>
 */
namespace Turbo\Speedwork\Widget\Tinymce;

use Speedwork\Core\Widget;

class Tinymce extends Widget
{
    /**
     * @var sting file path of the js
     */
    public $scripts = [
        'jquery.tinymce.min.js',
        'tinymce.min.js',
    ];

    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript('assets::tinymce.js');
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
    }
}
