<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status->value,
            'start_date' => $this->start_date?->format('Y-m-d H:i'),
            'end_date' => $this->end_date?->format('Y-m-d H:i'),
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ],
            'contents' => $this->contents->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'content' => $c->content,
                'lang' => $c->lang,
            ]),
            'events' => $this->events->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'slug' => $e->slug,
                'status' => $e->status->value,
                'start_date' => $e->start_date?->format('Y-m-d H:i'),
                'end_date' => $e->end_date?->format('Y-m-d H:i'),
            ]),
        ];
    }
}
