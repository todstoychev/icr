<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use InvalidArgumentException;

/**
 * Custom implementation of the imagine box interface
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Box implements BoxInterface
{
    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     * Constructs the Size with given width and height
     *
     * @param integer $width
     * @param integer $height
     */
    public function __construct($width = 0, $height = 0)
    {
        $this->width  = (int) $width;
        $this->height = (int) $height;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return Box
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return Box
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function scale($ratio)
    {
        $this->checkAttributes();

        return new Box(round($ratio * $this->width), round($ratio * $this->height));
    }

    /**
     * {@inheritdoc}
     */
    public function increase($size)
    {
        $this->checkAttributes();

        return new Box((int) $size + $this->width, (int) $size + $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function contains(BoxInterface $box, PointInterface $start = null)
    {
        $this->checkAttributes();

        $start = $start ? $start : new Point(0, 0);

        return $start->in($this) && $this->width >= $box->getWidth() + $start->getX() && $this->height >= $box->getHeight() + $start->getY();
    }

    /**
     * {@inheritdoc}
     */
    public function square()
    {
        $this->checkAttributes();

        return $this->width * $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%dx%d px', $this->width, $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function widen($width)
    {
        $this->checkAttributes();

        return $this->scale($width / $this->width);
    }

    /**
     * {@inheritdoc}
     */
    public function heighten($height)
    {
        $this->checkAttributes();

        return $this->scale($height / $this->height);
    }

    /**
     * Checks the attributes
     *
     * @throws InvalidArgumentException
     */
    protected function checkAttributes()
    {
        if ($this->width < 1 || $this->height < 1) {
            throw new InvalidArgumentException(sprintf('Length of either side cannot be 0 or negative, current size is %sx%s', $this->width, $this->height));
        }
    }
}