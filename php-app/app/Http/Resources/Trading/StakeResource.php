<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
            'event_id' => $this->event_id,
            'market_id' => $this->market_id,
            'outcome_id' => $this->outcome_id,
            'outcome_ids' => $this->outcome_ids,
        ];
    }
}
