<?php
namespace Craigh\LaravelFileUploader;

use FileUploader\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LaravelFileUploader extends FileUploader {

	protected $uploadPath;

	/**
	 * Override parent constructor to take no parameters
	 */
	public function __construct()
	{
		// Set the default path to the public folder
		$this->uploadPath = $this->uploadPath ?: public_path();
	}
}