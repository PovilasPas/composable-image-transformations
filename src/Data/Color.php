<?php

namespace PovilasPas\ComposableImageTransformations\Data;

readonly class Color
{
    public function __construct(
        public int $red,
        public int $green,
        public int $blue,
        public int $alpha,
    ) {
    }
}