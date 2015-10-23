<?php

return [
    'uploads_path' => 'uploads/images',

    'default' => [
        'small' => [
            'width' => 32,
            'height' => 32,
            'operation' => 'resize-crop'
        ],
        'medium' => [
            'width' => 200,
            'height' => 200,
            'operation' => 'resize-crop'
        ],
        'large' => [
            'width' => 400,
            'height' => 400,
            'operation' => 'resize-crop'
        ],
    ]
];
