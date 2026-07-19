<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->description ?? $this->marketTemplate->name,
            'description' => $this->marketTemplate->description,
            'market_type_id' => $this->whenLoaded('marketTemplate', fn() => $this->marketTemplate->market_type_id),
            'param1'         => $this->whenLoaded('marketTemplate', fn() => $this->marketTemplate->param1),
            'outcomes' => OutcomeResource::collection($this->whenLoaded('outcomes')),
        ];
    }
}
