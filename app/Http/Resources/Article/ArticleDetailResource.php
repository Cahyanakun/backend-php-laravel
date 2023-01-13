<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserDetailResource;

class ArticleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'publish' => $this->publish,
            'publish_text' => $this->publish_text,
            'user_id' => $this->user_id,
            'user' => new UserDetailResource($this->whenLoaded('users')),
        ];
    }
}
