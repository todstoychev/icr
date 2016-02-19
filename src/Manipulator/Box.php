<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;

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
        if (!is_numeric($width) || !is_numeric($height)) {
            throw new LogicException('Provided scale ration is NaN!');
        }

        if ($width < 0 || $height < 0) {
            throw new LogicException('Scale ratio is negative!');
        }

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
        if (!is_numeric($width)) {
            throw new LogicException('Box width is NaN!');
        }

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
        if (!is_numeric($height)) {
            throw new LogicException('Box height is NaN!');
        }

        $this->height = $height;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function scale($ratio)
    {
        if (!is_numeric($ratio)) {
            throw new LogicException('Provided scale ration is NaN!');
        }

        if ($ratio < 0) {
            throw new LogicException('Scale ratio is negative!');
        }

        $this->checkAttributes();

        return new Box(round($ratio * $this->width), round($ratio * $this->height));
    }

    /**
     * {@inheritdoc}
     */
    public function increase($size)
    {
        if (!is_numeric($size)) {
            throw new LogicException('Increase size is NaN!');
        }

        $width = (int) $size + $this->width;
        $height = (int) $size + $this->height;

        if ($width < 0) {
            throw new LogicException('Provided increase size produces negative width!');
        }

        if ($height < 0) {
            throw new LogicException('Provided increase size produces negative width!');
        }

        return new Box((int) $size + $this->width, (int) $size + $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function contains(BoxInterface $box, PointInterface $start = null)
    {
        $start = (null !== $start) ? $start : new Point(new Box(0, 0));

        return $start->in($this) &&
            $this->width >= $box->getWidth() + $start->getX() &&
            $this->height >= $box->getHeight() + $start->getY();
    }

    /**
     * {@inheritdoc}
     */
    public function square()
    {
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
        if (!is_numeric($width)) {
            throw new LogicException('Provided width is NaN!');
        }

        if ($width < 0) {
            throw new LogicException('Width can not be negative!');
        }

        $this->checkAttributes();

        return $this->scale($width / $this->width);
    }

    /**
     * {@inheritdoc}
     */
    public function heighten($height)
    {
        if (!is_numeric($height)) {
            throw new LogicException('Provided height is NaN!');
        }

        if ($height < 0) {
            throw new \LogicException('Height can not be negative!');
        }

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