<?php

namespace Todstoychev\Icr\Tests\Handler;

use Todstoychev\Icr\Handler\OpenImageHandler;


/**
 * Class OpenImageHandlerTest
 *
 * @package Todstoychev\Icr\Tests\Handler
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class OpenImageHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OpenImageHandler
     */
    protected $openImageHandler;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $nonImagePath;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->openImageHandler = new OpenImageHandler();
        $this->path = __DIR__ . '/../fixtures/test_image.png';
        $this->nonImagePath = __DIR__ . '/../fixtures/non_image.png';

        $this->file = file_get_contents($this->path);
    }

    /**
     * Test load image with GD
     */
    public function testLoadImageWithGd()
    {
        $gdInfo = gd_info();

        if (!empty($gdInfo)) {
            $result = $this->openImageHandler->setImageLibrary('gd')
                ->loadImage($this->file);
            static::assertInstanceOf('Imagine\Gd\Image', $result);

            // Use non image string
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->setImageLibrary('gd')
                ->loadImage('test string');
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->setImageLibrary('gd')
                ->loadImage($this->file);
        }
    }

    /**
     * Test load with Imagick
     */
    public function testLoadImageWithImagick()
    {
        $isInstalled = class_exists('Imagick');

        if ($isInstalled) {
            $result = $this->openImageHandler->setImageLibrary('imagick')
                ->loadImage($this->file);
            static::assertInstanceOf('Imagine\Imagick\Image', $result);

            // Set some string
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->setImageLibrary('imagick')
                ->loadImage('some string');
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');

            $this->openImageHandler->setImageLibrary('imagick')
                ->loadImage($this->file);
        }
    }

    /**
     * Test load image with Gmagick
     */
    public function testLoadImageWithGmagick()
    {
        $isInstalled = class_exists('Gmagick');

        if ($isInstalled) {
            $result = $this->openImageHandler->setImageLibrary('gmagick')
                ->loadImage($this->file);
            static::assertInstanceOf('Imagine\Gmagick\Image', $result);

            // Set some string
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->setImageLibrary('gmagick')
                ->loadImage('some string');
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');

            $this->openImageHandler->setImageLibrary('gmagick')
                ->loadImage($this->file);
        }
    }

    /**
     * Tests open image with gd
     */
    public function testOpenImageWithGd()
    {
        $gdInfo = gd_info();

        $this->openImageHandler->setImageLibrary('gd');

        if (!empty($gdInfo)) {
            $result = $this->openImageHandler->openImage($this->path);
            static::assertInstanceOf('Imagine\Gd\Image', $result);

            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->nonImagePath);
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->path);
        }
    }

    /**
     * Test open image with imagick
     */
    public function testOpenImageWithImagick()
    {
        $isInstalled = class_exists('Imagick');
        $this->openImageHandler->setImageLibrary('imagick');

        if ($isInstalled) {
            $result = $this->openImageHandler->openImage($this->path);
            static::assertInstanceOf('Imagine\Imagick\Image', $result);

            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->nonImagePath);
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->path);
        }
    }

    /**
     * Test open image with gmagick
     */
    public function testOpenImageWithGmagick()
    {
        $isInstalled = class_exists('Gmagick');

        $this->openImageHandler->setImageLibrary('gmagick');

        if ($isInstalled) {
            $result = $this->openImageHandler->openImage($this->path);
            static::assertInstanceOf('Imagine\Gmagick\Image', $result);

            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->nonImagePath);
        } else {
            static::setExpectedExceptionRegExp('Imagine\Exception\RuntimeException');
            $this->openImageHandler->openImage($this->path);
        }
    }

    /**
     * Try to load image with non existing library
     */
    public function testLoadImageWithWrongImageLibraryName()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->openImageHandler->setImageLibrary('test');
        $this->openImageHandler->loadImage($this->path);
    }

    public function testOpenImageWithWrongImageLibraryName()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->openImageHandler->setImageLibrary('test');
        $this->openImageHandler->openImage($this->path);
    }
}
