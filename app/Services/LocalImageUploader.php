<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalImageUploader implements ImageUploader
{
    public function upload(UploadedFile $image, string $folder): string
    {
        $path = $image->store($folder, 'public');
        return $path ? Storage::disk('public')->url($path) : '';
    }

    public function delete(string $url): bool
    {
        $base = Storage::disk('public')->url('');
        $path = Str::after($url, $base);
        return Storage::disk('public')->delete($path);
    }
}
