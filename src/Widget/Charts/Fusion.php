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
 * Fusioncharts is a charting library written in pure JavaScript,
 * offering intuitive, interactive charts to your web site or web application.
 *
 * Plugin home page
 * ({@link http://www.fusioncharts.com/}).
 *
 *
 *
 * To use this widget, you may insert the following code:
 * <pre>
 * $this->get('resolver')->widget('fusioncharts', array(
 *     'selector'=>'#container',
 *     'theme'   => 'default', facebook|twitter|mitgux|growl
 *     // additional javascript options for the draggable plugin
 *     'options'=>array(
 *        'text'=> 'Some text',
 *        'type' => 'alert' // noty type (alert, success, error)
 *      ),
 * ));
 * </pre>
 */
namespace Turbo\Speedwork\Widget\Charts;

use Speedwork\Core\Widget;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Fusion extends Widget
{
    public $defaultChartOptions = [
        'type'       => 'column2D',
        'renderAt'   => 'chart',
        'dataFormat' => 'json',
        'width'      => '95%',
        'height'     => '100%',
        'dataSource' => [
            'chart' => [
                'caption'              => 'Main Caption',
                'subCaption'           => 'Sub Caption',
                'bgColor'              => '#ffffff',
                'bgAlpha'              => '0',
                'showpercentvalues'    => '0',
                'showpercentintooltip' => '0',
                'showBorder'           => '0',
                'showShadow'           => '0',
                'enableRotation'       => '0',
                'use3dlighting'        => '0',
                'manageResize'         => '1',
                'formatNumberScale'    => '0',
                'legendnumcolumns'     => '3',
                'showValues'           => '0',
                'toolTipBorderColor'   => '#FFFFFF',
                'toolTipBgColor'       => '#666666',
                'toolTipBgAlpha'       => '80',
                'anchorAlpha'          => '100',
                'anchorBgColor'        => '#d8524e',
                /*'useDataPlotColorForLabels'=> '1',*/
                'showLegend'     => '0',
                'showPlotBorder' => '0',
                'palettecolors'  => '#d8524e,#1790E1,#1bae99,#6baa01,#f8bd19,#d35400,#bdc3c7,#95a5a6,#34495e,#1abc9c',
                'theme'          => 'fint',
                ],
            ],
        ];

    /**
     * Run this method before this widget run.
     * This method used to run the jui widget.
     */
    public function beforeRun()
    {
        $this->get('assets')->addScript(__DIR__.'/assets/js/themes/fusioncharts.theme.fint.js');
    }

    public function series($series, $categories, $type, $multi)
    {
        $data = [];

        foreach ($series as $k => $v) {
            $multi = (isset($v[0])) ? false : true;

            if (!$multi) {
                $data['data'][] = (is_array($v)) ? ['label' => $k, 'value' => $v[0]] : ['label' => $k, 'value' => $v];
            } else {
                $seriesname = $k;

                foreach ($categories as $value) {
                    $temp['category'][] = ['label' => $value];

                    if (!$v[$value]) {
                        $v[$value] = '0';
                    }
                    $data['data'][] = ['value' => $v[$value]];
                }

                $data['categories'] = [$temp];

                if (isset($v['renderAs'])) {
                    $data['dataset'][] = ['seriesname' => $seriesname, 'renderAs' => $v['renderAs'], 'data' => $data['data']];
                } else {
                    $data['dataset'][] = ['seriesname' => $seriesname, 'renderAs' => $type, 'data' => $data['data']];
                }

                unset($data['data'], $seriesname, $temp);
            }
        }

        return $data;
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
        $selector = $this->options['selector'];
        $options  = $this->options['options'];

        if ($this->options['globalOptions']) {
            $options = array_merge($this->options['globalOptions'], $options);
        }

        if (empty($options['series'])) {
            return;
        }

        foreach ($options['series'] as $value) {
            if (is_array($value)) {
                $multi = (isset($value[0])) ? false : true;

                if (isset($value['renderAs'])) {
                    $combi = true;
                }
            }
        }

        if (is_array($options['series'])) {
            $options['dataSource']                        = $this->series($options['series'], $options['categories'], $options['type'], $multi);
            $options['dataSource']['chart']['caption']    = ($options['title']) ? strip_tags($options['title']) : '';
            $options['dataSource']['chart']['subCaption'] = ($options['subtitle']) ? strip_tags($options['subtitle']) : '';
        } else {
            return;
        }

        $dimension       = (stristr($options['type'], '3d')) ? '3d' : '';
        $globalDimension = ($options['dimension'] == '3d') ? '3d' : '';

        $options['type'] = rtrim($options['type'], $dimension);
        $options['type'] = ($dimension || $globalDimension) ? $this->getChartType($options['type'], $multi, $combi, $options['stacked'], '3d') : $this->getChartType($options['type'], $multi, $combi, $options['stacked'], '2d');

        if ($options['xAxis']['title']['text'] || $options['x-title']) {
            if ($options['xAxis']['title']['text']) {
                $options['dataSource']['chart']['xaxisname'] = $options['xAxis']['title']['text'];
            } else {
                $options['dataSource']['chart']['xaxisname'] = $options['x-title'];
            }

            if ($options['xAxis']['labels']['rotation']) {
                $options['dataSource']['chart']['labelDisplay'] = 'rotate';
                $options['dataSource']['chart']['slantLabels']  = '1';
            }
        }

        if ($options['yAxis']['title']['text'] || $options['y-title']) {
            if ($options['yAxis']['title']['text']) {
                $options['dataSource']['chart']['yaxisname'] = $options['yAxis']['title']['text'];
            } else {
                $options['dataSource']['chart']['yaxisname'] = $options['y-title'];
            }
        }

        $options['dataSource']['chart']['exportenabled'] = ($options['export'] === true) ? '1' : '0';
        $options['dataSource']['chart']['showLegend']    = ($options['legend'] || $options['legend']['enabled']) ? '1' : '0';

        $options['dataSource']['chart']['showpercentvalues']    = ($options['showPercentValues']) ? '1' : '0';
        $options['dataSource']['chart']['showpercentintooltip'] = ($options['showPercentValues']) ? '1' : '0';

        $options             = $this->mergeArrays($this->defaultChartOptions, $options);
        $options['renderAt'] = $selector;

        unset($options['categories'], $options['series'], $options['titleText'], $options['xAxis'], $options['yAxis'], $options['title'], $options['tooltip'], $options['legend'], $options['donut'], $options['export']);

        $options = json_encode($options);

        $js = "eve.on('raphael.new', function () {
            this.raphael._url = this.raphael._g.win.location.href.replace(/#.*?$/, '');
            });";

        $js .= 'FusionCharts.ready(function () {var fusionChart = new FusionCharts('.$options.');fusionChart.render();})';

        $ajax = $this->get('is_ajax_request');

        // for ajax request for dashboard
        if ($ajax) {
            echo $js;
        } else {
            $this->get('assets')->addScriptDeclaration($js);
        }
    }

    public function mergeArrays($a, $b)
    {
        foreach ($b as $key => $Value) {
            if (array_key_exists($key, $a) && is_array($Value)) {
                $a[$key] = $this->MergeArrays($a[$key], $b[$key]);
            } else {
                $a[$key] = $Value;
            }
        }

        return $a;
    }

    public function getChartType($type, $multi, $combi, $stacked, $dimension)
    {
        switch ($type) {
            case 'spline':
                if ($multi) {
                    $type = 'ms'.$type;
                }
                break;
            case 'splinearea':
                if ($multi) {
                    if ($combi) {
                        $type = 'mscombi'.$dimension;
                    } else {
                        $type = 'ms'.$type;
                    }
                } else {
                    $type = $type.'2d';
                }
                break;
            case 'line':
                if ($multi) {
                    if ($combi) {
                        $type = 'mscombi'.$dimension;
                    } else {
                        $type = 'ms'.$type;
                    }
                }
                break;

            case 'area':
                if ($multi) {
                    if ($combi) {
                        $type = 'mscombi'.$dimension;
                    } elseif ($stacked) {
                        $type = 'stacked'.$type.$dimension;
                    } else {
                        $type = 'ms'.$type;
                    }
                } else {
                    $type = $type.'2d';
                }
                break;

            case 'column':
                if ($multi) {
                    if ($combi) {
                        $type = 'mscombi'.$dimension;
                    } elseif ($stacked) {
                        $type = 'stacked'.$type.$dimension;
                    } else {
                        $type = 'ms'.$type.$dimension;
                    }
                } else {
                    $type = $type.$dimension;
                }
                break;

            case 'pie':
                $type = $type.$dimension;
                break;

            case 'doughnut':
                $type = $type.$dimension;
                break;

            case 'bar':
                if ($multi) {
                    if ($combi) {
                        $type = 'mscombi'.$dimension;
                    } elseif ($stacked) {
                        $type = 'stacked'.$type.$dimension;
                    } else {
                        $type = 'ms'.$type.$dimension;
                    }
                } else {
                    $type = $type.$dimension;
                }
                break;
            default:
                $type = 'column'.$dimension;
        }

        return $type;
    }
}
