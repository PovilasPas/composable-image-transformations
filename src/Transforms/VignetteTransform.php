<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class VignetteTransform implements TransformInterface
{
    public function __construct(
        private float $strength,
    ) {
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        $width = $input->width;
        $height = $input->height;
        $cx = $width / 2;
        $cy = $height / 2;
        $maxDistance = sqrt($cx * $cx + $cy * $cy);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $oldColor = $input->getPixel($x, $y);
                $dx = $x - $cx;
                $dy = $y - $cy;
                $distance = sqrt($dx * $dx + $dy * $dy);
                $fade = 1 - ($this->strength * ($distance / $maxDistance));
                $fade = max(0, min(1, $fade));
                $newColor = new Color((int) ($oldColor->red * $fade), (int) ($oldColor->green * $fade), (int) ($oldColor->blue * $fade), $oldColor->alpha);
                $input->setPixel($x, $y, $newColor);
            }
        }

        return $input;
    }
}