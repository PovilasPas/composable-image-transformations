<?php

namespace PovilasPas\ComposableImageTransformations\Images\Abstraction;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;

/**
 * @template T
 */
interface ImageInterface
{
    public int $width { get; }
    public int $height { get; }

    public function getPixel(int $x, int $y): Color;
    public function setPixel(int $x, int $y, Color $color): void;
    public function withResource(callable $callable): void;
    /**
     * @param ImageSaverInterface<T> $saver
     * @return void
     */
    public function save(ImageSaverInterface $saver): void;
    /**
     * @param ImageInterface<T> $source
     * @param ImageCopyData $data
     * @return void
     */
    public function copy(ImageInterface $source, ImageCopyData $data): void;
}