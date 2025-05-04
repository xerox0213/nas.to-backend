<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowMemberArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_detailed_article()
    {
        $article = Article::factory()->create();
        $params = ['article' => $article->id];

        $response = $this->getJson(route('articles.show', $params));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'cover_image_url',
                'created_at',
                'updated_at',
                'author' => [
                    'id',
                    'name',
                    'avatar_image_url'
                ]
            ]);
    }

    public function test_should_return_not_found()
    {
        $params = ['article' => 123];

        $response = $this->getJson(route('articles.show', $params));

        $response->assertNotFound();
    }
}
