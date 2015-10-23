<?php

namespace Todstoychev\Icr\Entity;

use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\PointInterface;

/**
 * Object representing the point
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Entity
 */
class Point implements PointInterface
{
    /**
     * @var integer
     */
    protected $x;

    /**
     * @var integer
     */
    protected $y;

    /**
     * Constructs a point of coordinates
     *
     * @param int $x
     * @param int $y
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->setX($x);
        $this->setY($y);
    }

    /**
     * {@inheritdoc}
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return mixed
     */
    public function setY($y)
    {
        $this->y = $y;

        return $y;
    }

    /**
     * {@inheritdoc}
     */
    public function in(BoxInterface $box)
    {
        return $this->x < $box->getWidth() && $this->y < $box->getHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function move($amount)
    {
        return new Point($this->x + $amount, $this->y + $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('(%d, %d)', $this->x, $this->y);
    }

}