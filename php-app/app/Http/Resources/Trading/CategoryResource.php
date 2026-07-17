<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon_url,
            'contents' => $this->contents->map(fn ($c) => [
                'title' => $c->title,
                'content' => $c->content,
             ]),
            'tournaments' => $this->tournaments->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'icon' => $t->icon_url,
                'description' => $t->description,
                'start_date' => $t->start_date?->format('Y-m-d H:i'),
                'end_date' => $t->end_date?->format('Y-m-d H:i'),
            ]),
        ];
    }
}
