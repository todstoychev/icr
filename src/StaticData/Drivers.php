<?php

namespace Todstoychev\Icr\StaticData;

/**
 * Represents available image resize drivers
 *
 * @package Todstoychev\Icr\StaticData
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Drivers
{
    /**
     * @var array
     */
    public static $allowedDrivers = [
        'gd',
        'imagick',
        'gmagick'
    ];
}