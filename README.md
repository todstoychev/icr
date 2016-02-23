[![Build Status](https://travis-ci.org/todstoychev/icr.svg?branch=master)](https://travis-ci.org/todstoychev/icr)

This is an image manipulation module based on the [Imagine](https://github.com/avalanche123/Imagine) module. It is for Laravel 5.* php framework.

# Installation
Use the standard composer way:

```
composer require todstoychev/icr
```

Or add to your composer.json:

```json
"require": {
    ...
    "todstoychev/icr": "dev-master",
    ...
    }
```

and run 

```composer update```

# Configuration
In the ```'providers'``` section of your Laravel ```config/app.php``` add: ```Todstoychev\Icr\ServiceProvider::class,```. 

In the ```'aliases'``` you can add: ```'Icr' => \Todstoychev\Icr\Icr::class,```

Run ```php artisan vendor:publish --provider="Todstoychev\Icr\ServiceProvider"``` to publish the config or you cal use also ```php artisan vendor:publish --tag=icr```.

You will need also to set a new setting for the Storage module of Laravel. This can be done in the ```config/filesystem.php```.
You can create something similar:
```php
'local' => [
    'driver' => 'local',
    'root'   => public_path('/uploads/images'),
],
```

Put this one at the ```disks``` section of the file.

## The config file
The file can be found in ```config/``` it is called ```icr.php```. The file contains several sections and parameters.

### image_adapter
This is setting for the driver library that will be used to process the images. Imagine has support for Gd, Imagick and Gmagick, the available values are gd, imagick or gmagick. 

### Contexts
Those are the context settings. Each context is presented by array. The default one looks like this:

```php
'default' => [ 
        'small' => [
            'width' => 100,
            'height' => 100,
            'operation' => 'resize',
        ],
        'medium' => [
            'width' => 300,
            'height' => 300,
            'operation' => 'resize',
        ],
        'large' => [
            'width' => 600,
            'height' => 600,
            'operation' => 'resize',
        ],
    ],
```

Each context can define different sizes. The size array has 3 parameters: width, height and operation.

```php
'small' => [
            'width' => 32,
            'height' => 32,
            'operation' => 'resize-crop',
        ],
```

Allowed operations are crop, resize, scale, resize-crop. 
- crop - crops region from the center of the provided image;
- resize - resize image to given dimensions as keeps the proportions of the image;
- scale - scales the image to given dimensions;
- resize-crop - first resize the image while keeping the original proportions and then crops an region from the image center with the given size.

# Methods
To use the module call its basic class - Icr. The class contain 2 methods. One to upload images, the other to delete.
Another way to using it is to instantiate ```Todstoychev\Icr\Processor```

## Upload image
The Icr::uploadImage() method returns the file name on success. if any errors it returns an exception class instance. You can use something similar in your controller method: 

```php
$response = Icr::uploadImage($request->file('image'), 'my_context', 'images');

if ($response instanceof \Exception) {
    // Handle the error
} else {
    // Save the image name to database. Example: $myModel->saveImage($response);
}
```

As first argument you should use what is coming as file from the request, second argument is the context. The third argument is your setting name from the ```config/filesystem.php```. 

## Delete image
This can be performed with Icr::deleteImage(). Example:

```php
$response = Icr::deleteImage('my_file_name', 'my_context', 'images');

if ($response instanceof \Exception) {
    // Handle the error
} else {
    // Delete the image name from database. Example: $myModel->deleteImage($response);
}
```

Parameters are the same as in the uploadImage() method, except the first one which is the file name of the file that should be deleted.