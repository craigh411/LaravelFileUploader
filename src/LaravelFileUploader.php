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
		/**
		 * Set the upload directory to one set by the child class
		 * or the 'public/' folder if instantiated directly
		 */
		$this->uploadDir = $this->uploadDir ?: public_path() . '/';
	}
}