<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class InvertColorsTransform implements TransformInterface
{
    public function apply(ImageInterface $input): ImageInterface
    {
        $width = $input->width;
        $height = $input->height;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = $input->getPixel($x, $y);
                $invertedColor = new Color(255 - $color->red, 255 - $color->green, 255 - $color->blue, $color->alpha);
                $input->setPixel($x, $y, $invertedColor);
            }
        }

        return $input;
    }
}