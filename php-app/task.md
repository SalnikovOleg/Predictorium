After click "New Market" we have an error:

TypeError
app/Filament/Resources/MarketResource.php:108
App\Filament\Resources\MarketResource::{closure:App\Filament\Resources\MarketResource::form():108}(): Argument #1 ($get) must be of type Filament\Forms\Get, Filament\Schemas\Components\Utilities\Get given, called in /var/www/html/vendor/filament/support/src/Concerns/EvaluatesClosures.php on line 36


App\Filament\Resources\MarketResource::{closure:App\Filament\Resources\MarketResource::form():108}(object(Filament\Schemas\Components\Utilities\Get))
app/Filament/Resources/MarketResource.php:108

103                                return Participant::whereHas('events', fn ($q) => $q->where('events.id', $eventId))
104                                    ->pluck('name', 'id');
105                            })
106                            ->searchable()
107                            ->preload()
108                            ->visible(fn (Forms\Get $get) => (int) $get('market_type_id') === 2)
109                            ->afterStateUpdated(function ($set, $get, $state) {
110                                $template = MarketTemplate::find($get('market_template_id'));
111                                if ($template && $state) {
112                                    $participant = Participant::find($state);
113                                    $set('description', $template->name . ' — ' . $participant->name);
114                                }
115                            }),

