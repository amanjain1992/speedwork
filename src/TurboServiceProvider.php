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

/**
 * @author Sankar <sankar.suda@gmail.com>
 */
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
                'noty' => [
                    'namespace' => '\\Turbo\\Speedwork\\Component\\Noty\\',
                    'views'     => __DIR__.'/Component/Noty/',
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

        $this->registerConfig('app.apps', $apps);

        if (!$app->isConsole()) {
            $routes = [
                'register' => 'index.php?option=members&view=register',
                'login'    => 'index.php?option=members&view=login',
                'logout'   => 'index.php?option=members&view=logout',
                'me'       => 'index.php?option=members&view=me',
            ];

            $this->setRoutes($routes);

            $app['resolver']->widget('speedwork.jquery');
            $app['resolver']->widget('bootstrap');
            $app['resolver']->widget('nprogress');
            $app['resolver']->widget('speedwork');
            $app['resolver']->widget('noty');
            $app['resolver']->widget('qtip');
            $app['resolver']->widget('jui.autocomplete');

            $this->registerConfig('mail.templates', [__DIR__.'/Resource/mail/']);
        } else {
            $this->registerConfig('database.migrations', [__DIR__.'/Resource/migrations/']);

            $this->publishes([
                __DIR__.'/Component/Members/assets/*'       => '',
                __DIR__.'/Widget/Speedwork/assets/*.min.js' => '',
                __DIR__.'/Widget/Noty/assets/*'             => '',
                __DIR__.'/Widget/Qtip/assets/*'             => '',
                __DIR__.'/Widget/Tinymce/assets/*'          => '',
            ], 'assets');
        }
    }
}
