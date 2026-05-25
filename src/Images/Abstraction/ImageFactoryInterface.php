<?php

namespace PovilasPas\ComposableImageTransformations\Images\Abstraction;

interface ImageFactoryInterface {
    public function supports(string $imageClass): bool;
    public function createBlank(int $width, int $height): ImageInterface;
    public function createFromFile(string $filePath): ImageInterface;
    public function createFromBytes(string $bytes): ImageInterface;
}