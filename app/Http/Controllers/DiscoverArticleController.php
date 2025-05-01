<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;

class DiscoverArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy("created_at", "desc")->simplePaginate(10);
        return ArticleResource::collection($articles);
    }
}
