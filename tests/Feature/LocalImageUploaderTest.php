<?php

namespace Tests\Feature;

use App\Services\LocalImageUploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocalImageUploaderTest extends TestCase
{
    private LocalImageUploader $imageUploader;
    private string $FOLDER = 'articles_cover';
    private string $SUFFIX = '_cover';

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageUploader = new LocalImageUploader();
    }

    public function test_should_upload_image(): void
    {
        $coverImage = UploadedFile::fake()->image('img.jpg');
        $coverImageName = '1' . $this->SUFFIX . '.' . $coverImage->extension();
        $pathCoverImage = $this->FOLDER . DIRECTORY_SEPARATOR . $coverImageName;
        $coverImageUrl = $this->imageUploader->upload($coverImage, $this->FOLDER, $coverImageName);
        $expectedCoverImageUrl = Storage::url($pathCoverImage);

        Storage::disk('public')->assertExists($pathCoverImage);
        $this->assertEquals($expectedCoverImageUrl, $coverImageUrl);
    }
}
