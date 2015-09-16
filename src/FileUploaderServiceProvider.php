<?php
namespace Humps\LaravelFileUploader;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FileUploaderServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		App::bind('upload', function ()
		{
			return new LaravelFileUploader;
		});
	}
}
