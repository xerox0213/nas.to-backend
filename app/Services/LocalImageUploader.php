<?php

namespace App\Services;

use Illuminate\Http\Request;

class LocalImageUploader implements ImageUploader
{
    public function upload(Request $request, string $folder, string $name): string
    {
        $path = $request->file()->storeAs('covers', $name);
        return $path ? asset($path) : '';
    }
}
