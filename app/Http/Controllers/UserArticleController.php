<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserArticle;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleDetailsResource;
use App\Models\Article;
use App\Services\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserArticleController extends Controller
{
    private string $COVER_FOLDER = "article_covers";

    private ImageUploader $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index(Request $request)
    {
        $articles = $request->user()->articles()->orderByDesc("created_at")->simplePaginate(10);
        return ArticleDetailsResource::collection($articles);
    }

    public function store(StoreUserArticle $request)
    {
        $articleData = $request->safe()->only(['content', 'title']);
        $coverImage = $request->file('cover_image');

        if ($coverImage) {
            $coverImageUrl = $this->imageUploader->upload($coverImage, $this->COVER_FOLDER);

            if (!$coverImageUrl) {
                return response()->json([
                    'message' => 'An error has occurred. Please try later.',
                ], 500);
            }

            $articleData['cover_image_url'] = $coverImageUrl;
        }

        $article = $request->user()->articles()->create($articleData);

        return response()->json(["id" => $article->id], 201);
    }

    public function update(Article $article, UpdateArticleRequest $request)
    {
        Gate::authorize('update', $article);

        if ($article->cover_image_url) {
            $isDeleted = $this->imageUploader->delete($article->cover_image_url);

            if (!$isDeleted) {
                return response()->json([
                    'message' => 'An error has occurred. Please try later.',
                ], 500);
            }
        }

        $articleData = $request->safe()->only(['content', 'title']);
        $coverImage = $request->file('cover_image');

        if ($coverImage) {
            $coverImageUrl = $this->imageUploader->upload($coverImage, $this->COVER_FOLDER);

            if (!$coverImageUrl) {
                return response()->json([
                    'message' => 'An error has occurred. Please try later.',
                ], 500);
            }

            $articleData['cover_image_url'] = $coverImageUrl;
        } else {
            $articleData['cover_image_url'] = null;
        }

        $article->update($articleData);

        return response()->noContent();
    }
}
