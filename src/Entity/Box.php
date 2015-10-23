<?php

namespace Todstoychev\Icr\Entity;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use Todstoychev\Icr\Entity\Point;

/**
 * Object representing the box. Necessary to resize the image.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Entity
 */
class Box implements BoxInterface
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $context;

    /**
     * Constructs the Size with given width and height
     *
     * @param int $width
     * @param int $height
     * @param string $name
     * @param string $context
     */
    public function __construct($width = 0, $height = 0, $name = '', $context = '')
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setName($name);
        $this->setContext($context);
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
     * @return Box
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Box
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return Box
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function scale($ratio)
    {
        return new Box(round($ratio * $this->width), round($ratio * $this->height));
    }

    /**
     * {@inheritdoc}
     */
    public function increase($size)
    {
        return new Box((int) $size + $this->width, (int) $size + $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function contains(BoxInterface $box, PointInterface $start = null)
    {
        $start = $start ? $start : new Point(0, 0);

        return $start->in($this) && $this->width >= $box->getWidth() + $start->getX() && $this->height >= $box->getHeight() + $start->getY();
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
        return $this->scale($width / $this->width);
    }

    /**
     * {@inheritdoc}
     */
    public function heighten($height)
    {
        return $this->scale($height / $this->height);
    }
}