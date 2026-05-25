<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;
use Random\Engine\Mt19937;
use Random\Randomizer;

class RandomCropTransform implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    private Randomizer $randomizer;

    public function __construct(
        private int $newWidth,
        private int $newHeight,
    ) {
        $this->randomizer = new Randomizer(new Mt19937());
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        $this->enforceFactoryResolverSet();

        $oldWidth = $input->width;
        $oldHeight = $input->height;

        if ($this->newWidth > $oldWidth || $this->newHeight > $oldHeight) {
            throw new \Exception('Specified bigger dimensions than the dimensions of the original image');
        }

        $factory = $this->resolver->resolve($input::class);
        $output = $factory->createBlank($this->newWidth, $this->newHeight);

        $x = $this->randomizer->getInt(0, $oldWidth - $this->newWidth);
        $y = $this->randomizer->getInt(0, $oldHeight - $this->newHeight);

        $copyData = new ImageCopyData($x, $y, 0, 0, $this->newWidth, $this->newHeight);
        $output->copy($input, $copyData);

        return $output;
    }
}