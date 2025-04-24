<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticle;
use App\Models\Article;
use App\Services\ImageUploader;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    private string $COVERS_FOLDER = "covers";
    private string $COVER_SUFFIX = "_cover";

    private ImageUploader $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function store(StoreArticle $request)
    {
        $articleData = $request->safe()->only(['content', 'title']);

        if ($request->file()) {
            $coverImageName = $this->getCoverImageName();
            $coverImageUrl = $this->imageUploader->upload($request, $this->COVERS_FOLDER, $coverImageName);

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

    private function getCoverImageName()
    {
        return Auth::id() . $this->COVER_SUFFIX;
    }
}
