<?php

namespace App\Http\Resources\Trading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutcomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coef' => $this->coef,
            'result' => $this->result?->value,
            'outcome_type_id' => $this->outcomeType->id,
            'name' => $this->participant->name ?? $this->outcomeType->name,
        ];
    }
}
