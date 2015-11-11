<?php

namespace Todstoychev\Icr\StaticData;

/**
 * Represent available operation names
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\StaticData
 */
class Operations
{
    /**
     * Holds the allowed operations names
     *
     * @var array
     */
    public static $allowedOperations = [
        'crop',
        'resize',
        'resize-crop',
        'scale'
    ];
}