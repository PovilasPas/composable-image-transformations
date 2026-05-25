<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class HorizontalFlip implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    public function apply(ImageInterface $input): ImageInterface
    {
        $this->enforceFactoryResolverSet();

        $width = $input->width;
        $height = $input->height;

        $factory = $this->resolver->resolve($input::class);
        $output = $factory->createBlank($width, $height);

        for ($x = 0; $x < $width; $x++) {
            $copyData = new ImageCopyData($x, 0, $width - $x - 1, 0, 1, $height);
            $output->copy($input, $copyData);
        }

        return $output;
    }
}