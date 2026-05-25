<?php

namespace PovilasPas\ComposableImageTransformations\Images;

use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageFactoryInterface;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;

class GdImageFactory implements ImageFactoryInterface
{
    public function supports(string $imageClass): bool
    {
        return $imageClass === GdImage::class;
    }

    public function createBlank(int $width, int $height): ImageInterface
    {
        $resource = imagecreatetruecolor($width, $height);
        imagealphablending($resource, false);
        imagesavealpha($resource, true);

        $transparent = imagecolorallocatealpha($resource, 0, 0, 0, 127);
        imagefill($resource, 0, 0, $transparent);

        return new GdImage($resource);
    }

    public function createFromFile(string $filePath): ImageInterface
    {
        $fileInfo = new \SplFileInfo($filePath);

        if (!$fileInfo->isFile()) {
            throw new \Exception('Provided file path is invalid');
        }

        $file = $fileInfo->openFile();
        $content = $file->fread($file->getSize());

        return $this->createFromBytes($content);
    }

    public function createFromBytes(string $bytes): ImageInterface
    {
        $image = imagecreatefromstring($bytes);

        if ($image === false) {
            throw new \Exception('The provided image is not in a supported format');
        }

        return new GdImage($image);
    }
}