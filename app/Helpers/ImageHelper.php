<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageHelper
{
    public static function downloadImage($url, $folder = 'posters')
    {
        try {
            $contents = file_get_contents($url);
            $filename = Str::random(40) . '.jpg';
            $path = "$folder/$filename";

            Storage::disk('public')->put($path, $contents);

            return $path;
        } catch (\Exception $e) {
            Log::error("Error in ImageHelper@downloadImage: " . $e->getMessage());
            return null;
        }
    }
}
