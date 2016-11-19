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
namespace Turbo\Speedwork\Widget\Jui;

use Speedwork\Core\Widget;

class Jui extends Widget
{
    /**
     * Initializes the widget.
     * This method will publish JUI assets if necessary.
     * It will also register jquery and JUI JavaScript files and the theme CSS file.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function beforeRun()
    {
        $this->get('assets')->addStyleSheet('static::jquery-ui/themes/ui-lightness/jquery-ui.min.css');
        $this->get('assets')->addStyleSheet('static::jquery-ui/themes/ui-lightness/theme.css');
        $this->get('assets')->addScript('static::jquery-ui/jquery-ui.min.js');
    }

    public function run()
    {
    }
}
