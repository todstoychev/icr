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
     * Creates operation instance
     *
     * @param AbstractImage $abstractImage
     * @param string $operation
     * @param int $width
     * @param int $height
     *
     * @return ManipulatorFactory
     */
    public function create(AbstractImage $abstractImage, $operation, $width, $height)
    {
        $className = "Todstoychev\\Icr\\Manipulator\\" . ucfirst($operation);

        return new $className($abstractImage, $width, $height, $this->box, $this->point);
    }
}