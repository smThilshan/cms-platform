<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'order'     => $this->order,
            'parent_id' => $this->parent_id,
            'children'  => MenuItemResource::collection($this->whenLoaded('children')),
            'pages'     => PageResource::collection($this->whenLoaded('pages')),
        ];
    }
}
