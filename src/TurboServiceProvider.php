<?php

/*
 * This file is part of the Speedwork package.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Turbo\Speedwork;

use Speedwork\Container\BootableInterface;
use Speedwork\Container\Container;
use Speedwork\Container\ServiceProvider;

class TurboServiceProvider extends ServiceProvider implements BootableInterface
{
    public function register(Container $app)
    {
    }

    public function boot(Container $app)
    {
        $apps = [
            'components' => [
                'members' => [
                    'namespace' => '\\Turbo\\Speedwork\\Component\\Members\\',
                    'views'     => __DIR__.'/Component/Members/',
                ],
                'menu' => [
                    'namespace' => '\\Turbo\\Speedwork\\Component\\Menu\\',
                    'views'     => __DIR__.'/Component/Menu/',
                ],
                'media' => [
                    'namespace' => '\\Turbo\\Speedwork\\Component\\Media\\',
                    'views'     => __DIR__.'/Component/Media/',
                ],
                'errors' => [
                    'namespace' => '\\Turbo\\Speedwork\\Component\\Errors\\',
                    'views'     => __DIR__.'/Component/Errors/',
                ],
            ],
            'modules' => [
                'menu' => [
                    'namespace' => '\\Turbo\\Speedwork\\Module\\Menu\\',
                    'views'     => __DIR__.'/Module/Menu/',
                ],
            ],
            'widgets' => [
                'speedwork' => '\\Turbo\\Speedwork\\Widget\\Speedwork\\',
                'nprogress' => '\\Turbo\\Speedwork\\Widget\\Nprogress\\',
                'noty'      => '\\Turbo\\Speedwork\\Widget\\Noty\\',
                'bootstrap' => '\\Turbo\\Speedwork\\Widget\\Bootstrap\\',
                'charts'    => '\\Turbo\\Speedwork\\Widget\\Charts\\',
                'jui'       => '\\Turbo\\Speedwork\\Widget\\Jui\\',
                'qtip'      => '\\Turbo\\Speedwork\\Widget\\Qtip\\',
                'tinymce'   => '\\Turbo\\Speedwork\\Widget\\Tinymce\\',
            ],
        ];

        $this->config('app.apps', $apps);
        $this->config('database.migrations', [__DIR__.'/migrations/']);

        if (!$app->isConsole()) {
            $routes = [
                'docs/([0-9\.]+)/(.*)' => 'index.php?option=docs&version=$1&page=$2',
                'logsin'               => 'index.php?option=members&view=login',
            ];

            $this->setRoutes($routes);

            $app['resolver']->widget('speedwork.jquery');
            $app['resolver']->widget('bootstrap');
            $app['resolver']->widget('nprogress');
            $app['resolver']->widget('speedwork');
            $app['resolver']->widget('noty');
            $app['resolver']->widget('qtip');
        }
    }
}
