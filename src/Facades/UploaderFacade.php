<?php
namespace Humps\LaravelFileUploader\Facades;

use Illuminate\Support\Facades\Facade;

class UploaderFacade extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'upload';
	}
}