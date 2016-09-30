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
 * Highcharts is a charting library written in pure JavaScript,
 * offering intuitive, interactive charts to your web site or web application.
 *
 * Plugin home page
 * ({@link http://www.highcharts.com/}).
 *
 *
 *
 * To use this widget, you may insert the following code:
 * <pre>
 * $this->get('resolver')->widget('highcharts', array(
 *     'selector'=>'#container',
 *	   'theme'	 => 'default', facebook|twitter|mitgux|growl
 *     // additional javascript options for the draggable plugin
 *     'options'=>array(
 *		  'text'=> 'Some text',
 *		  'type' => 'alert' // noty type (alert, success, error)
 *		),
 * ));
 * </pre>
 */
namespace Turbo\Speedwork\Widget\Charts;

use Speedwork\Core\Widget;

class High extends Widget
{
    /**
     * @var array list of default options these options will overwrite by options
     **/
    public $defaultOptions = ['chart' => ['type' => 'pie', 'renderTo' => 'chart', 'options3d' => [
        'enabled' => true,
        ' alpha'  => 45, ]],
                                   'title' => ['text' => 'Sample Title'],
                                   'xAxis' => ['categories' => 'js:[]'],
                                   'yAxis' => ['stackLabels' => ['enabled' => 'true'],
                                                   'min'     => 0,
                                                 ],
                                   'plotOptions' => ['column' => [//'stacking'=>'normal',
                                                                        'dataLabels' => ['enabled' => 'true'],
                                                                        'legend'     => ['enabled' => 'true'],
                                                                        ],
                                                        'pie' => ['allowPointSelect' => 'true',
                                                                     'cursor'        => 'pointer',
                                                                     'dataLabels'    => ['enabled'            => 'true',
                                                                                         'formatter'          => "js:function () {return '<b>'+ this.point.name+'</b>: '+ Math.round(this.percentage) +' %';}",
                                                                                         'showInLegend'       => 'true',
                                                                                         'percentageDecimals' => 1,
                                                                                         'valueDecimals'      => 2,
                                                                                         ],
                                                                     'showInLegend' => 'true',
                                                                     ],
                                                        ],
                                   'tooltip' => ['shared' => 'false',
                                                    //'crosshairs'=> 'true',
                                                    'useHTML'      => 'true',
                                                    'headerFormat' => '<b>{point.key}</b><table>',
                                                    'pointFormat'  => '<tr><td style=\"color: {series.color}\">{series.name}: </td><td style=\"text-align: right\"> <b>{point.y}</b></td></tr>',
                                                    'footerFormat' => '</table>',
                                                    //'valueDecimals' => 2,
                                                    'percentageDecimals' => 1,
                                                    ],
                                   'series'     => 'js:[]',
                                   'credits'    => ['enabled' => 'false'],
                                   'legend'     => ['enabled' => true],
                                   'exporting'  => ['enabled' => 'true'],
                                   'tooltipPie' => ['pointFormat' => '<b>{point.percentage}%</b>', 'percentageDecimals' => 1],
                                   'colors'     => ['#FF6040', '#A05030', '#D0B090', '#6600FF', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
                                   ];

    /**
     * @var sting default theme
     */
    public $theme = '';

    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript('highcharts-release/highcharts.js', 'bower');
    }

    public function series($series, $categories, $type = '', $title = '', $multi = false)
    {
        $return = [];
        foreach ($series as $k => $v) {
            $s = ['name' => trim($k)];

            $multi = (isset($v[0])) ? false : true;

            if ($multi) {
                $data = [];
                foreach ($categories as $key => $value) {
                    $total  = $series[$k][$key];
                    $data[] = ($total) ? $total : 0;
                }
            } else {
                $data   = [];
                $data[] = (is_array($v)) ? $v[0] : $v;
            }

            $s['data'] = $data;
            $return[]  = $s;
        }

        unset($series);

        return $this->seriesFormat($return, $type, $categories, $title);
    }

    /**
     * Public function to format the series.
     *
     * @param [type] $return     [description]
     * @param [type] $type       [description]
     * @param [type] $categories [description]
     *
     * @return [type] [description]
     */
    public function seriesFormat($return, $type, $categories)
    {
        if ($type == 'pie') {
            $d = [];
            foreach ($return as $v) {
                $d[] = "['".$v['name']."',".implode(',', $v['data']).']';
            }
            $series = ["{name:'".$title."'", 'data:['.implode(',', $d).']}'];
        } else {
            $series = [];

            foreach ($return as $v) {
                $series[] = "{name:'".$v['name']."',data:[".implode(',', $v['data']).']}';
            }
        }

        unset($return, $type, $d, $data);

        return '['.implode(',', $series).']';
    }

    /**
     * [categories description].
     *
     * @param array $categories [description]
     *
     * @return [type] [description]
     */
    public function categories($categories = [])
    {
        return "['".implode("','", $categories)."']";
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $selector = $this->options['selector'];
        $options  = $this->options['options'];

        if (empty($options['series'])) {
            return;
        }

        $var   = ($options['var']) ? $options['var'] : 'chart';
        $type  = ($options['type']) ? $options['type'] : 'column';
        $title = ($options['title']) ? $options['title'] : '';

        // merging the options with globally defined options
        if ($this->options['globalOptions']) {
            $options = array_merge($this->options['globalOptions'], $options);
        }

        unset($options['title']);

        $options['title']['text'] = $title;

        $options['chart']['renderTo'] = $selector;
        $options['chart']['type']     = $type;

        $options['exporting']['enabled'] = ($options['export'] === true) ? 'true' : 'false';

        if (isset($options['subtitle'])) {
            $options['subtitle']['text'] = $options['subtitle'];
        }

        $multi = (count($options['series']) == count($options['series'], COUNT_RECURSIVE)) ? false : true;

        if (is_array($options['series'])) {
            $options['series'] = 'js:'.$this->series($options['series'], $options['categories'], $type, $options['pieTitle'], $multi);
        }

        if ($type != 'pie') {
            if (is_array($options['categories'])) {
                $options['xAxis']['categories'] = 'js:'.$this->categories($options['categories']);
            }

            if ($options['x-title']) {
                $options['xAxis']['title']['text'] = $options['x-title'];
            }

            if ($options['y-title']) {
                $options['yAxis']['title']['text'] = $options['y-title'];
            }
            unset($this->defaultOptions['tooltipPie']);
        } else {
            unset($options['xAxis'], $options['yAxis']);
        }

        unset($options['categories'], $options['type'], $options['y-title'], $options['x-title'], $options['multi'], $options['tooltipPie']);

        $options = $this->mergeArrays($this->defaultOptions, $options);

        $typeOptions = $options['plotOptions'][$type];

        if ($typeOptions) {
            unset($options['plotOptions']);
            $options['plotOptions'][$type] = $typeOptions;
        }

        // for donut chart
        if ($type = 'pie') {
            $options['plotOptions']['pie']['innerSize'] = $options['donut']['innersize'];
            $options['plotOptions']['pie']['depth']     = $options['donut']['depth'];
            $options['legend']                          = ['enabled' => ($options['legend'] === true) ? 'true' : 'false'];
        } else {
            $options['plotOptions']['column']['legend']['enabled'] = ($options['legend'] === true) ? 'true' : 'false';
            $options['plotOptions']['pie']['depth']                = $options['donut']['depth'];
        }

        unset($typeOptions, $options['donut']);
        $options = $this->decode($options);

        $js   = 'var '.$var." = new Highcharts.Chart($options);";
        $ajax = $this->get('is_ajax_request');

        // for ajax request for dashboard
        if ($ajax) {
            echo $js;
        } else {
            $this->get('assets')->addScriptDeclaration($js);
        }
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
