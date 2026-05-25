<?php

namespace PovilasPas\ComposableImageTransformations\Transforms\Abstraction;

use PovilasPas\ComposableImageTransformations\Images\ImageFactoryResolver;

interface FactoryAwareTransformInterface
{
    public function setFactoryResolver(ImageFactoryResolver $resolver): void;
}