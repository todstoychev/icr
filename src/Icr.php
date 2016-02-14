<?php

namespace Todstoychev\Icr;

use Todstoychev\Icr\Handler\OpenImage;
use Todstoychev\Icr\Manipulator\ManipulatorFactory;


/**
 * Class Icr
 *
 * @package Todstoychev\Icr
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Icr
{
    protected $config;

    protected $context;

    public function __construct(array $config, $context)
    {
        $this->config = $config;
    }
}