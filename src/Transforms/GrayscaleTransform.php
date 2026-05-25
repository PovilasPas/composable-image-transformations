<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class GrayscaleTransform implements TransformInterface
{
    public function apply(ImageInterface $input): ImageInterface
    {
        $width = $input->width;
        $height = $input->height;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $oldColor = $input->getPixel($x, $y);
                $gray = (int) round(0.299 * $oldColor->red + 0.587 * $oldColor->green + 0.114 * $oldColor->blue);
                $newColor = new Color($gray, $gray, $gray, $oldColor->alpha);
                $input->setPixel($x, $y, $newColor);
            }
        }

        return $input;
    }
}