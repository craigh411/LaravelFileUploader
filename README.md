# Laravel File Uploader

Laravel File Uploader is a web form upload manager for Laravel. This package provides additional files for the [craigh411/FileUploader](https://github.com/craigh411/FileUploader) package for use within the Laravel framework.
FileUploader uses the same `symfony/HttpFoundation` package used by laravel for file uploads, so is easily integrated, but adds some additional functionality to make file uploads a breeze.

## Features

- Allow and block specific mime types.
- Adjust maximum file size.
- Choose to automatically create upload directories.
- Allow file overwriting
- Automatically create sanitised file names.
- Create unique file names

## Installation

To install simply require `craigh/laravel-file-uploader` in your composer.json file and run composer update.

You should also register the service provider by adding the following to providers in `config/app.php`:
                    
`Humps\LaravelFileUploader\FileUploaderServiceProvider::class`

### Creating an Alias (Optional)

While not required, if you want to use the facade you need to add the following to aliases in `config/app.php`:

`'Upload'	=> Humps\LaravelFileUploader\Facades\UploaderFacade::class`

You will then be able to use the `Upload` alias to use the facade.

## Basic Usage

Beacuse LaravelFileUploader uses the `symfony/HttpFoundation` you can pass `$request->file('input_name')` directly into the file method.

If you've added the alias for the facade, then you can use:

`Upload::file($request->file('file'))->move();`

You will need to `use Upload` to get this to work if you are working inside a namespaced class.

If you are not using the facade you can type hint LaravelFileUploader in your classes methods or constructor or if you prefer you can simply call:

`$upload = App::make('upload');`

### Changing Settings Directly
 
If you want to change the default settings then you can simply chain them together like so (See Methods at the bottom for the full settings list):
 
```php
Upload::file($request->all())
->createDirs(true)
->maxFileSize(5,'MB')
->allowedMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
->move();
```

**Note:** The move() method returns the upload path, so must be chained to the end.

### Configuring Using Inheritance

For a clean configuration you can extend the LaravelFileUploader class which gives you access to the following
protected variables:

```php
protected $uploadDir; // Upload directory
protected $allowedMimeTypes = []; // Only allow these file to be uploaded
protected $blockedMimeTypes = []; // Don't allow these files to be uploaded
protected $maxFileSize = 1000000; // In bytes
protected $makeFilenameUnique = false; // Make the filename unique if two files have the same name
protected $overwrite = false; // Allow overwriting of files with the same name
protected $createDirs = false; // Allow the automatic creation of any upload directories
```

e.g. For an ImageUploader you may do the following:
```php
use Humps\LaravelFileUploader\LaravelFileUploader;

class ImageUploader extends LaravelFileUploader{
    
   protected $uploadDir = 'images/';
   protected $maxFileSize = 5+6e; // 5 MB
   protected $createDirs = true;
   protected $makeFilenameUnique = true;
   
   protected $allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/gif'
   ];
  
}
```

This can now be used in your classes which will automatically use those values as defaults:

```php
/**
 * Example using dependency injection
 **/
private $upload;

public __constructor(ImageUploader $upload){
    $this->upload = $upload;
}

public function store(Request $request){
    $this->upload->file($request->file('file'))->move();
}
```

## Handling Exceptions

`LaravelFileUploader` is shipped with an exception handler which redirects the user back to the page with any errors. To use the exception handler simply add the following to the `app\Exceptions\Handler.php` files' `render()` method:

```php
$uploadExceptionHandler = app()->make('uploadExceptionHandler', [$e]);
if ($errors = $uploadExceptionHandler->getUploadErrors()) {
  return $errors;
}
```

You can retreive the error message in your view from the `$errors` variable: (See: [http://laravel.com/docs/5.1/validation#quick-displaying-the-validation-errors](http://laravel.com/docs/5.1/validation#quick-displaying-the-validation-errors))

### Custom Error Messages

If you would like to create your own custom error messages for when an exception is thrown, you will need to do:

`php artisan vendor:publish`

which will publish the exceptions file to `app\resources\lang\vendor\laravel-file-uploader\en` which contains the error messages which are displayed for each exception.

**Note:** There currently aren't any localised error messages, so you will need to create these yourself if you do not want to  use English.

### Custom Exception Behaviour

If you would like to use the `LaravelFileUploaderExceptionHandler` but don't like the default behaviour of redirecting the user back, then you should override the `response()` method of the exception handler by extending the class. You will of course, need to change the code in your `boot()` method to reference your child class.

```php
use Humps\LaravelFileUploader\LaravelFileUploaderExceptionHandler as BaseHandler;

class CustomUploadExceptionHandler extends BaseHandler {
  protected function response(){
    die($this->error);
  }
}

```


## Humps\FileUploader\Uploader Interface

You can also bind to the Humps\FileUploader\Uploader interface. To do this place the following inside
the register() method of your AppServiceProvider:

`App::bind('Humps\FileUploader\Contracts\Uploader','Humps\LaravelFileUploader\LaravelFileUploader');`;

You can then type hint the interface instead of the concrete class:

```
use Humps\FileUploader\Uploader;

private $upload;

function __construct(Uploader $upload){
    $this->upload = $upload;
}
```

Now you can swap out your own implementations simply by changing the bound class e.g.:

`App::bind('Humps\FileUploader\Contracts\Uploader','Your\Namespace\YourFileUploader');`;


### Methods

##### uploadDir(string)
 Sets the upload directory. It will also append any required '/' if it is not set, so both 'path/to/dir' and 'path/to/dir/' will work.
 
`$upload->uploadDir('path/to/dir');`
 
 
**Note:** The public folder will be the base directory for your uploads, so if you set your upload path to 'images' (`$upload->uploadDir('images');`) this will upload to the public/images folder.

##### overwrite(boolean)
 Set to true to allow overwriting of files with the same name (default: false)
 
 `$upload->overwrite(true);`
 
##### allowedMimeTypes(array) 
Pass in an array of allowed mime types, everything else will be blocked. When empty all file types will be allowedunless  explicitly blocked.
 
`$upload->allowedMimeTypes(['image/jpeg, 'image/png', 'image/gif']);`
 
##### blockedMimeTypes(array)
You can also block file types if you prefer. Pass in an array of mime types you want to block
 
`$upload->blockedMimeTypes(['application/x-msdownload']);`
 
 
##### maxFileSize($size, $unit)
The maximum file size you want to allow, expects size to be a number and unit to be either:
- B - Byte
- KB - Kilobyte
- MB - Megabyte
 
`$upload->maxFileSize(5, 'MB');`
 
You can also use the words BYTE, BYTES, KILOBYTE, KILOBYTES, MEGABYTE or MEGABYTES if you prefer:
 
`$upload->maxFileSize(1, 'MEGABYTE');`
 
##### createDirs(bool)
If set to true this will recursively create any specified directories if they do not exist (default: false)
 
`$upload->createDirs(true);`
 
##### makeFilenameUnique(bool)
If set to true this will make the filename unique by appending a _{number} to the end.
 
`$upload->makeFilenameUnique(true);`
 
##### filename(string)
By default the filename will be a sanitised version of the uploaded filename. Use this method if you want to set your own filename.
 
`$upload->filename('myFile.txt');`
 
**Note:** When using this method the filename will not be sanatised, if you want to sanatise the filename you can use the sanitizeFilename() method.
 
##### sanitizeFilename()
Sanitises the given filename by removing any non alpha numeric characters and replacing any spaces with an underscore. You will only need to call this if you want to set your
own filenames using the filename() method, otherwise this method is called automatically.
You should also be aware that this call will need to be made after you set your filename:
 
```
$upload->filename('my%$crazy@filename.txt')->sanitizeFilename();
```
 
##### move() 
Moves the file to it's destination and returns the upload path.
 
`$uploadPath = $upload->move();`
 
You can also use `upload()` which is an alias of `move()` if you feel the wording is more appropriate:
 
`$uploadPath = $upload->upload();`

That's it!
