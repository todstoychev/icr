<?php

namespace Todstoychev\Icr\State;

interface ImageStateInterface
{
    public function handleOriginalImage();

    public function performResize();

    public function performScale();

    public function performCrop();

    public function saveImageCopy();
}