<?php

namespace App\Helper;

use Exception;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class Helper
{
    /**
     * @throws Exception
     */
    public static function processImage(UploadedFile $image, array $config, string $hash): string
    {
        try {
            $format = $config['format'] ?? 'webp';
            $name = $hash;
            $reference = preg_replace('/[^a-zA-Z0-9_-]/', '_', $config['reference']);

            $path = "images/{$reference}/{$name}.{$format}";

            $directory = "images/{$reference}";
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $storedPath = Storage::disk('public')->putFileAs(
                $directory,
                $image,
                "{$name}.{$format}"
            );

            try {
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize(storage_path("app/public/{$storedPath}"));
            } catch (Exception $e) {
                Log::warning('Image optimization failed: ' . $e->getMessage());
            }

            return $storedPath;
        } catch (Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
