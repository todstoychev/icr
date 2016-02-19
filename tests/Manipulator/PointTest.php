<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Todstoychev\Icr\Manipulator\Box;
use Todstoychev\Icr\Manipulator\Point;

/**
 * Class PointTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class PointTest extends \PHPUnit_Framework_TestCase
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
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->width = 10;
        $this->height = 10;
        $this->box = new Box($this->width, $this->height);
        $this->point = new Point();
    }

    /**
     * Tests get x with correct values
     */
    public function testGetXWithBoxSet()
    {
        $x = $this->point->setBox($this->box)
            ->getX();
        static::assertEquals($this->width, $x);
    }

    /**
     * Tests get x with no box set
     */
    public function testGetXWithoutBoxSet()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->point->getX();
    }
    
    /**
     * Tests get y with correct values
     */
    public function testGetYWithBoxSet()
    {
        $y = $this->point->setBox($this->box)
            ->getY();
        static::assertEquals($this->width, $y);
    }

    /**
     * Tests get y with no box set
     */
    public function testGetYWithoutBoxSet()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->point->getY();
    }

    /**
     * Tests in
     */
    public function testIn()
    {
        $result = $this->point->setBox($this->box)
            ->in(new Box(200, 200));
        static::assertTrue($result);
        $result = $this->point->setBox($this->box)
            ->in(new Box(5, 5));
        static::assertFalse($result);
    }

    /**
     * Test in throws exception
     */
    public function testInThrowsException()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->point->in(new Box(5, 5));
    }

    /**
     * Tests point move with correct values
     */
    public function testMoveWithCorrectValues()
    {
        $point = $this->point->setBox($this->box)
            ->move(10);
        static::assertEquals($this->width + 10, $point->getX());
        static::assertEquals($this->height + 10, $point->getY());
    }

    /**
     * Test to string
     */
    public function testToString()
    {
        $string = (string) $this->point->setBox($this->box);
        static::assertInternalType('string', $string);
    }

}
