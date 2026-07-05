<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status->value,
            'start_date' => $this->start_date?->format('Y-m-d H:i'),
            'end_date' => $this->end_date?->format('Y-m-d H:i'),
            'tournament' => [
                'id' => $this->tournament->id,
                'name' => $this->tournament->name,
                'slug' => $this->tournament->slug,
            ],
        ];
    }
}
