<?php

namespace App\Http\Resources\WebStructure;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = collect($this->items)->map(function ($item) {
            return [
                'label'    => $item['label'] ?? null,
                'url'      => $item['data']['url'] ?? null,
                'children' => $item['children'] ?? [],
            ];
        })->values();

        return [
            'data' => [
                'items' => $items,
            ],
        ];
    }
}
