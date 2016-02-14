<?php

namespace Todstoychev\Icr\Manager;

use Illuminate\Filesystem\FilesystemAdapter;

/**
 * Class UniqueFileNameGenerator
 *
 * @package Todstoychev\Icr
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class UniqueFileNameGenerator
{
    /**
     * Generates unique filename
     *
     * @param FilesystemAdapter $filesystemAdapter
     * @param string $extension File extension
     * @param null|string $path Directory path
     *
     * @return string
     */
    public function generate(FilesystemAdapter $filesystemAdapter, $extension, $path = null)
    {
        $hash = sha1(time() + microtime());

        $exists = $filesystemAdapter->exists($path . '/' . $hash . '.' . $extension);

        if ($exists) {
            $this->generate($filesystemAdapter, $extension, $path);
        }

        return $hash;
    }
}