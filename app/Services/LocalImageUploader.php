<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalImageUploader implements ImageUploader
{
    public function upload(UploadedFile $image, string $folder, string $name): string
    {
        $path = $image->storeAs($folder, $name, 'public');
        return $path ? Storage::disk('public')->url($path) : '';
    }
}
