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
            $name = $hash; // Já é um UUID, não precisa de mais modificações
            $reference = preg_replace('/[^a-zA-Z0-9_-]/', '_', $config['reference']);

            $path = "images/{$reference}/{$name}.{$format}";

            // Verifica se o diretório existe, se não, cria
            $directory = "images/{$reference}";
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Armazena a imagem
            $storedPath = Storage::disk('public')->putFileAs(
                $directory,
                $image,
                "{$name}.{$format}"
            );

            // Otimiza a imagem
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
