<?php

namespace PovilasPas\ComposableImageTransformations\Transforms\Abstraction;

use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;


interface TransformInterface
{
    public function apply(ImageInterface $input): ImageInterface;
}