<?php

namespace App\Filament\Resources\MarketResource\Pages;

use App\Filament\Resources\MarketResource;
use App\Models\Market;
use App\Models\MarketTemplate;
use App\Models\Outcome;
use Filament\Resources\Pages\CreateRecord;

class CreateMarket extends CreateRecord
{
    protected static string $resource = MarketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['event_id'] = request()->query('event_id', $data['event_id'] ?? null);

        unset($data['market_type_id']);
        unset($data['participant_ids']);
        unset($data['participant_a_id']);
        unset($data['participant_b_id']);
        unset($data['param1']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $market = $this->record;
        $template = MarketTemplate::find($market->market_template_id);

        if (! $template) {
            return;
        }

        $marketTypeId = (int) $template->market_type_id;
        $outcomeTypeIds = $template->outcome_template_ids ?? [];

        $yesTypeId = $this->findOutcomeTypeId('yes');
        $noTypeId = $this->findOutcomeTypeId('no');
        $participantTypeId = $this->findOutcomeTypeId('participant');

        match ($marketTypeId) {
            1, 2 => $this->createBooleanOutcomes($market, $yesTypeId, $noTypeId),
            3 => $this->createSelectionOutcomes($market, $participantTypeId),
            4 => $this->createBinarySelectionOutcomes($market, $participantTypeId),
            default => null,
        };
    }

    private function createBooleanOutcomes(Market $market, int $yesTypeId, int $noTypeId): void
    {
        Outcome::create([
            'market_id' => $market->id,
            'outcome_type_id' => $yesTypeId,
            'coef' => 1.00,
        ]);

        Outcome::create([
            'market_id' => $market->id,
            'outcome_type_id' => $noTypeId,
            'coef' => 1.00,
        ]);
    }

    private function createSelectionOutcomes(Market $market, int $participantTypeId): void
    {
        $participantIds = $this->data['participant_ids'] ?? [];

        foreach ($participantIds as $participantId) {
            Outcome::create([
                'market_id' => $market->id,
                'outcome_type_id' => $participantTypeId,
                'participant_id' => $participantId,
                'coef' => 1.00,
            ]);
        }
    }

    private function createBinarySelectionOutcomes(Market $market, int $participantTypeId): void
    {
        $participantAId = $this->data['participant_a_id'] ?? null;
        $participantBId = $this->data['participant_b_id'] ?? null;

        if ($participantAId) {
            Outcome::create([
                'market_id' => $market->id,
                'outcome_type_id' => $participantTypeId,
                'participant_id' => $participantAId,
                'coef' => 1.00,
            ]);
        }

        if ($participantBId) {
            Outcome::create([
                'market_id' => $market->id,
                'outcome_type_id' => $participantTypeId,
                'participant_id' => $participantBId,
                'coef' => 1.00,
            ]);
        }
    }

    private function findOutcomeTypeId(string $name): int
    {
        return \App\Models\OutcomeType::where('name', $name)->first()->id;
    }
}
