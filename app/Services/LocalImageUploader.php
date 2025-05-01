<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalImageUploader implements ImageUploader
{
    public function upload(UploadedFile $image, string $folder, string $name): string
    {
        if ($name) {
            $path = $image->storeAs($folder, $name, 'public');
        } else {
            $path = $image->store($folder, 'public');
        }

        return $path ? Storage::disk('public')->url($path) : '';
    }
}
