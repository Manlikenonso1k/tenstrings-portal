<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Server-side downscaling with GD. The project has neither Imagick nor
 * intervention/image, and Filament's imageResize* options only resize in the
 * browser — which a mobile upload can bypass.
 */
class ImageResizer
{
    public const MAX_WIDTH = 1600;

    public const THUMB_WIDTH = 600;

    /**
     * Downscale the stored file in place. Returns the path it now lives at
     * (unchanged), or null when the file could not be read as an image.
     */
    public static function downscale(string $path, string $disk = 'public', int $maxWidth = self::MAX_WIDTH): ?string
    {
        $image = self::read($path, $disk);

        if ($image === null) {
            return null;
        }

        [$resource, $extension] = $image;
        $resized = self::scaleToWidth($resource, $maxWidth);

        if ($resized !== null) {
            Storage::disk($disk)->put($path, self::encode($resized, $extension));
            imagedestroy($resized);
        }

        imagedestroy($resource);

        return $path;
    }

    /**
     * Write a thumbnail next to the original and return its path.
     */
    public static function thumbnail(string $path, string $disk = 'public', int $width = self::THUMB_WIDTH): ?string
    {
        $image = self::read($path, $disk);

        if ($image === null) {
            return null;
        }

        [$resource, $extension] = $image;
        $resized = self::scaleToWidth($resource, $width) ?? $resource;

        $directory = trim(dirname($path), '.\/');
        $thumbPath = ($directory !== '' ? $directory . '/' : '')
            . 'thumbs/' . Str::beforeLast(basename($path), '.') . '-thumb.' . $extension;

        Storage::disk($disk)->put($thumbPath, self::encode($resized, $extension));

        if ($resized !== $resource) {
            imagedestroy($resized);
        }

        imagedestroy($resource);

        return $thumbPath;
    }

    /**
     * @return array{0: \GdImage, 1: string}|null
     */
    protected static function read(string $path, string $disk): ?array
    {
        if (! extension_loaded('gd') || ! Storage::disk($disk)->exists($path)) {
            return null;
        }

        $contents = Storage::disk($disk)->get($path);

        if ($contents === null) {
            return null;
        }

        $resource = @imagecreatefromstring($contents);

        if ($resource === false) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $extension = 'jpg';
        }

        return [$resource, $extension];
    }

    protected static function scaleToWidth(\GdImage $resource, int $width): ?\GdImage
    {
        $originalWidth = imagesx($resource);
        $originalHeight = imagesy($resource);

        if ($originalWidth <= $width) {
            return null;
        }

        $height = (int) round($originalHeight * ($width / $originalWidth));
        $scaled = imagescale($resource, $width, $height);

        return $scaled === false ? null : $scaled;
    }

    protected static function encode(\GdImage $resource, string $extension): string
    {
        ob_start();

        match ($extension) {
            'png' => imagepng($resource, null, 8),
            'webp' => imagewebp($resource, null, 82),
            default => imagejpeg($resource, null, 82),
        };

        return (string) ob_get_clean();
    }
}
