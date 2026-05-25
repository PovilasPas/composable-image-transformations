<?php

namespace PovilasPas\ComposableImageTransformations\Images;

use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageFactoryInterface;

class ImageFactoryResolver
{
    public function __construct(
        /** @var ImageFactoryInterface[] */
        private array $factories = [],
    ) {
    }

    public function resolve(string $imageClass): ImageFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($imageClass)) {
                return $factory;
            }
        }

        throw new \Exception('No supported factory found for provided image class');
    }

    public static function getDefaultResolver(): ImageFactoryResolver
    {
        return new ImageFactoryResolver([
            new GdImageFactory(),
        ]);
    }
}