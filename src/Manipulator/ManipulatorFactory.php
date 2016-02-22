<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;
use Todstoychev\Icr\Exception\IcrRuntimeException;

/**
 * Factory used to produce manipulation class instance
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ManipulatorFactory
{
    /**
     * @var Box
     */
    protected $box;

    /**
     * @var Point
     */
    protected $point;

    /**
     * @param Box $box
     * @param Point $point
     */
    public function __construct(Box $box, Point $point)
    {
        $this->box = $box;
        $this->point = $point;
    }

    /**
     * Creates manipulation instance
     *
     * @param string $operation Operation name
     *
     * @return mixed
     */
    public function create($operation)
    {
        if (!is_string($operation)) {
            throw new \LogicException("Provided manipulation name {$operation} is not a string!");
        }

        $operation = str_replace('-', '', $operation);
        $operation = strtolower($operation);
        $operation = ucfirst($operation);
        $operation = str_replace(' ', '', $operation);

        $className = "Todstoychev\\Icr\\Manipulator\\" . $operation;

        if (!class_exists($className)) {
            throw new IcrRuntimeException("Operation {$operation} does not exists!");
        }

        return new $className($this->box, $this->point);
    }
}