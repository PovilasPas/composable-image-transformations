<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class PosterizeTransform implements TransformInterface
{
    private const int MIN_LEVELS = 2;
    private const int MAX_LEVELS = 256;

    public function __construct(
        private int $levels
    ) {
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        if ($this->levels < self::MIN_LEVELS || $this->levels > self::MAX_LEVELS) {
            throw new \Exception('number of levels has to be in range from 2 (inclusive) to 256 (inclusive)');
        }

        $buckets = $this->levels - 1;
        $step = (int) ((self::MAX_LEVELS - 1) / $buckets);

        for ($y = 0; $y < $input->height; $y++) {
            for ($x = 0; $x < $input->width; $x++) {
                $oldColor = $input->getPixel($x, $y);

                $newColor = new Color(
                    min(255, (int) round($oldColor->red / $step) * $step),
                    min(255, (int) round($oldColor->green / $step) * $step),
                    min(255, (int) round($oldColor->blue / $step) * $step),
                    $oldColor->alpha,
                );

                $input->setPixel($x, $y, $newColor);
            }
        }

        return $input;
    }
}