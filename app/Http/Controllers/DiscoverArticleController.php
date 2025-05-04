<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlePreviewResource;
use App\Models\Article;

class DiscoverArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy("created_at", "desc")->simplePaginate(10);
        return ArticlePreviewResource::collection($articles);
    }
}
