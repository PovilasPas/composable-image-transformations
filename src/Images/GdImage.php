<?php

namespace PovilasPas\ComposableImageTransformations\Images;

use \GdImage as BaseGdImage;
use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageSaverInterface;

/**
 * @implements ImageInterface<BaseGdImage>
 */
class GdImage implements ImageInterface
{
    public function __construct(
        private BaseGdImage $resource
    ) {
    }

    public int $width { get => imagesx($this->resource); }
    public int $height { get => imagesy($this->resource); }

    public function getPixel(int $x, int $y): Color
    {
        $index = imagecolorat($this->resource, $x, $y);
        $color = imagecolorsforindex($this->resource, $index);

        return new Color($color['red'], $color['green'], $color['blue'], $color['alpha']);
    }

    public function setPixel(int $x, int $y, Color $color): void
    {
        $index = imagecolorallocatealpha($this->resource, $color->red, $color->green, $color->blue, $color->alpha);
        imagesetpixel($this->resource, $x, $y, $index);
    }

    public function withResource(callable $callable): void
    {
        $callable($this->resource);
    }

    /**
     * @param ImageSaverInterface<BaseGdImage> $saver
     * @return void
     */
    public function save(ImageSaverInterface $saver): void
    {
        $saver->save($this->resource);
    }

    /**
     * @param ImageInterface<BaseGdImage> $source
     * @param ImageCopyData $data
     */
    public function copy(ImageInterface $source, ImageCopyData $data): void 
    {
        $source->withResource(
            function (BaseGdImage $resource) use ($data): void 
            {
                imagecopy(
                    $this->resource,
                    $resource,
                    $data->dstX,
                    $data->dstY, 
                    $data->srcX, 
                    $data->srcY, 
                    $data->width, 
                    $data->height,
                );
            }
        );
    }
}