<?php
/**
 * Created by admin.
 * User: realeff
 * Date: 18-1-15
 * Time: 上午8:14
 */

namespace BaiSam\UI;

use BaiSam\UI\Form\Helper as FormHelper;
use BaiSam\UI\Grid\Helper as GridHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class UIServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom the ui view of paths
        if (is_array($this->app->config['view']['paths'])) {
            foreach ($this->app->config['view']['paths'] as $viewPath) {
                if (is_dir($appPath = $viewPath.'/ui')) {
                    $this->app['view']->addNamespace('ui', $appPath);
                }
            }
        }

        // Load ui views
        $this->app['view']->addNamespace('ui', __DIR__.'/resources/views');
        // Load ui translations
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/', 'ui');


        if ($this->app->runningInConsole()) {
            // Publish config files
            $this->publishes([__DIR__.'/resources/config/config.php' => config_path('ui.php')], 'config');

            // Publish assets files
            //TODO ui资源需要进行加工后再发布市场
            $this->publishes([__DIR__.'/resources/assets' => resource_path('assets/ui')], 'ui');
        }
    }

    /**
     * Merges user's and ui's configs.
     *
     * @return void
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/resources/config/config.php', 'ui'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();

        $this->registerFormHelper();

        $this->registerGridHelper();

        //TODO Register chart

        // Load and merge the config
        $this->mergeConfig();
    }

    protected function registerResources()
    {
        $this->app->singleton('resources', function ($app) {
            $configuration = $app['config']['ui'];

            // 将新配置的UI资源导入$resources
            $resource = new UIRepository($app, Arr::get($configuration, 'resources', []));
            // Set base url for the form repository.
            $resource->setBaseUrl(Arr::get($configuration, 'baseUrl', ''));

            $this->registerBladeDirectives();

            return $resource;
        });

        $this->app->alias('resources', UIRepository::class);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('style', function ($expression) {
            if (empty($expression)) {
                return "<?php \$__resource->startStyle(\$_resource_name??'style'); ?>";
            }

            return "<?php \$__resource->startStyle({$expression}); ?>";
        });

        Blade::directive('endstyle', function () {
            return '<?php $__resource->stopStyle(); ?>';
        });

        Blade::directive('script', function ($expression) {
            if (empty($expression)) {
                return "<?php \$__resource->startScript(\$_resource_name??'script'); ?>";
            }

            return "<?php \$__resource->startScript({$expression}); ?>";
        });

        Blade::directive('endscript', function () {
            return '<?php $__resource->stopScript(); ?>';
        });
    }

    protected function registerFormHelper()
    {
        $this->app->singleton('form.helper', function ($app) {
            // 引用UI资源
            $resource = $app['resources'];

            return new FormHelper($app, $resource, $app['config']['ui']);
        });

        $this->app->alias('form.helper', '\BaiSam\UI\Form\Helper');
    }

    protected function registerGridHelper()
    {
        $this->app->singleton('grid.helper', function ($app) {
            // 引用UI资源
            $resource = $app['resources'];

            return new GridHelper($app, $resource, $app['config']['ui']);
        });

        $this->app->alias('grid.helper', '\BaiSam\UI\Grid\Helper');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'resources',
            'form.helper',
            'grid.helper'
        ];
    }

}