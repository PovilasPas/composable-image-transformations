<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\ImageCopyData;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class CropTransform implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    public function __construct(
        private int $x,
        private int $y,
        private int $newWidth,
        private int $newHeight,
    ) {
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        $this->enforceFactoryResolverSet();

        $factory = $this->resolver->resolve($input::class);

        $oldWidth = $input->width;
        $oldHeight = $input->height;

        if (!$this->isInsideImage($oldWidth, $oldHeight)) {
            throw new \Exception('Specified bounding box to be cropped is outside of the bounds of the image');
        }

        $output = $factory->createBlank($this->newWidth, $this->newHeight);
        $copyData = new ImageCopyData($this->x, $this->y, 0, 0, $this->newWidth, $this->newHeight);
        $output->copy($input, $copyData);

        return $output;
    }

    private function isInsideImage(int $oldWidth, int $oldHeight): bool
    {
        return $this->x >= 0 
            && $this->y >= 0
            && $this->x + $this->newWidth <= $oldWidth
            && $this->y + $this->newHeight <= $oldHeight;
    }
}