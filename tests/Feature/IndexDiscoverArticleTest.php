<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexDiscoverArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_first_ten_articles()
    {
        Article::factory()->count(20)->create();

        $response = $this->getJson(route("discover-articles.index"));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    "*" => [
                        'id',
                        'title',
                        'content',
                        'created_at',
                        'updated_at',
                        'author' => [
                            "id",
                            "name",
                            "avatar_image_url"
                        ]
                    ]
                ],
                'meta',
                'links' => ["next"]
            ])
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.current_page', 1);
    }

    public function test_should_return_next_ten_articles()
    {
        Article::factory()->count(20)->create();

        $response = $this->getJson(route("discover-articles.index", ["page" => 2]));

        $response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }
}
