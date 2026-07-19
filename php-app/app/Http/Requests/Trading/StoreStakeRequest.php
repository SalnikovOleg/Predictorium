<?php

namespace App\Http\Requests\Trading;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:groups,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'event_id' => ['required', 'integer', 'exists:events,id', function ($attribute, $value, $fail) {
                $event = Event::find($value);
                if ($event && $event->status !== EventStatus::Active) {
                    $fail('The event is not active.');
                }
            }],
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'outcome_id' => ['required', 'integer', 'exists:outcomes,id'],
            'outcome_ids' => ['nullable', 'array'],
            'outcome_ids.*' => ['integer', 'exists:outcomes,id'],
        ];
    }
}
