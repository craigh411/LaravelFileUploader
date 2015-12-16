<?php


namespace Humps\LaravelFileUploader;

use Exception;
use Humps\FileUploader\Exceptions\DirectoryNotFoundException;
use Humps\FileUploader\Exceptions\FileSizeTooLargeException;
use Humps\FileUploader\Exceptions\InvalidFileTypeException;
use Humps\FileUploader\Exceptions\NoOverwritePermissionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LaravelFileUploaderExceptionsHandler
{
    protected $exception;
    protected $error;

    function __construct(Exception $e)
    {
        $this->exception = $e;
    }

    public function uploadErrors()
    {
        if ($this->exception instanceof DirectoryNotFoundException) {
            $this->error = trans('laravel-file-uploader::exceptions.directoryNotFound');
            return $this->response();
        }
        if ($this->exception instanceof FileSizeTooLargeException) {
            $this->error = trans('laravel-file-uploader::exceptions.fileSizeTooLarge');
            return $this->response();
        }
        if ($this->exception instanceof InvalidFileTypeException) {
            $this->error = trans('laravel-file-uploader::exceptions.invalidFileType');
            return $this->response();
        }
        if ($this->exception instanceof NoOverwritePermissionException) {
            $this->error = trans('laravel-file-uploader::exceptions.noOverwritePermission');
            return $this->response();
        }

        return null;
    }

    /**
     * The response when exceptions are thrown
     * @return mixed
     */
    protected function response()
    {
        return Redirect::back()->withInput()->withErrors($this->error);
    }

}