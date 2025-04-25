<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

interface ImageUploader
{
    public function upload(UploadedFile $image, string $folder, string $name): string;
}
