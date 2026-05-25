<?php

namespace PovilasPas\ComposableImageTransformations\Images;

use \GdImage as BaseGdImage;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageSaverInterface;

/**
 * @implements ImageSaverInterface<BaseGdImage>
 */
class GdImagePngSaver implements ImageSaverInterface
{
    public function __construct(
        private string $filePath,
        private int $quality = -1,
        private int $filters = -1,
    ) {
    }

    /**
     * @param BaseGdImage $resource
     * @return void
     */
    public function save(mixed $resource): void
    {
        imagepng($resource, $this->filePath, $this->quality, $this->filters);
    }
}