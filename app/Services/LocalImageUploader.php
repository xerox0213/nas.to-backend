<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class LocalImageUploader implements ImageUploader
{
    public function upload(UploadedFile $image, string $folder, string $name): string
    {
        $path = $image->storeAs('covers', $name);
        return $path ? asset($path) : '';
    }
}
