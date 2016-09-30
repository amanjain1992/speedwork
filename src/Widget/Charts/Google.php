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
 * Google provides a variety of charts that are optimized to address your data visualization needs.
 * These charts are based on pure HTML5/SVG technology (adopting VML for old IE versions),
 * so no plugins are required. Adding these charts to your page can be done in a few simple steps.
 * offering intuitive, interactive charts to your web site or web application.
 * Plugin home page
 * ({@link https://developers.google.com/chart/}).
 *
 *
 * To use this widget, you may insert the following code:
 * <pre>
 * $this->get('resolver')->widget('googlecharts', array(
 *     												'selector'=>'#container',
 *													'type' => '',
 *													'var' => 'VariableName',[optional]
 *     												// additional javascript options, graph specific
 *     												'options'=>array(),
 * ));
 * </pre>
 */
namespace Turbo\Speedwork\Widget\Charts;

use Speedwork\Core\Widget;

class Google extends Widget
{
    /**
     * @var array list of default options these options will overwrite by options
     **/
    public $graphOptions = ['AreaChart'             => [],
                                 'BarChart'         => [],
                                 'BubbleChart'      => [],
                                 'CandlestickChart' => [],
                                 'ColumnChart'      => [],
                                 'ComboChart'       => [],
                                 'Gauge'            => [],
                                 'GeoChart'         => [],
                                 'LineChart'        => [],
                                 'PieChart'         => [],
                                 'ScatterChart'     => [],
                                 'SteppedAreaChart' => [],
                                 'Table'            => [],
                                 'TreeMap'          => [],
                                ];

    public $defaultPackage = 'corechart';
    public $packages       = ['Gauge'  => 'gauge',
                            'GeoChart' => 'geochart',
                            'Table'    => 'table',
                            'TreeMap'  => 'treemap', ];
    /**
     * @var sting file path of the js
     */
    public $jsFile = 'https://www.google.com/jsapi';

    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScriptUrl($this->jsFile);
    }

    /**
     * Function generates data google expected data array.
     */
    public function series(&$series, &$categories)
    {
        $return = [];
        if (is_array($categories)) {
            $return[] = "['".implode("','", $categories)."']";
        }
        foreach ($series as $k => $v) {
            if (is_array($v)) {
                $return[] = "['".$k."', ".implode(',', $v).']';
            } else {
                $return[] = "['".$k."', ".$v.']';
            }
        }

        unset($series, $categories);

        return '['.implode(',', $return).']';
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $selector = $this->options['selector'];
        $options  = $this->options['options'];

        $var  = ($options['var']) ? $options['var'] : 'chart';
        $type = ($options['type']) ? $options['type'] : 'ColumnChart';

        $chartoptions = ($options['chartoptions']) ? $options['chartoptions'] : $this->graphOptions[$type];

        $js = '<script type="text/javascript">';
        $js .= 'function googleInit'.$selector.'() {';
        if (isset($this->packages[$type])) {
            $js .= "google.load('visualization', '1', {'packages': ['".$this->packages[$type]."']});";
        } else {
            $js .= "google.load('visualization', '1', {'packages': ['".$this->defaultPackage."']});";
        }
        $js .= 'google.setOnLoadCallback(drawMarkersMap'.$selector.');';
        $js .= '}';

        $js .= 'if(google == undefined){';
        $js .= 'setTimeout("googleInit'.$selector.'()", 1000);';
        $js .= '}else{';
        $js .= 'googleInit'.$selector.'();';
        $js .= '}';

        $js .= 'function drawMarkersMap'.$selector.'() {';
        $js .= 'var googledata = google.visualization.arrayToDataTable('.$this->series($options['series'], $options['categories']).');';

        $js .= 'var googleoptions = '.json_encode($chartoptions).';';
        $js .= 'var '.$var." = new google.visualization.$type(document.getElementById('".$selector."'));";
        $js .= $var.'.draw(googledata, googleoptions);';
        $js .= '}';
        $js .= '</script>';

        $this->get('assets')->addCustomTag($js);
    }

    public function mergeArrays($Arr1, $Arr2)
    {
        foreach ($Arr2 as $key => $Value) {
            if (array_key_exists($key, $Arr1) && is_array($Value)) {
                $Arr1[$key] = $this->MergeArrays($Arr1[$key], $Arr2[$key]);
            } else {
                $Arr1[$key] = $Value;
            }
        }

        return $Arr1;
    }
}
