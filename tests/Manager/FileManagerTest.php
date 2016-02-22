<?php

namespace Todstoychev\Icr\Tests\Manager;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Todstoychev\Icr\Manager\FileManager;

/**
 * Class FileManagerTest
 *
 * @package Todstoychev\Icr\Tests\Manager
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class FileManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var FilesystemAdapter
     */
    protected $filesystemAdapter;

    /**
     * @var FileManager
     */
    protected $fileManager;

    /**
     * @var string
     */
    protected $file;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->rootPath = __DIR__ . '/../fixtures/';
        $this->filesystemAdapter = new FilesystemAdapter(new Filesystem(new Local($this->rootPath)));
        $this->fileManager = new FileManager();
        $this->file = file_get_contents($this->rootPath . '/test_image.png');
    }

    /**
     * Test file upload with correct data
     */
    public function testUploadFileWithCorrectData()
    {
        $context = 'test';
        $size = 'default';
        $this->fileManager->setFileSystemAdapter($this->filesystemAdapter);
        $fileName1 = $this->fileManager->uploadFile($this->file, 'png', $context);
        $uploadedFile = file_get_contents($this->rootPath . "{$context}/" . $fileName1);
        static::assertEquals($uploadedFile, $this->file);

        $fileName2 = $this->fileManager->uploadFile($this->file, 'png', $context, $size);
        $uploadedFile = file_get_contents($this->rootPath . "{$context}/{$size}/" . $fileName2);
        static::assertEquals($uploadedFile, $this->file);

        $this->fileManager->uploadFile($this->file, 'png', $context, $size, 'test_image.png');
        $uploadedFile = file_get_contents($this->rootPath . "{$context}/{$size}/test_image.png");
        static::assertEquals($uploadedFile, $this->file);

        unlink($this->rootPath . "{$context}/" . $fileName1);
        unlink($this->rootPath . "{$context}/{$size}/" . $fileName2);
        unlink($this->rootPath . "{$context}/{$size}/test_image.png");
        rmdir($this->rootPath . "{$context}/{$size}/");
        rmdir($this->rootPath . "{$context}/");
    }

    /**
     * Test file upload exception
     */
    public function testFileUploadThrowsException()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->fileManager->uploadFile($this->file, 'png');
    }

    /**
     * Test path generation
     */
    public function testPathGeneration()
    {
        $context = 'context';
        $sizeName = 'size';
        $path = $this->fileManager->path($context, $sizeName);
        static::assertEquals("/{$context}/{$sizeName}/", $path);

        $path = $this->fileManager->path(null, $sizeName);
        static::assertEquals("/{$sizeName}/", $path);

        $path = $this->fileManager->path($context, null);
        static::assertEquals("/{$context}/", $path);
    }

    /**
     * Tests generate filename
     */
    public function testGenerateFileName()
    {
        $filename = $this->fileManager->generate($this->filesystemAdapter, 'png', $this->rootPath);

        static::assertInternalType('string', $filename);
    }
}
