<?php

namespace App\Services;

use Illuminate\Http\Request;

interface ImageUploader
{
    public function upload(Request $request, string $folder, string $name): string;
}
