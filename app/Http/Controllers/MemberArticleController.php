<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleDetailsResource;
use App\Models\Article;

class MemberArticleController extends Controller
{
    public function show(Article $article)
    {
        return new ArticleDetailsResource($article);
    }
}
