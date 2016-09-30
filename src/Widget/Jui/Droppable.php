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
 * JuiDroppable displays a droppable widget.
 *
 * JuiDroppable encapsulates the {@link http://jqueryui.com/demos/droppable/ JUI Droppable}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->get('resolver')->widget('jui.droppable', array(
 *     'selector'=>'#container',
 *     // additional javascript options for the droppable plugin
 *     'options'=>array(
 *         'scope'=>'myScope',
 *     ),
 * ));
 *
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI Droppable plugin. Please refer to
 * the {@link http://jqueryui.com/demos/droppable/ JUI Droppable} documentation
 * for possible options (name-value pairs).
 */
namespace Turbo\Speedwork\Widget\Jui;

use Speedwork\Core\Widget;

class Droppable extends Widget
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
        $this->setRun('droppable');
    }
}
