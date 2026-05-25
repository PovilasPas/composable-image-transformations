<?php

namespace PovilasPas\ComposableImageTransformations\Images;

use \GdImage as BaseGdImage;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageSaverInterface;

/**
 * @implements ImageSaverInterface<BaseGdImage>
 */
class GdImageJpgSaver implements ImageSaverInterface
{
    public function __construct(
        private string $filePath, 
        private int $quality = -1,
    ) {
    }

    /**
     * @param BaseGdImage $resource
     * @return void
     */
    public function save(mixed $resource): void
    {
        imagejpeg($resource, $this->filePath, $this->quality);
    }
}