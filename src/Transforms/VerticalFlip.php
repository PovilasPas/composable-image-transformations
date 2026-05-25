<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class VerticalFlip implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    public function apply(ImageInterface $input): ImageInterface
    {
        $this->enforceFactoryResolverSet();

        $width = $input->width;
        $height = $input->height;

        $factory = $this->resolver->resolve($input::class);
        $output = $factory->createBlank($width, $height);

        for ($y = 0; $y < $height; $y++) {
            $copyData = new ImageCopyData(0, $y, 0, $height - $y - 1, $width, 1);
            $output->copy($input, $copyData);
        }

        return $output;
    }
}