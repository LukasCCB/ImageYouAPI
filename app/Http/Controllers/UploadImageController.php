<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessImageJob;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|array', // Permite múltiplos arquivos
                'image.*' => 'required|image|mimes:jpg,jpeg,png,webp,gif,avif|max:5120',
                'reference' => 'required|string|min:5|max:100|regex:/^[a-zA-Z0-9_-]+$/',
                'format' => 'required|in:jpg,png,webp,gif,avif',
            ]);

            $reference = $request->reference;
            $format = $request->format;
            $uploadedImages = [];

            foreach ($request->file('image') as $image) {
                $hash = Str::uuid()->toString();
                $tempPath = "temp/{$hash}.{$image->getClientOriginalExtension()}";

                // Salva a imagem temporariamente antes de processar
                Storage::disk('local')->put($tempPath, file_get_contents($image));

                // Envia para o Job para processamento assíncrono
                ProcessImageJob::dispatch($tempPath, $reference, $format, $hash);

                $uploadedImages[] = [
                    'id' => $hash,
                    'url' => Storage::disk('public')->url("images/{$reference}/{$hash}.{$format}") // URL final esperada
                ];
            }

            return response()->json([
                'message' => 'Upload iniciado, imagens serão processadas',
                'images' => $uploadedImages
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error uploading image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($hash)
    {
        $image = Image::where('hash', $hash)->firstOrFail();
        return Storage::disk('public')->response($image->path);
    }

    public function destroy($hash)
    {
        try {
            $image = Image::where('hash', $hash)->firstOrFail();

            Storage::disk('public')->delete($image->path);
            $image->delete();

            return response()->json([
                'hash' => $hash,
                'message' => 'Image deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
