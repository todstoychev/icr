<?php

return [
    'image_adapter' => 'gd',

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
        ]
    ]
];

