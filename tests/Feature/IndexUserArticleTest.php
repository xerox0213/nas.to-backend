<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexUserArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieve_user_articles(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(8)->for($user)->create();

        $response = $this->actingAs($user)->getJson(route('articles.index'));

        $response
            ->assertOk()
            ->assertJsonCount(8, "data")
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        "id",
                        "title",
                        "cover_image_url",
                        "author"
                    ]
                ]
            ]);
    }
}
