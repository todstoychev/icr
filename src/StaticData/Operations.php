<?php

namespace Todstoychev\Icr\StaticData;

/**
 * Represent operations
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
        'resize-proportional'
    ];
}