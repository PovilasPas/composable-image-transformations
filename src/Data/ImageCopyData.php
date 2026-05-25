<?php

namespace PovilasPas\ComposableImageTransformations\Data;

readonly class ImageCopyData
{
    public function __construct(
        public int $srcX,
        public int $srcY,
        public int $dstX,
        public int $dstY,
        public int $width,
        public int $height,
    ) {
    }
}