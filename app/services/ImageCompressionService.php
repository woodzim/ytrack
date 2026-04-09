<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Service for compressing image files.
 * 
 * This service handles compression of uploaded image files using Intervention Image library.
 * It applies quality reduction and optional dimension constraints to reduce file size
 * while maintaining acceptable visual quality.
 */
class ImageCompressionService
{
    /**
     * Default compression quality (0-100).
     * 75% provides good balance between size reduction and visual quality.
     * Note: Only applies to JPEG. PNG is lossless and cannot use quality reduction.
     */
    private const DEFAULT_QUALITY = 75;

    /**
     * Maximum width/height in pixels before resizing.
     * Prevents memory exhaustion on very large images.
     */
    private const MAX_DIMENSION = 1500;

    /**
     * Maximum file size (in bytes) to attempt compression.
     * Files larger than 3MB will skip compression to prevent memory exhaustion.
     * This is conservative for servers with 128MB PHP memory limit.
     */
    private const MAX_FILE_SIZE_FOR_COMPRESSION = 3145728; // 3MB

    /**
     * Maximum dimensions to check before loading image.
     * Images larger than this will skip compression to prevent memory exhaustion.
     */
    private const MAX_SAFE_DIMENSION = 8000; // 8000x8000 = 64MP (very conservative)

    /**
     * Minimum free memory (in bytes) required before compression.
     * Skip compression if available memory is less than 20MB.
     * Conservative for shared hosting environments.
     */
    private const MIN_FREE_MEMORY = 20971520; // 20MB

