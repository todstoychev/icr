This is an image manipulation module based on the [Imagine](https://github.com/avalanche123/Imagine) module. It is for Laravel 5.* php framework.

# Installation
Use the standart composer way:

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
In the ```'providers'``` section of your Laravel ```config/app.php``` add:

```Todstoychev\Icr\ServiceProvider::class,```

In the ```'aliases'``` you can add: ```'Icr' => \Todstoychev\Icr\Icr::class,```

Run ```php artisan vendor:publish --provider="Todstoychev\Icr\ServiceProvider"``` to publish the config.

## The config file
The file can be found in ```config/icr/``` it is called ```config.php```. The file contains several sections and parameters.

### uploads_path
This parameter points to the directory where the images will be stored. The images are stored by default in the ```public/``` folder. This path points to location in the ```public``` folder.

### driver
This is setting for the driver library that will be used to process the images. ince Imagine has support for Gd, Imagick and Gmagick, the available values are gd, imagick or gmagick. 

### allowed_filetypes
This contains arrays with the allowed mimetypes/filetypes pairs. Those values can be defined per context.

###  output_format
This is setting to define the output format of the processed images. It is per context setting.

### Contexts
Those are the context settings. Each context is presented by array. The default one looks like this:

```php
'default' => [ 
        'small' => [
            'width' => 32,
            'height' => 32,
            'operation' => 'resize-crop',
        ],
        'medium' => [
            'width' => 100,
            'height' => 100,
            'operation' => 'resize-crop',
        ],
        'large' => [
            'width' => 200,
            'height' => 200,
            'operation' => 'resize-crop',
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

## Upload image
The Icr::uploadImage() method returns the file name on success. if any errors it returns an exception class instance. You can use something similar in your controller method: 

```php
$response = Icr::uploadImage($request->file('image'), 'my_context');

if ($response instanceof \Exception) {
    // Handle the error
} else {
    // Save the image name to database. Example: $myModel->saveImage($response);
}
```

## Delete image
This can be performed with Icr::deleteImage(). Example:

```php
$response = Icr::deleteImage('my_file_name', 'my_context');

if ($response instanceof \Exception) {
    // Handle the error
} else {
    // Delete the image name from database. Example: $myModel->deleteImage($response);
}
```
