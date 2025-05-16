<?php

namespace Tests\Feature;

use App\Services\LocalImageUploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class LocalImageUploaderTest extends TestCase
{
    private LocalImageUploader $imageUploader;
    private string $FOLDER = 'articles_cover';

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageUploader = new LocalImageUploader();
    }

    public function test_should_upload_image_with_name(): void
    {
        Storage::fake('public');
        $coverImage = UploadedFile::fake()->image('img.jpg');
        $coverImageUrl = $this->imageUploader->upload($coverImage, $this->FOLDER);
        $base = Storage::disk('public')->url('');
        $pathCoverImage = Str::after($coverImageUrl, $base);
        Storage::disk('public')->assertExists($pathCoverImage);
    }
}
