<?php

namespace PovilasPas\ComposableImageTransformations\Transforms\Abstraction;

use PovilasPas\ComposableImageTransformations\Images\ImageFactoryResolver;

trait FactoryAwareTransformTrait
{
    private ImageFactoryResolver $resolver;

    public function setFactoryResolver(ImageFactoryResolver $resolver): void
    {
        $this->resolver = $resolver;
    }

    public function enforceFactoryResolverSet(): void
    {
        if (!isset($this->resolver)) {
            throw new \Exception('Factory resolver not set');
        }
    }
}