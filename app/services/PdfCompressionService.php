<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

/**
 * Service for compressing PDF files using ILovePDF REST API.
 * 
 * This service handles compression of uploaded PDF files using the ILovePDF cloud service
 * via direct REST API calls following the ILoveAPI workflow:
 * 0. Authentication - Get JWT token from auth server
 * 1. Start Task - Get server and task ID
 * 2. Upload Files - Upload PDF to server
 * 3. Process - Execute compression
 * 4. Download - Get compressed result
 * 
 * Requires ILovePDF API credentials (public key) to be configured.
 * Visit https://developer.ilovepdf.com/user/projects to get your API keys.
 * API Documentation: https://www.iloveapi.com/docs/api-reference
 */
class PdfCompressionService
{
    /**
     * Maximum file size (in bytes) to attempt compression.
     * Files larger than 100MB will skip compression (ILovePDF limit).
     */
    private const MAX_FILE_SIZE_FOR_COMPRESSION = 104857600; // 100MB

    /**
     * Minimum file size (in bytes) to process.
     * Files smaller than 2MB might not need compression.
     */
    private const MIN_FILE_SIZE_FOR_COMPRESSION = 2097152; // 2MB

    /**
     * Compress a PDF file using ILovePDF REST API.
     * 
     * @param \Illuminate\Http\UploadedFile $uploadedFile The uploaded file
     * @param string $storagePath The storage path where file was saved
     * @return string The path to the compressed file (original if compression skipped/failed)
     */
    public function compressPdf($uploadedFile, string $storagePath): string
    {
        $tempDownload = null;

        // Skip compression if disabled via config
        if (!config('app.pdf_compression_enabled', true)) {
            Log::channel('compression')->info("PDF compression skipped - disabled via config", ['path' => $storagePath]);
            return $storagePath;
        }

        try {
            // Check if API key is configured
            $publicKey = config('services.ilovepdf.public_key');

            if (empty($publicKey)) {
                Log::channel('compression')->warning("PDF compression skipped - ILovePDF API key not configured", [
                    'path' => $storagePath,
                ]);
                return $storagePath;
            }

            $fullPath = Storage::disk('public')->path($storagePath);

            if (!file_exists($fullPath)) {
                throw new Exception("File not found: {$fullPath}");
            }

            // Get original file size
            $originalSize = filesize($fullPath);

            // Skip compression for very large files (ILovePDF has 100MB limit)
            if ($originalSize > self::MAX_FILE_SIZE_FOR_COMPRESSION) {
                Log::channel('compression')->warning("PDF compression skipped - file too large", [
                    'path' => $storagePath,
                    'size' => $originalSize,
                    'max_allowed' => self::MAX_FILE_SIZE_FOR_COMPRESSION,
                ]);
                return $storagePath;
            }

            // Skip compression for very small files (minimal benefit)
            if ($originalSize < self::MIN_FILE_SIZE_FOR_COMPRESSION) {
                Log::channel('compression')->warning("PDF compression skipped - file too small", [
                    'path' => $storagePath,
                    'size' => $originalSize,
                    'min_recommended' => self::MIN_FILE_SIZE_FOR_COMPRESSION,
                ]);
                return $storagePath;
            }

            Log::channel('compression')->debug("Starting PDF compression with ILovePDF API", [
                'input' => $storagePath,
                'original_size' => $originalSize,
            ]);

            // Step 0: Authentication - Get JWT token
            $authResponse = Http::timeout(30)
                ->post('https://api.ilovepdf.com/v1/auth', [
                    'public_key' => $publicKey,
                ]);

            if (!$authResponse->successful()) {
                throw new Exception("Failed to authenticate: " . $authResponse->body());
            }

            $authData = $authResponse->json();
            $token = $authData['token'] ?? null;

            if (!$token) {
                throw new Exception("Invalid response from authentication - no token received");
            }

            Log::channel('compression')->debug("Authentication successful");

            // Step 1: Start Task
            $startResponse = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get('https://api.ilovepdf.com/v1/start/compress');

            if (!$startResponse->successful()) {
                throw new Exception("Failed to start task: " . $startResponse->body());
            }

            $startData = $startResponse->json();
            $server = $startData['server'] ?? null;
            $taskId = $startData['task'] ?? null;

            if (!$server || !$taskId) {
                throw new Exception("Invalid response from start task");
            }

            Log::channel('compression')->debug("Task started", [
                'server' => $server,
                'task_id' => $taskId,
            ]);

            // Step 2: Upload File
            $uploadResponse = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->attach('file', file_get_contents($fullPath), basename($fullPath))
                ->post("https://{$server}/v1/upload", [
                    'task' => $taskId,
                ]);

            if (!$uploadResponse->successful()) {
                throw new Exception("Failed to upload file: " . $uploadResponse->body());
            }

            $uploadData = $uploadResponse->json();
            $serverFilename = $uploadData['server_filename'] ?? null;

            if (!$serverFilename) {
                throw new Exception("Invalid response from upload");
            }

            Log::channel('compression')->debug("File uploaded", [
                'server_filename' => $serverFilename,
            ]);

            // Step 3: Process (Compress)
            $processResponse = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->post("https://{$server}/v1/process", [
                    'task' => $taskId,
                    'tool' => 'compress',
                    'files' => [
                        [
                            'server_filename' => $serverFilename,
                            'filename' => basename($fullPath),
                        ]
                    ],
                    'compression_level' => 'recommended', // extreme, recommended, low
                ]);

            if (!$processResponse->successful()) {
                throw new Exception("Failed to process file: " . $processResponse->body());
            }

            $processData = $processResponse->json();
            $status = $processData['status'] ?? null;

            if ($status !== 'TaskSuccess') {
                throw new Exception("Processing failed with status: " . $status);
            }

            Log::channel('compression')->debug("File processed", [
                'status' => $status,
                'output_filesize' => $processData['output_filesize'] ?? 0,
            ]);

            // Step 4: Download
            $downloadResponse = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("https://{$server}/v1/download/{$taskId}");

            if (!$downloadResponse->successful()) {
                throw new Exception("Failed to download file: " . $downloadResponse->status());
            }

            // Create temporary path for download
            $tempDownload = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

            // Save downloaded content
            file_put_contents($tempDownload, $downloadResponse->body());

            // Validate that output was created
            if (!file_exists($tempDownload)) {
                throw new Exception("Failed to save downloaded file");
            }

            // Validate PDF content
            if (!$this->isValidPdfWithContent($tempDownload)) {
                throw new Exception("Downloaded file is invalid or empty");
            }

            $compressedSize = filesize($tempDownload);

            // Only use compressed file if it's actually smaller
            if ($compressedSize < $originalSize) {
                // Replace original with compressed version
                if (!copy($tempDownload, $fullPath)) {
                    throw new Exception("Failed to copy compressed file to original location");
                }

                $compressionRatio = round((1 - ($compressedSize / $originalSize)) * 100, 2);

                Log::channel('compression')->info("PDF compressed successfully", [
                    'path' => $storagePath,
                    'original_size' => $originalSize,
                    'compressed_size' => $compressedSize,
                    'compression_ratio' => "{$compressionRatio}%",
                    'service' => 'ILovePDF API',
                ]);
            } else {
                Log::channel('compression')->info("PDF compression skipped - compressed file not smaller", [
                    'path' => $storagePath,
                    'original_size' => $originalSize,
                    'compressed_size' => $compressedSize,
                ]);
            }

            return $storagePath;
        } catch (\Throwable $e) {
            // Log error with context for debugging
            Log::channel('compression')->error("PDF compression failed", [
                'path' => $storagePath ?? 'unknown',
                'error' => $e->getMessage(),
                'service' => 'ILovePDF API',
                'trace' => $e->getTraceAsString(),
            ]);

            // Return original path on failure (graceful degradation)
            return $storagePath;
        } finally {
            // Always clean up temporary file
            if ($tempDownload && file_exists($tempDownload)) {
                @unlink($tempDownload);
            }
        }
    }

    /**
     * Check if a file is a PDF that can be compressed.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function isCompressiblePdf($file): bool
    {
        if (!$file || !$file->isValid()) {
            return false;
        }

        $mimeType = $file->getMimeType();
        return $mimeType === 'application/pdf';
    }

    /**
     * Validate that a PDF file is valid and has actual content.
     * 
     * This is a basic check to ensure the compressed PDF isn't corrupted or empty.
     * We check for PDF header and reasonable file size.
     * 
     * @param string $filePath Full path to PDF file
     * @return bool True if PDF appears valid with content
     */
    private function isValidPdfWithContent(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $fileSize = filesize($filePath);

        // PDF should be at least 1KB to have any meaningful content
        if ($fileSize < 1024) {
            Log::channel('compression')->warning("PDF file too small to have content", [
                'path' => $filePath,
                'size' => $fileSize,
            ]);
            return false;
        }

        // Check PDF header (should start with %PDF-)
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return false;
        }

        $header = fread($handle, 5);
        fclose($handle);

        if ($header !== '%PDF-') {
            Log::channel('compression')->warning("File does not have valid PDF header", [
                'path' => $filePath,
                'header' => bin2hex($header),
            ]);
            return false;
        }

        return true;
    }
}
