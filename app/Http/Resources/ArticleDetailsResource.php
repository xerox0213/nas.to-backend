<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Article */
class ArticleDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'cover_image_url' => $this->cover_image_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => new AuthorResource($this->user),
        ];
    }
}
