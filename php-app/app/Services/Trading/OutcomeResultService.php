<?php

namespace App\Services\Trading;

use App\Enums\EventStatus;
use App\Enums\OutcomeResult;
use App\Models\Outcome;
use App\Repositories\Trading\EventRepository;
use App\Repositories\Trading\OutcomeRepository;
use App\Repositories\Trading\ResultRepository;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class OutcomeResultService
{
    public function __construct(
        protected EventRepository $eventRepository,
        protected OutcomeRepository $outcomeRepository,
        protected ResultRepository $resultRepository,
    ) {}

    public function calculateForEvent(int $eventId): void
    {
        $event = $this->eventRepository->getById($eventId, 'en');

        if (!$event) {
            throw new InvalidArgumentException("Event {$eventId} not found");
        }

        if ($event->status !== EventStatus::Finished) {
            throw new InvalidArgumentException(
                "Event {$eventId} must be 'finished' to calculate results, current status: {$event->status->value}"
            );
        }

        $results = $this->resultRepository->getByEventId($eventId);
        $outcomes = $this->getOutcomesForEvent($eventId);

        $resultsByType = $results->keyBy('result_type_id');
        $outcomesByMarket = $outcomes->groupBy('market_id');

        $updates = [];

        foreach ($outcomes as $outcome) {
            $result = $this->determineResult($outcome, $resultsByType, $outcomesByMarket, $eventId);
            $updates[$outcome->id] = $result;
        }

        $this->outcomeRepository->bulkUpdateResult($updates);
    }

    protected function getOutcomesForEvent(int $eventId): Collection
    {
        return Outcome::whereHas('market', fn ($q) => $q->where('event_id', $eventId))
            ->with(['outcomeType', 'market.marketTemplate'])
            ->get();
    }

    protected function determineResult(
        Outcome $outcome,
        Collection $resultsByType,
        Collection $outcomesByMarket,
        int $eventId
    ): OutcomeResult {
        $marketTypeId = $outcome->market->marketTemplate->market_type_id;

        return match (true) {
            // Rule 1: Selection with position-based results
            $marketTypeId === 3 && in_array($outcome->result_type_id, [1, 2]) =>
                $this->handleSelectionWithPositions($outcome, $resultsByType),

            // Rule 2: Selection with participant-based results
            $marketTypeId === 3 && in_array($outcome->result_type_id, [3, 4, 5, 6]) =>
                $this->handleSelectionWithParticipant($outcome, $eventId),

            // Rule 3: Participant-based Boolean
            $marketTypeId === 2 =>
                $this->handleParticipantBoolean($outcome, $eventId),

            // Rule 4: Boolean
            $marketTypeId === 1 =>
                $this->handleBoolean($outcome, $resultsByType),

            // Rule 5: Binary Selection with position-based results
            $marketTypeId === 4 && in_array($outcome->result_type_id, [1, 2]) =>
                $this->handleBinarySelectionWithPositions($outcome, $resultsByType, $outcomesByMarket),

            // Rule 6: Exact Score (market_type_id 4-52 are score variants, result_type_id 7 is Score)
            $marketTypeId >= 4 && $marketTypeId <= 52 && $outcome->result_type_id === 7 =>
                $this->handleExactScore($outcome, $resultsByType),

            // Default: lose if no matching rule
            default => OutcomeResult::Lose,
        };
    }

    protected function handleSelectionWithPositions(
        Outcome $outcome,
        Collection $resultsByType
    ): OutcomeResult {
        $result = $resultsByType->get($outcome->result_type_id);

        if (!$result || empty($result->value)) {
            return OutcomeResult::Lose;
        }

        $winnerId = $this->extractParticipantId($result->value, 0);

        return $winnerId === $outcome->participant_id
            ? OutcomeResult::Win
            : OutcomeResult::Lose;
    }

    protected function handleSelectionWithParticipant(
        Outcome $outcome,
        int $eventId
    ): OutcomeResult {
        $result = $this->resultRepository->findByResultTypeAndParticipant(
            $eventId,
            $outcome->result_type_id,
            $outcome->participant_id
        );

        return $result ? OutcomeResult::Win : OutcomeResult::Lose;
    }

    protected function handleParticipantBoolean(
        Outcome $outcome,
        int $eventId
    ): OutcomeResult {
        $result = $this->resultRepository->findByResultTypeAndParticipant(
            $eventId,
            $outcome->result_type_id,
            $outcome->participant_id
        );

        // outcome_type_id = 1 (Yes): Win if result exists
        // outcome_type_id = 2 (No):  Win if result does NOT exist
        return $outcome->outcome_type_id === 1
            ? ($result ? OutcomeResult::Win : OutcomeResult::Lose)
            : ($result ? OutcomeResult::Lose : OutcomeResult::Win);
    }

    protected function handleBoolean(
        Outcome $outcome,
        Collection $resultsByType
    ): OutcomeResult {
        $result = $resultsByType->get($outcome->result_type_id);

        return $result ? OutcomeResult::Win : OutcomeResult::Lose;
    }

    protected function handleBinarySelectionWithPositions(
        Outcome $outcome,
        Collection $resultsByType,
        Collection $outcomesByMarket
    ): OutcomeResult {
        $result = $resultsByType->get($outcome->result_type_id);

        if (!$result || empty($result->value)) {
            return OutcomeResult::Return;
        }

        $marketOutcomes = $outcomesByMarket->get($outcome->market_id);

        if (!$marketOutcomes || $marketOutcomes->count() < 2) {
            return OutcomeResult::Return;
        }

        $placements = [];
        $foundAny = false;
        foreach ($marketOutcomes as $marketOutcome) {
            $index = $this->findParticipantIndex($result->value, $marketOutcome->participant_id);
            if ($index !== false) {
                $foundAny = true;
            }
            $placements[$marketOutcome->id] = $index !== false ? $index : PHP_INT_MAX;
        }

        if (!$foundAny) {
            return OutcomeResult::Return;
        }

        $minPlace = min($placements);
        $isWinner = $placements[$outcome->id] === $minPlace;

        return $isWinner ? OutcomeResult::Win : OutcomeResult::Lose;
    }

    protected function handleExactScore(
        Outcome $outcome,
        Collection $resultsByType
    ): OutcomeResult {
        $result = $resultsByType->get($outcome->result_type_id);

        if (!$result || !$result->value) {
            return OutcomeResult::Lose;
        }

        $actualScore = $result->value['score'] ?? null;

        if (!$actualScore) {
            return OutcomeResult::Lose;
        }

        return $outcome->outcomeType->name === $actualScore
            ? OutcomeResult::Win
            : OutcomeResult::Lose;
    }

    private function extractParticipantId(array $value, int $index): ?int
    {
        return $value[$index] ?? null;
    }

    private function findParticipantIndex(array $value, int $participantId): int|false
    {
        return array_search($participantId, $value);
    }
}
