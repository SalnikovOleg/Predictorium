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
            'stake_items' =>$this->whenLoaded('stakeItems', function () {
                return $this->stakeItems->map(fn ($item) => [
                    'market_id'  => $item->market_id,
                    'outcome_id' => $item->outcome_id,
                    'result'     => $item->result,
                ]);
            }),
        ];
    }
}
