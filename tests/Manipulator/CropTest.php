<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Imagine\Gd\Imagine;
use Imagine\Image\AbstractImage;
use Todstoychev\Icr\Manipulator;

/**
 * Class CropTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class CropTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractImage
     */
    protected $image;

    /**
     * @var Manipulator\Crop
     */
    protected $crop;

    /**
     * @var string
     */
    protected $path;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->image = new Imagine();
        $this->path = __DIR__ . '/../fixtures/test_image.png';
        $this->crop = new Manipulator\Crop(new Manipulator\Box(), new Manipulator\Point());
    }

    /**
     * Tests manipulate with correct values
     */
    public function testManipulateWithCorrectValues()
    {
        $image = $this->crop->manipulate($this->image->open($this->path), 200, 200);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with float
     */
    public function testManipulateWithFloat()
    {
        $image = $this->crop->manipulate($this->image->open($this->path), 200.10, 200.10);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with numeric string
     */
    public function testsManipulateWithNumericString()
    {
        $image = $this->crop->manipulate($this->image->open($this->path), '200', '200');
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Test manipulate with string
     */
    public function testManipulateWithNonNumericString()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->crop->manipulate($this->image->open($this->path), 'test', 'test');
    }

    /**
     * Test manipulate with large values
     */
    public function testManipulateWithTooLargeParameters()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\ImageTooSmallException');
        $this->crop->manipulate($this->image->open($this->path), 2000, 2000);
    }
}
