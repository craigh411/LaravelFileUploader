<?php
namespace Humps\LaravelFileUploader;

use Humps\FileUploader\FileUploader;

class LaravelFileUploader extends FileUploader {

	protected $uploadDir;

	/**
	 * Override parent constructor to take no parameters
	 */
	public function __construct()
	{
		// Set the default path to the public folder
		$this->uploadDir = $this->uploadDir ?: public_path().'/';
	}
}