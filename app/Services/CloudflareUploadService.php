<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudflareUploadService
{
    private static string $apiToken;
    private static string $accountId;
    private static string $customerSubdomain;

    public function __construct()
    {
        self::$apiToken = config("custom.cloudflare_api_token");
        self::$accountId = config("custom.cloudflare_account_id");
        self::$customerSubdomain = config(
            "custom.cloudflare_customer_subdomain",
        );
    }

    /**
     * Upload file to appropriate Cloudflare service based on file type
     */
    public static function uploadFile(
        UploadedFile $file,
        string $conversationId = null,
    ): array {
        try {
            $mimeType = $file->getMimeType();
            $originalName = $file->getClientOriginalName();

            // Generate unique ID for the file
            $fileId = self::generateUniqueId();

            if (str_starts_with($mimeType, "video/")) {
                return self::uploadVideoToStream(
                    $file,
                    $fileId,
                    $conversationId,
                );
            } elseif (str_starts_with($mimeType, "image/")) {
                return self::uploadImageToR2($file, $fileId);
            } else {
                // For other file types, upload to R2
                return self::uploadFileToR2($file, $fileId);
            }
        } catch (\Exception $e) {
            Log::error("Cloudflare upload error: " . $e->getMessage());
            return [
                "success" => false,
                "error" => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload video to Cloudflare Stream
     */
    private static function uploadVideoToStream(
        UploadedFile $file,
        string $fileId,
        string $conversationId = null,
    ): array {
        try {
            $filename =
                "paymefans-{$conversationId}-{$fileId}" .
                pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            // Create TUS upload session
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . self::$apiToken,
                "Tus-Resumable" => "1.0.0",
                "Upload-Length" => $file->getSize(),
                "Upload-Metadata" => "name " . base64_encode($filename),
            ])->post(
                "https://api.cloudflare.com/client/v4/accounts/" .
                    self::$accountId .
                    "/stream",
            );

            if (!$response->successful()) {
                throw new \Exception(
                    "Failed to create TUS upload session: " . $response->body(),
                );
            }

            $uploadUrl = $response->header("Location");
            if (!$uploadUrl) {
                throw new \Exception("No upload URL returned from Cloudflare");
            }

            // Upload the file content
            $uploadResponse = Http::withHeaders([
                "Tus-Resumable" => "1.0.0",
                "Upload-Offset" => "0",
                "Content-Type" => "application/offset+octet-stream",
            ])
                ->withBody(
                    file_get_contents($file->getPathname()),
                    "application/offset+octet-stream",
                )
                ->patch($uploadUrl);

            if (!$uploadResponse->successful()) {
                throw new \Exception(
                    "Failed to upload video: " . $uploadResponse->body(),
                );
            }

            // Extract media ID from upload URL
            $mediaId = basename(parse_url($uploadUrl, PHP_URL_PATH));

            return [
                "success" => true,
                "url" =>
                    self::$customerSubdomain .
                    "/{$mediaId}/manifest/video.m3u8",
                "mediaId" => $mediaId,
                "name" => $filename,
                "size" => $file->getSize(),
                "type" => $file->getMimeType(),
                "extension" => pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_EXTENSION,
                ),
                "poster" =>
                    self::$customerSubdomain .
                    "/{$mediaId}/thumbnails/thumbnail.gif?time=1s&height=400&duration=4s",
            ];
        } catch (\Exception $e) {
            Log::error(
                "Video upload to Cloudflare Stream failed: " . $e->getMessage(),
            );
            // Fallback to local storage
            return self::fallbackUpload($file);
        }
    }

    /**
     * Upload image to Cloudflare R2
     */
    private static function uploadImageToR2(
        UploadedFile $file,
        string $fileId,
    ): array {
        try {
            $extension = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION,
            );
            $filename = "attachments/{$fileId}.{$extension}";

            // For now, use local storage as fallback
            // In production, this would upload to R2
            return self::fallbackUpload($file);
        } catch (\Exception $e) {
            Log::error("Image upload to R2 failed: " . $e->getMessage());
            return self::fallbackUpload($file);
        }
    }

    /**
     * Upload file to Cloudflare R2
     */
    private static function uploadFileToR2(
        UploadedFile $file,
        string $fileId,
    ): array {
        try {
            $extension = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION,
            );
            $filename = "attachments/{$fileId}.{$extension}";

            // For now, use local storage as fallback
            // In production, this would upload to R2
            return self::fallbackUpload($file);
        } catch (\Exception $e) {
            Log::error("File upload to R2 failed: " . $e->getMessage());
            return self::fallbackUpload($file);
        }
    }

    /**
     * Fallback to local storage upload
     */
    private static function fallbackUpload(UploadedFile $file): array
    {
        $fileName = time() . "_" . $file->getClientOriginalName();
        $filePath = $file->storeAs("chat-attachments", $fileName, "public");

        return [
            "success" => true,
            "url" => Storage::url($filePath),
            "name" => $file->getClientOriginalName(),
            "size" => $file->getSize(),
            "type" => $file->getMimeType(),
            "extension" => pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION,
            ),
        ];
    }

    /**
     * Generate unique ID for files
     */
    private static function generateUniqueId(): string
    {
        return uniqid("file_", true) . "_" . time();
    }

    /**
     * Delete file from Cloudflare Stream
     */
    public static function deleteStreamVideo(string $mediaId): bool
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . self::$apiToken,
            ])->delete(
                "https://api.cloudflare.com/client/v4/accounts/" .
                    self::$accountId .
                    "/stream/{$mediaId}",
            );

            return $response->successful();
        } catch (\Exception $e) {
            Log::error(
                "Failed to delete video from Cloudflare Stream: " .
                    $e->getMessage(),
            );
            return false;
        }
    }

    /**
     * Delete file from Cloudflare R2
     */
    public static function deleteR2File(string $filename): bool
    {
        try {
            // Implementation would depend on R2 configuration
            // For now, return true as placeholder
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete file from R2: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get video thumbnail from Cloudflare Stream
     */
    public static function getVideoThumbnail(
        string $mediaId,
        array $options = [],
    ): string {
        $time = $options["time"] ?? "1s";
        $height = $options["height"] ?? "400";
        $duration = $options["duration"] ?? "4s";

        return self::$customerSubdomain .
            "/{$mediaId}/thumbnails/thumbnail.gif?time={$time}&height={$height}&duration={$duration}";
    }

    /**
     * Check if Cloudflare services are configured
     */
    public static function isConfigured(): bool
    {
        return !empty(self::$apiToken) &&
            !empty(self::$accountId) &&
            !empty(self::$customerSubdomain);
    }

    /**
     * Process attachment data to match server format
     */
    public static function processAttachment(array $uploadResult): array
    {
        $attachment = [
            "url" => $uploadResult["url"],
            "name" => $uploadResult["name"],
            "size" => $uploadResult["size"],
            "type" => $uploadResult["type"],
            "extension" => $uploadResult["extension"],
        ];

        // Add video-specific properties
        if (isset($uploadResult["mediaId"])) {
            $attachment["id"] = $uploadResult["mediaId"];
            $attachment["poster"] = $uploadResult["poster"];
        }

        return $attachment;
    }
}
