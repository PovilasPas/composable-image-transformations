<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class CompositeTransform implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    public function __construct(
        /** @var TransformInterface[] */
        private array $innerTransforms,
    ) {
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        $isFactoryResolverSet = isset($this->resolver);

        foreach ($this->innerTransforms as $transform) {
            if ($transform instanceof FactoryAwareTransformInterface && $isFactoryResolverSet) {
                $transform->setFactoryResolver($this->resolver);
            }

            $input = $transform->apply($input);
        }

        return $input;
    }
}