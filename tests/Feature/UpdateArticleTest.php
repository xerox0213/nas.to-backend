<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    private string $COVER_FOLDER = "article_covers";

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_update_article_with_new_cover_deletes_old_cover(): void
    {
        $oldCoverImage = UploadedFile::fake()->image('old_img.jpg');
        $oldCoverImagePath = $oldCoverImage->store($this->COVER_FOLDER, 'public');
        $oldCoverImageUrl = Storage::disk('public')->url($oldCoverImagePath);
        $article = Article::factory()->create(["cover_image_url" => $oldCoverImageUrl]);
        $user = $article->user;
        $newCoverImage = UploadedFile::fake()->image('new_img.jpg');
        $newTitle = "PNL - Capuche";
        $newContent = "Seul sous la capuche";
        $newData = [
            'title' => $newTitle,
            'content' => $newContent,
            'cover_image' => $newCoverImage
        ];

        $response = $this->actingAs($user)->patchJson(route('articles.update', ['article' => $article->id]), $newData);
        $article->refresh();

        $response->assertNoContent();
        $this->assertEquals($newTitle, $article->title);
        $this->assertEquals($newContent, $article->content);
        $this->assertNotEquals($oldCoverImageUrl, $article->cover_image_url);
        Storage::disk('public')->assertMissing($oldCoverImagePath);
    }

    public function test_update_article_without_new_cover_removes_old_cover(): void
    {
        $oldCoverImage = UploadedFile::fake()->image('old_img.jpg');
        $oldCoverImagePath = $oldCoverImage->store($this->COVER_FOLDER, 'public');
        $oldCoverImageUrl = Storage::disk('public')->url($oldCoverImagePath);
        $article = Article::factory()->create(["cover_image_url" => $oldCoverImageUrl]);
        $user = $article->user;
        $newTitle = "PNL - Capuche";
        $newContent = "Seul sous la capuche";
        $newData = [
            'title' => $newTitle,
            'content' => $newContent,
        ];

        $response = $this->actingAs($user)->patchJson(route('articles.update', ['article' => $article->id]), $newData);
        $article->refresh();

        $response->assertNoContent();
        $this->assertEquals($newTitle, $article->title);
        $this->assertEquals($newContent, $article->content);
        $this->assertNull($article->cover_image_url);
        Storage::disk('public')->assertMissing($oldCoverImagePath);
    }

    public function test_update_article_fails_for_non_owner()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();
        $newTitle = "PNL - Capuche";
        $newContent = "Seul sous la capuche";
        $newData = [
            'title' => $newTitle,
            'content' => $newContent,
        ];

        $response = $this->actingAs($user)->patchJson(route('articles.update', ['article' => $article->id]), $newData);
        $response->assertForbidden();
    }
}
