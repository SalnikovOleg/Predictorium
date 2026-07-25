<?php

namespace App\Repositories\Trading;

use App\Models\Result;
use Illuminate\Database\Eloquent\Collection;

class ResultRepository
{
    public function __construct(
        protected Result $model,
    ) {}

    public function getByEventId(int $eventId): Collection
    {
        return $this->model->where('event_id', $eventId)->get();
    }

    public function findByResultTypeAndParticipant(
        int $eventId,
        int $resultTypeId,
        ?int $participantId
    ): ?Result {
        return $this->model
            ->where('event_id', $eventId)
            ->where('result_type_id', $resultTypeId)
            ->where('participant_id', $participantId)
            ->first();
    }

    public function findByResultType(int $eventId, int $resultTypeId): ?Result
    {
        return $this->model
            ->where('event_id', $eventId)
            ->where('result_type_id', $resultTypeId)
            ->first();
    }
}
