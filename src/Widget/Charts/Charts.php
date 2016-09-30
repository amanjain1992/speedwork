<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork\Widget\Charts;

use Speedwork\Core\Widget;

/**
 * chartWidget class file.
 *
 * This is the base class for all Chart widget classes.
 */
class Charts extends Widget
{
    /**
     * [$options description].
     *
     * @var array
     */
    public $options = [];

    public $publicCharts = [
        'type'    => 'high',
        'x-title' => '',
        'y-title' => '',
        'legend'  => true,
        'export'  => false,
    ];

    public function beforeRun()
    {
    }

    public function run()
    {
        $charts = config('charts');

        if (!is_array($charts)) {
            $charts = [];
        }

        $charts = array_merge($this->publicCharts, $charts);

        $this->options['globalOptions'] = $charts;

        $charts = ($charts['type']) ? $charts['type'] : 'high';

        if (strstr($this->options['options']['type'], '.')) {
            $type                             = explode('.', $this->options['options']['type']);
            $this->options['options']['type'] = $type[0];
            $charts                           = $type[1];
        }

        if ($charts == 'high') {
            $this->get('resolver')->widget('charts.high', $this->options);
        } elseif ($charts == 'flot') {
            $this->get('resolver')->widget('flot.'.$this->options['options']['type'], $this->options);
        } elseif ($charts == 'fusion') {
            $this->get('resolver')->widget('fusion', $this->options);
        }
    }
}
