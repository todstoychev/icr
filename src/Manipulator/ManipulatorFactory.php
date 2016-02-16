<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

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
     * @param string $operation Operation name
     *
     * @return mixed
     */
    public function create($operation)
    {
        $className = "Todstoychev\\Icr\\Manipulator\\" . ucfirst($operation);

        return new $className($this->box, $this->point);
    }
}