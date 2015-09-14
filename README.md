# Laravel File Uploader

Laravel File Uploader is a web form upload manager for Laravel. This package provides additional files for [FileUploader\FileUploader package](https://github.com/craigh411/FileUploader) for use within the Laravel framework.
FileUploader uses the same `symfony/HttpFoundation` package used by laravel for file uploads, but adds some additional functionality to make
file uploads a breeze.

## Features

- Allow and block specific mime types.
- Adjust maximum file size.
- Choose to automatically create upload directories.
- Allow file overwriting
- Automatically create sanitised file names.
- Create unique file names

## Installation

To install simply add `craigh/laravel-file-uploader` to you composer.json file and run composer update.

You should also register the service provider by adding the following to providers in `config/app.php`:
                    
`Craigh\LaravelFileUploader\FileUploaderServiceProvider::class`

### Creating an Alias (Optional)

While not required, if you want to use the facade you need to add the following to aliases in `config/app.php`:

`'Upload'	=> Craigh\LaravelFileUploader\Facades\UploaderFacade::class`

You will then be able to use the `Upload` alias to use the facade.

## Basic Usage

If you've added the alias for the facade you can simply use:

`Upload::file($request->file('file'))->move();`

from inside your classes.

If you are not using the facade you can type hint LaravelFileUploader in your method or constructor as follows:

```
private $upload;

function _construct(LaravelFileUploader $upload){
    $this->upload = $upload;
}

public function store(Request $request){
    $this->upload->file($request->file('file'))->move();
}
```

or if you prefer you can simple call:

'$upload = App::make('upload');'

from inside your method.

### Functions

 ##### uploadPath(string)
 Sets the upload path. This can also be set via the second parameter on the constructor (defaults to current directory)
 
 `$uploader->uploadPath('path/to/dir');`
 
 ##### overwrite(boolean)
 Set to true to allow overwriting of files with the same name (default: false)
 
 `$uploader->overwrite(true);`
 
 ##### allowedMimeTypes(array) 
 Pass in an array of allowed mime types, everything else will be blocked. When empty all file types will be allowed unless
 explicitly blocked.
 
 `$uploader->allowedMimeTypes(['image/jpeg,'image/png', 'image/gif']);`
 
 ##### blockedMimeTypes(array)
 You can also block file types if you prefer. Pass in an array of mime types you want to block
 
 `$uploader->blockedMimeTypes(['application/x-msdownload']);`
 
 
 ##### maxFileSize($size, $unit)
 The maximum file size you want to allow, expects size to be a number and unit to be either:
 - B - Byte
 - KB - Kilobyte
 - MB - Megabyte
 
 `$uploader->maxFileSize(5, 'MB');`
 
 You can also use the words BYTE, BYTES, KILOBYTE, KILOBYTES, MEGABYTE or MEGABYTES if you prefer:
 
 `$uploader->maxFileSize(1, 'MEGABYTE');`
 
 ##### createDirs(bool)
 If set to true this will recursively create any specified directories if they do not exist (default: false)
 
 `$uploader->createDirs(true);`
 
 ##### makeFilenameUnique(bool)
 If set to true this will make the filename unique by appending a _{number} to the end.
 
 `$uploader->makeFilenameUnique(true);`
 
 ##### filename(string)
 By default the filename will be a sanitised version of the uploaded filename. Use this method if you want to set your own filename.
 
 `$uploader->filename('myFile.txt');`
 
 **Note:** When using this method the filename will not be sanatised, if you want to sanatise the filename you can use the
 sanitizeFilename() method
 
 ##### sanitizeFilename()
 Sanitises the given filename by removing any dangerous characters and replaces any spaces with an underscore. You will only need to call this if you want to set your
 own filenames using the filename() method, otherwise this method is called automatically.
 You should also be aware that this call will need to be made after you set your filename:
 
 ```
 $uploader->filename('my%$crazy@filename.txt')->sanitizeFilename();
 ```
 
 ##### move() 
Moves the file to it's destination and returns the upload path.
 
 `$uploadPath = $uploader->move();`
 
 You can also use upload() which is an alias of move if you feel it's wording is more appropriate:
 
 `$uploadPath = $uploader->upload();`
 
 ### Chaining
 
 The easiest way to use LaravelFileUploader is to use chaining, e.g.
 
 ```
 Upload::file($request->all())
 ->createDirs(true)
 ->maxFileSize(5,'MB')
 ->allowedMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
 ->move();
```

### Configuring using Inheritance

For a cleaner configuration you can also extend the LaravelFileUploader class which gives you access to the following
protected variables:

```
protected $uploadPath = '/';
protected $allowedMimeTypes = [];
protected $blockedMimeTypes = [];
protected $maxFileSize = 1000000;
protected $makeFilenameUnique = false;
protected $overwrite = false;
protected $createDirIfNotExists = false;
```

e.g. For an ImageUploader you may do the following:
```
class ImageUploader extends LaravelFileUploader{
    
   protected $uploadPath = 'images/';
   protected $maxFileSize = 5+6e;
   protected $createDirIfNotExists = true;
   protected $makeFilenameUnique = true;
   
   protected $allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/gif'
   ];
  
}
```

This can now be used by method or constructor injection in your Controllers which will automatically use those defaults:

```
private $uploader;

public __constructor(ImageUploader $uploader){
    $this->uploader = $uploader;
}

public function store($request){
    $this->uploader->file($request->file('file'))->move();
}
```

## FileUploader\Uploader Interface

You can also bind to the FileUploader\Uploader interface. To do this place the following inside
the register method of your AppServiceProvider (providers\AppServiceProvider.php):

`App::bind('FileUploader\Contracts\Uploader','Craigh\LaravelFileUploader\LaravelFileUploader');`;

You can then type hint the interface instead of the concrete class:

```
use FileUploader\Uploader;
public __constructor(Uploader $uploader){
    $this->uploader = $uploader;
}
```

This then allows you to swap out your own implementations by simply by changing the bound class e.g.:

`App::bind('FileUploader\Contracts\Uploader','Your\Namespace\YourFileUploader');`;

That's it!









