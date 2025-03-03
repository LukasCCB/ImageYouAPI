<?php

namespace App\Http\Controllers;

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
            $hash = Str::uuid()->toString();

            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,avif|max:5120',
                'reference' => 'required|string|min:5|max:100|regex:/^[a-zA-Z0-9_-]+$/',
                'format' => 'required|in:jpg,png,webp,gif,avif',
            ]);

            $imagePath = Helper::processImage(
                $request->file('image'),
                [
                    'format' => $request->input('format', 'webp'),
                    'reference' => $request->reference
                ],
                $hash
            );

            $image = Image::create([
                'hash' => $hash,
                'path' => $imagePath,
                'original_name' => $request->file('image')->getClientOriginalName()
            ]);

            return response()->json([
                'id' => $image->hash,
                'url' => Storage::disk('public')->url($imagePath)
            ], 201);
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
