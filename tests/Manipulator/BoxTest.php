<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Todstoychev\Icr\Manipulator\Box;
use Todstoychev\Icr\Manipulator\Point;

/**
 * Class BoxTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class BoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Box
     */
    protected $box;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->box = new Box(100, 100);
    }

    /**
     * Tests width setter and getter
     */
    public function testWidth()
    {
        $this->box->setWidth(200);
        static::assertEquals(200, $this->box->getWidth());
    }

    /**
     * Tests height setter and getter
     */
    public function testHeight()
    {
        $this->box->setHeight(300);
        static::assertEquals(300, $this->box->getHeight());
    }

    /**
     * Tests scale with float
     */
    public function testScaleWithFloat()
    {
        $box = $this->box->scale(2.0);
        static::assertEquals(200, $box->getHeight());
        static::assertEquals(200, $box->getWidth());
    }

    /**
     * Test scale with string
     */
    public function testScaleWithString()
    {
        $box = $this->box->scale('2.0');
        static::assertEquals(200, $box->getHeight());
        static::assertEquals(200, $box->getWidth());
    }

    /**
     * Test scale with integer
     */
    public function testScaleWithInteger()
    {
        $box = $this->box->scale(2);
        static::assertEquals(200, $box->getHeight());
        static::assertEquals(200, $box->getWidth());
    }

    /**
     * Test scale with non numeric value
     */
    public function testScaleWithNonNumericString()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->box->scale('test');
    }

    /**
     * Test increase with integer
     */
    public function testIncreaseWithInteger()
    {
        $box = $this->box->increase(20);
        static::assertEquals(120, $box->getHeight());
        static::assertEquals(120, $box->getWidth());
    }

    /**
     * Tests increase with numeric string
     */
    public function testIncreaseWithNumericString()
    {
        $box = $this->box->increase('20');
        static::assertEquals(120, $box->getHeight());
        static::assertEquals(120, $box->getWidth());
    }

    /**
     * Tests increase with float
     */
    public function testIncreaseWithWithFloat()
    {
        $box = $this->box->increase(20.1);
        static::assertEquals(120, $box->getHeight());
        static::assertEquals(120, $box->getWidth());
    }

    /**
     * Tests increase with non numeric string
     */
    public function testsIncreaseWithNonNumericString()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->box->increase('test');
    }

    /**
     * Tests contains method
     */
    public function testContains()
    {
        static::assertFalse($this->box->contains(new Box(10, 10), new Point(new Box(1200, 1200))));
        static::assertFalse($this->box->contains(new Box(1000, 1000), new Point(new Box(120, 120))));
        static::assertFalse($this->box->contains(new Box(1000, 1000)));
        static::assertTrue($this->box->contains(new Box(10, 10)));
        static::assertTrue($this->box->contains(new Box(10, 10), new Point(new Box(10, 10))));
    }

    /**
     * Tests square
     */
    public function testSquare()
    {
        static::assertEquals(10000, $this->box->square());
        $this->box->setHeight(10);
        static::assertEquals(1000, $this->box->square());
    }

    /**
     * Tests to string
     */
    public function testToString()
    {
        static::assertEquals('100x100 px', (string) $this->box);
    }

    /**
     * Tests widen
     */
    public function testWiden()
    {
        $box = $this->box->widen(50);
        static::assertEquals(50, $box->getWidth());
        static::assertEquals(50, $box->getHeight());
    }

    /**
     * Tests widen with 0
     */
    public function testWidenWithZeroInput()
    {
        $box = $this->box->widen(0);
        static::assertEquals(0, $box->getHeight());
        static::assertEquals(0, $box->getWidth());
    }

    /**
     * Tests widen with 0 as height
     */
    public function testWidenWithZeroHeight()
    {
        $this->box->setHeight(0);
        static::setExpectedExceptionRegExp('InvalidArgumentException');
        $this->box->widen(50);
    }

    /**
     * Tests widen as 0 as width
     */
    public function testWidenWithZeroWidth()
    {
        $this->box->setWidth(0);
        static::setExpectedExceptionRegExp('InvalidArgumentException');
        $this->box->widen(50);
    }

    /**
     * Tests widen with negative argument
     */
    public function testWidenWithNegative()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->box->widen(-10);
    }

    /**
     * Tests heighten
     */
    public function testHeighten()
    {
        $box = $this->box->heighten(50);
        static::assertEquals(50, $box->getWidth());
        static::assertEquals(50, $box->getHeight());
    }

    /**
     * Test heighten as 0 for argument
     */
    public function testHeightenWithZeroInput()
    {
        $box = $this->box->heighten(0);
        static::assertEquals(0, $box->getHeight());
        static::assertEquals(0, $box->getWidth());
    }

    /**
     * Tests heighten with 0 as width
     */
    public function testHeightenWithZeroWidth()
    {
        $this->box->setWidth(0);
        static::setExpectedExceptionRegExp('InvalidArgumentException');
        $this->box->heighten(20);
    }

    /**
     * Tests heighten with 0 as height
     */
    public function testHeightenWithZeroHeight()
    {
        $this->box->setWidth(0);
        static::setExpectedExceptionRegExp('InvalidArgumentException');
        $this->box->heighten(20);
    }

    /**
     * Tests heighten with negative argument
     */
    public function testHeightenWithNegativeValue()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->box->heighten(-10);
    }
}
