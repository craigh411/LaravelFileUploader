<?php
namespace Humps\FileUploader\Tests;

require 'helper.php';

use Humps\LaravelFileUploader\LaravelFileUploader;
use PHPUnit_Framework_TestCase;

class LaravelFileUploaderTest extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function it_sets_the_upload_path_to_the_default_folder()
	{
		$upload = new LaravelFileUploader();
		$this->assertEquals('public/', $upload->getUploadDir());
	}

	/**
	 * @test
	 */
	public function it_allows_a_child_class_to_set_the_upload_path()
	{
		$upload = new UploaderTest();
		$this->assertEquals('uploads/', $upload->getUploadDir());
	}
}

// Little test class to mock laravel extender
class UploaderTest extends LaravelFileUploader {
	protected $uploadDir = 'uploads/';
}