    /**
     * Image manager instance.
     */
    protected $imageManager;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Compress an uploaded image file.
     * 
     * For JPEG: Applies quality reduction (lossy compression).
     * For PNG: Optimizes via dimension reduction only (PNG is lossless by nature).
     * 
     * @param \Illuminate\Http\UploadedFile $uploadedFile The uploaded file
     * @param string $storagePath The storage path where file was saved
     * @param int $quality Compression quality (0-100), applies to JPEG only
     * @return string The path to the compressed file
     * @throws Exception If compression fails
     */
    public function compressImage($uploadedFile, string $storagePath, int $quality = self::DEFAULT_QUALITY): string
    {
        // Skip compression if disabled via config
        if (!config('app.image_compression_enabled')) {
            Log::info("Image compression skipped - disabled via config", ['path' => $storagePath]);
            return $storagePath;
        }

        try {
            $fullPath = Storage::disk('public')->path($storagePath);
            
            if (!file_exists($fullPath)) {
                throw new Exception("File not found: {$fullPath}");
            }

            // Get original file size
            $originalSize = filesize($fullPath);

            // Skip compression for very large files to prevent memory exhaustion
            if ($originalSize > self::MAX_FILE_SIZE_FOR_COMPRESSION) {
                Log::warning("Image compression skipped - file too large", [
                    'path' => $storagePath,
                    'size' => $originalSize,
                    'max_allowed' => self::MAX_FILE_SIZE_FOR_COMPRESSION,
                ]);
                return $storagePath;
            }

            // Check image dimensions BEFORE loading to prevent memory exhaustion
            // This is more accurate than file size check
            $imageInfo = @getimagesize($fullPath);
            if ($imageInfo !== false) {
                list($width, $height) = $imageInfo;
                
                // Estimate memory requirement: width × height × 4 bytes × 2 (for processing)
                $estimatedMemory = $width * $height * 4 * 2;
                
                if ($width > self::MAX_SAFE_DIMENSION || $height > self::MAX_SAFE_DIMENSION) {
                    Log::warning("Image compression skipped - dimensions too large", [
                        'path' => $storagePath,
                        'width' => $width,
                        'height' => $height,
                        'max_dimension' => self::MAX_SAFE_DIMENSION,
                    ]);
                    return $storagePath;
                }

                // Check if estimated memory exceeds 50% of available memory
                $memoryLimit = $this->getMemoryLimit();
                $memoryUsage = memory_get_usage(true);
                $availableMemory = $memoryLimit - $memoryUsage;

                if ($estimatedMemory > ($availableMemory * 0.5)) {
                    Log::warning("Image compression skipped - estimated memory too high", [
                        'path' => $storagePath,
                        'width' => $width,
                        'height' => $height,
                        'estimated_memory' => $estimatedMemory,
                        'available_memory' => $availableMemory,
                    ]);
                    return $storagePath;
                }
            }

            // Check available memory before processing
            $memoryLimit = $this->getMemoryLimit();
            $memoryUsage = memory_get_usage(true);
            $availableMemory = $memoryLimit - $memoryUsage;

            if ($availableMemory < self::MIN_FREE_MEMORY) {
                Log::warning("Image compression skipped - insufficient memory", [
                    'path' => $storagePath,
                    'memory_limit' => $memoryLimit,
                    'memory_usage' => $memoryUsage,
                    'available' => $availableMemory,
                    'required' => self::MIN_FREE_MEMORY,
                ]);
                return $storagePath;
            }

            // Set custom error handler to catch memory errors
            $previousErrorHandler = set_error_handler(function ($errno, $errstr) {
                if (str_contains($errstr, 'memory') || str_contains($errstr, 'Allowed memory size')) {
                    throw new Exception("Memory exhausted during image processing: {$errstr}");
                }
                return false; // Let PHP handle other errors normally
            });

            try {
                // Load image using Intervention Image
                $image = $this->imageManager->read($fullPath);

                // Get original dimensions
                $width = $image->width();
                $height = $image->height();

                // Resize if dimensions exceed maximum (reduces memory usage)
                if ($width > self::MAX_DIMENSION || $height > self::MAX_DIMENSION) {
                    $image->scale(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION);
                    Log::info("Image resized from {$width}x{$height} to fit " . self::MAX_DIMENSION . "px");
                }

                // Determine format and apply compression
                $extension = strtolower($uploadedFile->getClientOriginalExtension());
                
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $image->toJpeg($quality);
                        break;
                    case 'png':
                        // Intervention Image v3: toPng() signature changed
                        // First param is now bool $interlaced, not compression level
                        // PNG is lossless, so we can't reduce quality like JPEG
                        // Just save as PNG with default settings (already optimized)
                        $image->toPng(interlaced: false);
                        break;
                    default:
                        // Fallback to jpeg for unknown formats
                        $image->toJpeg($quality);
                }

                // Save compressed image (overwrite original)
                $image->save($fullPath);

                // Get compressed file size for logging
                $compressedSize = filesize($fullPath);
                $compressionRatio = round((1 - ($compressedSize / $originalSize)) * 100, 2);

                Log::info("Image compressed successfully", [
                    'path' => $storagePath,
                    'original_size' => $originalSize,
                    'compressed_size' => $compressedSize,
                    'compression_ratio' => "{$compressionRatio}%",
                    'quality' => $quality,
                ]);

                return $storagePath;

            } finally {
                // Restore previous error handler
                if ($previousErrorHandler) {
                    set_error_handler($previousErrorHandler);
                } else {
                    restore_error_handler();
                }
                
                // Force garbage collection to free memory
                if (isset($image)) {
                    unset($image);
                }
                gc_collect_cycles();
            }

        } catch (\Throwable $e) {
            // Catch all errors including memory exhaustion
            $errorType = str_contains($e->getMessage(), 'memory') ? 'memory_exhaustion' : 'general_error';
            
            Log::error("Image compression failed - {$errorType}", [
                'path' => $storagePath ?? 'unknown',
                'error' => $e->getMessage(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
            ]);
            
            // Return original path on failure (graceful degradation)
            return $storagePath;
        }
    }

    /**
     * Get PHP memory limit in bytes.
     * 
     * @return int Memory limit in bytes
     */
    private function getMemoryLimit(): int
    {
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit == -1) {
            return PHP_INT_MAX; // No limit
        }
        
        $unit = strtoupper(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'G':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'M':
                $value *= 1024 * 1024;
                break;
            case 'K':
                $value *= 1024;
                break;
        }
        
        return $value;
    }

    /**
     * Check if a file is an image that can be compressed.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function isCompressibleImage($file): bool
    {
        if (!$file || !$file->isValid()) {
            return false;
        }

        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];

        return in_array($mimeType, $allowedMimes);
    }
}
