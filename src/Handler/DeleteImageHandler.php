<?php

namespace Todstoychev\Icr\Handler;

class DeleteImageHandler extends AbstractHandler
{
    public function deleteImageSizes($context, $fileName)
    {
        $contextPath = public_path($this->getUploadsPath() . '/' . $context);

        foreach ($this->getContextValues($context) as $key => $value) {
            $image = $contextPath . '/' . $context  . '/' . $fileName;

            is_file($image) ? unlink($image) : null;
        }
    }

    public function deleteImage($context, $fileName)
    {
        $this->deleteImageSizes($context, $fileName);

        $originalImage = public_path($this->getUploadsPath() . '/' . $context . '/' . $fileName);

        is_file($originalImage) ? unlink($originalImage) : null;
    }
}