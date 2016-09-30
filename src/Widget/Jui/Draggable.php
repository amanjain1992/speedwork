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
 * JuiDraggable displays a draggable widget.
 *
 * JuiDraggable encapsulates the {@link http://jqueryui.com/demos/draggable/ JUI Draggable}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->get('resolver')->widget('jui.draggable', array(
 *     'selector'=>'#container',
 *     // additional javascript options for the draggable plugin
 *     'options'=>array(
 *         'handle'=>'p',
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI Draggable plugin. Please refer to
 * the {@link http://jqueryui.com/demos/draggable/ JUI Draggable} documentation
 * for possible options (name-value pairs).
 */
namespace Turbo\Speedwork\Widget\Jui;

use Speedwork\Core\Widget;

class Draggable extends Widget
{
    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('resolver')->widget('jui');
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $this->setRun('draggable');
    }
}
