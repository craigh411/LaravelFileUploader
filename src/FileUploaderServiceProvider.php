<?php
namespace Humps\LaravelFileUploader;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FileUploaderServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang/vendor/laravel-file-uploader', 'laravel-file-uploader');

        $this->publishes([
            __DIR__ . '/resources/lang/vendor/laravel-file-uploader' => base_path('resources/lang/vendor/laravel-file-uploader')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('upload', function () {
            return new LaravelFileUploader;
        });

        App::bind('uploadExceptionHandler', function ($app, $params) {
            return new LaravelFileUploaderExceptionHandler($params[0]);
        });

    }
}
