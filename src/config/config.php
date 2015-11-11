<?php

return [
    'uploads_path' => 'uploads/images',

    // Driver to use for creating the image object @example 'gd' or 'imagick' or 'gmagick'
    'driver' => 'gd',

    // Allowed filetypes necessary for validating the input image. Configuration per context
    'allowed_filetypes' => [
        'default' => [
            'image/jpeg' => [
                'jpeg',
                'jpg',
            ],
            'image/png' => [
                'png',
            ],
            'image/gif' => [
                'gif',
            ],
        ],
    ],

    // Context output format
    'output_format' => [
        'default' => '.jpg',
    ],

    // Context settings
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
];
