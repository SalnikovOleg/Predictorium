<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimplePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $content = $this->contents->first();

        return [
            'data' => [
                'id' => $this->id,
                'slug' => $this->slug,
                'contents' => $this->contents->map(fn ($c) => [
                        'id' => $c->id,
                        'lang' => $c->lang,
                        'title' => $c->title,
                        'content' => $c->content,
                ])
            ],
        ];
    }
}
