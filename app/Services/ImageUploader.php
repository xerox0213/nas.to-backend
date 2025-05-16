<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

interface ImageUploader
{
    public function upload(UploadedFile $image, string $folder): string;

    public function delete(string $url): bool;
}
