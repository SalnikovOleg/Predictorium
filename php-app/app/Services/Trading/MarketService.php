<?php

namespace App\Services\Trading;

use App\Models\Event;
use App\Models\Market;
use App\Models\MarketTemplate;
use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Repositories\Trading\MarketRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MarketService
{
    public function __construct(
        protected MarketRepository $repository,
    ) {}

    public function getMarketsByEventId(int $eventId): Collection
    {
        return $this->repository->getByEventId($eventId);
    }

    public function createDefaultsMarket(int $eventId): int
    {
        return DB::transaction(function () use ($eventId) {
            $event = Event::with('tournament.config')->findOrFail($eventId);
            $templateIds = $event->tournament->config->market_template_ids ?? [];

            if (empty($templateIds)) {
                return 0;
            }

            $templates = MarketTemplate::whereIn('id', $templateIds)->get();
            $created = 0;

            foreach ($templates as $template) {
                if ($this->repository->existsByEventAndTemplate($eventId, $template->id)) {
                    continue;
                }

                $market = Market::create([
                    'event_id' => $eventId,
                    'market_template_id' => $template->id
                 ]);

                $this->createOutcomesForMarket($market, $template, $event);
                $created++;
            }

            return $created;
        });
    }

    private function createOutcomesForMarket(Market $market, MarketTemplate $template, Event $event): void
    {
        $marketTypeId = (int) $template->market_type_id;

        if ($marketTypeId === 1 || $marketTypeId === 5) {
            $outcomeTypeIds = $template->outcome_type_ids ?? [];
            foreach ($outcomeTypeIds as $typeId) {
                Outcome::create([
                    'market_id' => $market->id,
                    'outcome_type_id' => $typeId,
                    'result_type_id' => $template->result_type_id,
                    'coef' => 1.00,
                ]);
            }
        } elseif ($marketTypeId === 3) {
            $participantTypeId = OutcomeType::where('name', 'participant')->first()->id;
            $participantIds = $event->participants()->pluck('participants.id');

            foreach ($participantIds as $participantId) {
                Outcome::create([
                    'market_id' => $market->id,
                    'outcome_type_id' => $participantTypeId,
                    'result_type_id' => $template->result_type_id,
                    'participant_id' => $participantId,
                    'coef' => 1.00,
                ]);
            }
        }
    }
}
