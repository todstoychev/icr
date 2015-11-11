<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * Abstract operation class
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Operation
 */
abstract class AbstractOperation
{

    /**
     * @var ImageInterface
     */
    protected $image;
    
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @param ImageInterface $image
     * @param int $width
     * @param int $height
     */
    public function __construct(ImageInterface $image, $width, $height)
    {
        $this->setImage($image);
        $this->setHeight($height);
        $this->setWidth($width);
    }

    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     *
     * @return AbstractOperation
     */
    public function setImage(ImageInterface $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return AbstractOperation
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return AbstractOperation
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Performs operation action
     *
     * @return AbstractOperation
     */
    abstract public function doAction();

    /**
     * Creates box object
     *
     * @return Box
     */
    abstract protected function createBox();
}