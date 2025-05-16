<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserArticle;
use App\Services\ImageUploader;

class UserArticleController extends Controller
{
    private string $COVER_FOLDER = "article_covers";

    private ImageUploader $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
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
}
