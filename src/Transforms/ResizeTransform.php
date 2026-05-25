<?php

namespace PovilasPas\ComposableImageTransformations\Transforms;

use PovilasPas\ComposableImageTransformations\Data\Color;
use PovilasPas\ComposableImageTransformations\Images\Abstraction\ImageInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformInterface;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\FactoryAwareTransformTrait;
use PovilasPas\ComposableImageTransformations\Transforms\Abstraction\TransformInterface;

class ResizeTransform implements TransformInterface, FactoryAwareTransformInterface
{
    use FactoryAwareTransformTrait;

    public function __construct(
        private int $newWidth,
        private int $newHeight,
    ) {
    }

    public function apply(ImageInterface $input): ImageInterface
    {
        $this->enforceFactoryResolverSet();

        $oldWidth = $input->width;
        $oldHeight = $input->height;

        $factory = $this->resolver->resolve($input::class);
        $output = $factory->createBlank($this->newWidth, $this->newHeight);

        $widthRatio = $oldWidth / $this->newWidth;
        $heightRatio = $oldHeight / $this->newHeight;

        $yMap = $this->computeCoordinateMap($oldHeight, $this->newHeight, $heightRatio);
        $xMap = $this->computeCoordinateMap($oldWidth, $this->newWidth, $widthRatio);

        for ($yDst = 0; $yDst < $this->newHeight; $yDst++) {
            $yEntry = $yMap[$yDst];

            for ($xDst = 0; $xDst < $this->newWidth; $xDst++) {
                $xEntry = $xMap[$xDst];

                $topLeftColor = $input->getPixel($xEntry['src0'], $yEntry['src0']);
                $topRightColor = $input->getPixel($xEntry['src1'], $yEntry['src0']);
                $bottomLeftColor = $input->getPixel($xEntry['src0'], $yEntry['src1']);
                $bottomRightColor = $input->getPixel($xEntry['src1'], $yEntry['src1']);

                $newRed = $this->interpolate($xEntry['weight'], $yEntry['weight'], $topLeftColor->red, $topRightColor->red, $bottomLeftColor->red, $bottomRightColor->red);
                $newGreen = $this->interpolate($xEntry['weight'], $yEntry['weight'], $topLeftColor->green, $topRightColor->green, $bottomLeftColor->green, $bottomRightColor->green);
                $newBlue = $this->interpolate($xEntry['weight'], $yEntry['weight'], $topLeftColor->blue, $topRightColor->blue, $bottomLeftColor->blue, $bottomRightColor->blue);
                $newAlpha = $this->interpolate($xEntry['weight'], $yEntry['weight'], $topLeftColor->alpha, $topRightColor->alpha, $bottomLeftColor->alpha, $bottomRightColor->alpha);

                $newColor = new Color($newRed, $newGreen, $newBlue, $newAlpha);

                $output->setPixel($xDst, $yDst, $newColor);
            }
        }

        return $output;
    }

    private function computeCoordinateMap(int $oldTo, int $newTo, float $ratio): array
    {
        $map = [];

        for ($dst = 0; $dst < $newTo; $dst++) {
            $src = ($dst + 0.5) * $ratio - 0.5;
            $src0 = (int) floor($src);
            $weight = $src - $src0;
            $src0 = max(0, min($oldTo - 1, $src0));
            $src1 = min($src0 + 1, $oldTo - 1);

            $map[$dst] = [
                'src0' => $src0,
                'src1' => $src1,
                'weight' => $weight,
            ];
        }

        return $map;
    } 

    private function interpolate(
        float $wx,
        float $wy,
        int $topLeft, 
        int $topRight, 
        int $bottomLeft, 
        int $bottomRight
    ): int {
        return (int) round(
            (1 - $wx) * (1 - $wy) * $topLeft 
            + $wx * (1 - $wy) * $topRight 
            + (1 - $wx) * $wy * $bottomLeft 
            + $wx * $wy * $bottomRight
        );
    }
}

