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
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'icon' => $this->icon,
            'contents' => $this->contents->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'content' => $c->content,
                'lang' => $c->lang,
            ]),
            'tournaments' => $this->tournaments->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'description' => $t->description,
                'status' => $t->status->value,
                'start_date' => $t->start_date?->format('Y-m-d H:i'),
                'end_date' => $t->end_date?->format('Y-m-d H:i'),
            ]),
        ];
    }
}
