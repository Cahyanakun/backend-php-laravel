<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\User\UserListResource;

class UserListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'rows' => UserListResource::collection($this->collection),
            'meta' => [
                "current_page" => $this->currentPage(),
                "from" => (($this->currentPage() - 1) * $this->perPage()) + 1,
                "last_page" => $this->lastPage(),
                "per_page" => $this->perPage(),
                "to" => $this->currentPage() * $this->perPage(),
                "total" => $this->total()
            ]
        ];
    }
}
