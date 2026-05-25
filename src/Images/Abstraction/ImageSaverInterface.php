<?php

namespace PovilasPas\ComposableImageTransformations\Images\Abstraction;

/**
 * @template T
 */
interface ImageSaverInterface
{
    /**
     * @param T $resource
     * @return void
     */
    public function save(mixed $resource): void;
}