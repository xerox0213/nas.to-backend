<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ImageUploader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class StoreArticle extends TestCase
{
    use RefreshDatabase;

    private array $basicArticleData = [
        'title' => 'Why Damon is an asshole ?',
        'content' => 'Because he is a vampire.'
    ];

    public function test_should_store_article_with_cover()
    {
        $coverImageUrl = 'url';

        $this->mock(ImageUploader::class, function (MockInterface $mock) use ($coverImageUrl) {
            $mock->expects('upload')->once()->andReturn($coverImageUrl);
        });

        $this->basicArticleData['cover_image'] = UploadedFile::fake()->image('damon.jpg');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('articles.store'), $this->basicArticleData);

        $response->assertCreated()->assertJsonStructure(['id']);

        $this->assertDatabaseHas('articles', [
            'title' => $this->basicArticleData['title'],
            'content' => $this->basicArticleData['content'],
            'cover_image_url' => $coverImageUrl
        ]);
    }

    public function test_should_store_article_without_cover()
    {
        $this->mock(ImageUploader::class, function (MockInterface $mock) {
            $mock->expects('upload')->never();
        });

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('articles.store'), $this->basicArticleData);

        $response->assertCreated()->assertJsonStructure(['id']);

        $this->assertDatabaseHas('articles', [
            'title' => $this->basicArticleData['title'],
            'content' => $this->basicArticleData['content'],
            'cover_image_url' => null
        ]);
    }

    public function test_should_not_store_article_when_cover_upload_fails()
    {
        $this->mock(ImageUploader::class, function (MockInterface $mock) {
            $mock->expects('upload')->once()->andReturn('');
        });

        $this->basicArticleData['cover_image'] = UploadedFile::fake()->image('damon.jpg');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('articles.store'), $this->basicArticleData);

        $response->assertServerError();

        $this->assertDatabaseMissing('articles', [
            'title' => $this->basicArticleData['title'],
            'content' => $this->basicArticleData['content'],
        ]);
    }

    public function test_should_reject_request_if_validation_errors()
    {
        $invalidArticleData = [
            'title' => '',
            'content' => ''
        ];
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('articles.store'), $invalidArticleData);

        $response->assertJsonValidationErrors(['title', 'content']);
    }
}
