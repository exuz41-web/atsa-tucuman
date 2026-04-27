<?php

namespace App\Support;

class ImageWatermarkSupport
{
    public static function applyToPublicImage(?string $relativePath, string $logoPath = 'images/logo-atsa.png'): void
    {
        if (blank($relativePath)) {
            return;
        }

        $imagePath = storage_path('app/public/' . ltrim($relativePath, '/'));
        $fullLogoPath = public_path($logoPath);

        if (! is_file($imagePath) || ! is_file($fullLogoPath)) {
            return;
        }

        $imageData = @file_get_contents($imagePath);
        $logoData = @file_get_contents($fullLogoPath);

        if ($imageData === false || $logoData === false) {
            return;
        }

        $baseImage = @imagecreatefromstring($imageData);
        $logoImage = @imagecreatefromstring($logoData);

        if (! $baseImage || ! $logoImage) {
            return;
        }

        imagealphablending($baseImage, true);
        imagesavealpha($baseImage, true);
        imagealphablending($logoImage, true);
        imagesavealpha($logoImage, true);

        $baseWidth = imagesx($baseImage);
        $baseHeight = imagesy($baseImage);
        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesy($logoImage);

        if (! $baseWidth || ! $baseHeight || ! $logoWidth || ! $logoHeight) {
            imagedestroy($baseImage);
            imagedestroy($logoImage);
            return;
        }

        $targetWidth = (int) max(110, min(($baseWidth * 0.17), 240));
        $targetHeight = (int) round(($targetWidth / $logoWidth) * $logoHeight);

        $resizedLogo = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($resizedLogo, false);
        imagesavealpha($resizedLogo, true);
        $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
        imagefilledrectangle($resizedLogo, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $resizedLogo,
            $logoImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $logoWidth,
            $logoHeight
        );

        self::applyOpacity($resizedLogo, 72);

        $margin = (int) max(18, round($baseWidth * 0.02));
        $destX = max(0, $baseWidth - $targetWidth - $margin);
        $destY = max(0, $baseHeight - $targetHeight - $margin);

        imagecopy($baseImage, $resizedLogo, $destX, $destY, 0, 0, $targetWidth, $targetHeight);

        self::saveImage($baseImage, $imagePath);

        imagedestroy($baseImage);
        imagedestroy($logoImage);
        imagedestroy($resizedLogo);
    }

    protected static function saveImage(\GdImage $image, string $path): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        match ($extension) {
            'jpg', 'jpeg' => imagejpeg($image, $path, 90),
            'png' => imagepng($image, $path, 6),
            'webp' => imagewebp($image, $path, 90),
            default => imagejpeg($image, $path, 90),
        };
    }

    protected static function applyOpacity(\GdImage $image, int $alphaIncrease = 72): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgba = imagecolorat($image, $x, $y);

                $alpha = ($rgba >> 24) & 0x7F;
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                $newAlpha = min(127, $alpha + $alphaIncrease);
                $newColor = imagecolorallocatealpha($image, $red, $green, $blue, $newAlpha);
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
    }
}
