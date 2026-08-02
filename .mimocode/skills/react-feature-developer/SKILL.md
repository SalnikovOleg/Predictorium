---
name: react-feature-developer
description: Builds React 19 + Vite 8 + TypeScript 6 features for Predictorium frontend following the feature-based architecture: hooks, API, types, and UI components in features/<domain>/ with market_type-based rendering.
triggers: [react feature, create component, event page, market card, useQuery, useMutation, tanstack query, market_type]
---

# React Feature Developer (Predictorium)

## Purpose

Build React 19 features for the Predictorium sports betting frontend using the project's feature-based architecture, TanStack Query, and market_type-driven UI rendering.

## When to Use

- Creating new feature pages (EventPage, CategoryPage, TournamentPage)
- Building market display components (MarketCard, MarketScoreCard, variants)
- Implementing stake placement flows
- Adding hooks for data fetching/mutation
- Creating TypeScript types matching API responses

## Architecture Conventions

### 1. Feature-Based Folder Structure

```
src/
├── features/
│   └── <domain>/           # e.g., event, category, tournament
│       ├── api/            # API functions (fetchX, createX)
│       ├── hooks/          # TanStack Query hooks (useX, useCreateX)
│       ├── ui/             # Presentational components
│       ├── types/          # TypeScript interfaces
│       └── index.ts        # Barrel exports
├── pages/                  # Route-level page components
│   └── <Domain>/           # e.g., Event/EventPage.tsx
├── components/
│   └── ui/                 # Design system (H1, H2, Date, Background, etc.)
└── shared/
    └── types/              # Cross-feature types
```

### 2. API Layer (`features/<domain>/api/`)

```typescript
// features/event/api/event.ts
import { api } from '@/shared/lib/api';  // axios instance with baseURL

export interface EventResponse {
  data: Event;
}

export interface CreateStakePayload {
  group_id: number;
  user_id: number;
  event_id: number;
  market_id: number;
  outcome_id?: number;
  outcome_ids?: number[];
}

export const fetchEvent = (eventId: string | number) =>
  api.get<EventResponse>(`/events/${eventId}`).then(r => r.data);

export const createStake = (payload: CreateStakePayload) =>
  api.post('/stake', payload).then(r => r.data);
```

- Use the shared `api` axios instance (configured with `baseURL: '/api'` via Vite proxy)
- Functions return typed promises
- Route names match Laravel `->name()` (e.g., `api.events.show`, `api.stakes.store`)

### 3. Hooks Layer (`features/<domain>/hooks/`)

```typescript
// features/event/hooks/useEvent.ts
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { fetchEvent, createStake } from '../api/event';

export const useEvent = (eventId: string | number) =>
  useQuery({
    queryKey: ['event', eventId],
    queryFn: () => fetchEvent(eventId),
    enabled: !!eventId,
  });

export const useCreateStake = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: createStake,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['event'] });
    },
  });
};
```

- `useQuery` for GET, `useMutation` for POST/PUT/DELETE
- `queryKey` arrays for granular invalidation
- `onSuccess` invalidates related queries

### 4. Types Layer (`features/<domain>/types/`)

```typescript
// features/event/types/index.ts
export interface Outcome {
  id: number;
  market_id: number;
  outcome_type_id: number;
  name: string;
  coef: number;
  result: 'win' | 'lose' | 'return' | null;
  participant_id: number | null;
}

export interface Market {
  id: number;
  event_id: number;
  market_template_id: number;
  name: string;
  market_type_id: number;
  param1: number;        // max outcomes per stake
  param2: number | null;
  sort_order: number;
  description: string | null;
  outcomes: Outcome[];
}

export interface Event {
  id: number;
  tournament_id: number;
  name: string;
  slug: string;
  start_date: string;    // 'yyyy-mm-dd HH:ii'
  end_date: string;
  status: 'active' | 'finished' | 'cancelled';
  markets: Market[];
  tournament: Tournament;
}
```

- Match Laravel API Resource shapes exactly
- Dates are strings in `yyyy-mm-dd HH:ii` format
- Enums as string unions (not numeric)

### 5. UI Components (`features/<domain>/ui/`)

**MarketCard** — Base market display, handles `market_type_id` 1, 2, 4 (two outcomes per row):

```tsx
// features/event/ui/MarketCard.tsx
interface MarketCardProps {
  market: Market;
  onStake: (payload: CreateStakePayload) => void;
  selectedOutcomeId?: number;
}

export const MarketCard = ({ market, onStake, selectedOutcomeId }: MarketCardProps) => {
  const { market_type_id, param1, outcomes } = market;
  
  // market_type_id 1,2,4: two buttons per row
  // market_type_id 3: many outcomes, two rows + expand
  
  return (
    <div className="bg-white/10 rounded-lg p-4">
      <div className="flex items-center justify-between mb-3">
        <h3 className="text-lg font-medium">{market.name}</h3>
        <div className="flex gap-2">
          <InfoIcon tooltip={market.description} />
          <StatsIcon onClick={() => {}} />
        </div>
      </div>
      
      <div className="grid grid-cols-2 gap-2">
        {outcomes.map(outcome => (
          <OutcomeButton
            key={outcome.id}
            outcome={outcome}
            selected={selectedOutcomeId === outcome.id}
            onClick={() => onStake({ ..., outcome_id: outcome.id })}
          />
        ))}
      </div>
    </div>
  );
};
```

**MarketScoreCard** — Separate component for `market_type_id === 5` (exact score), 7 outcomes per row:

```tsx
// features/event/ui/MarketScoreCard.tsx
export const MarketScoreCard = ({ market, onStake }: MarketCardProps) => (
  <div className="bg-white/10 rounded-lg p-4">
    <h3 className="text-lg font-medium mb-3">{market.name}</h3>
    <div className="grid grid-cols-7 gap-2 max-h-24 overflow-hidden">
      {outcomes.slice(0, 7).map(outcome => (
        <OutcomeButton key={outcome.id} outcome={outcome} onClick={...} />
      ))}
      {outcomes.length > 7 && <MoreButton count={outcomes.length - 7} />}
    </div>
  </div>
);
```

**Conditional Rendering in Page:**

```tsx
// pages/Event/EventPage.tsx
import { MarketCard } from '@/features/event/ui/MarketCard';
import { MarketScoreCard } from '@/features/event/ui/MarketScoreCard';

{markets.map(market => 
  market.market_type_id === 5 ? (
    <MarketScoreCard key={market.id} market={market} onStake={handleStake} />
  ) : (
    <MarketCard key={market.id} market={market} onStake={handleStake} />
  )
)}
```

### 6. Design System (`components/ui/`)

**All visual styling lives in `components/ui/`** — feature components only handle structure/logic.

```tsx
// components/ui/common.tsx
interface HProps { children: React.ReactNode; className?: string; }

export const H1 = ({ children, className }: HProps) => (
  <h1 className={`text-3xl font-bold text-white ${className}`}>{children}</h1>
);

export const H2 = ({ children, className }: HProps) => (
  <h2 className={`text-2xl font-semibold text-white ${className}`}>{children}</h2>
);

export const TournamentHr = () => <hr className="border-white/20 my-4" />;
```

**Date component** (`components/ui/date.tsx`):
```tsx
export const DateLine = ({ date, className }: { date: string; className?: string }) => (
  <time className={`text-white/70 ${className}`}>{date}</time>
);
```

### 7. Page Structure (`pages/<Domain>/<Domain>Page.tsx`)

```tsx
// pages/Event/EventPage.tsx
import { H1, H2, TournamentHr, DateLine } from '@/components/ui';
import { useEvent } from '@/features/event/hooks/useEvent';
import { MarketCard, MarketScoreCard } from '@/features/event/ui';

export const EventPage = ({ params }: { params: { slug: string } }) => {
  const { data, isLoading } = useEvent(params.slug);
  const createStake = useCreateStake();
  
  if (isLoading) return <div>Loading...</div>;
  if (!data) return <div>Not found</div>;
  
  const { tournament, name, start_date, markets } = data;
  
  return (
    <div className="min-h-screen bg-gradient-to-b from-gray-900 to-black p-6">
      <H1 className="mb-2">
        <a href={`/tournament/${tournament.slug}`} className="hover:underline">
          {tournament.name}
        </a>
      </H1>
      <H2 className="mb-2">{name}</H2>
      <DateLine date={start_date} className="text-xl mb-6" />
      <TournamentHr />
      
      <div className="grid gap-4">
        {markets.map(market => 
          market.market_type_id === 5 ? (
            <MarketScoreCard key={market.id} market={market} onStake={createStake.mutate} />
          ) : (
            <MarketCard key={market.id} market={market} onStake={createStake.mutate} />
          )
        )}
      </div>
      
      <aside className="mt-8 p-4 bg-white/5 rounded-lg">
        {/* Placeholder for sidebar */}
      </aside>
    </div>
  );
};
```

### 8. Stake Placement

```typescript
// Temporary hardcoded for development
const current_group_id = 1;
const current_user_id = 1;

const handleStake = (outcomeId: number, marketId: number) => {
  createStake.mutate({
    group_id: current_group_id,
    user_id: current_user_id,
    event_id: eventId,
    market_id: marketId,
    outcome_id: outcomeId,
  });
};
```

## market_type_id Rendering Rules

| market_type_id | Name | Outcomes Layout | Component |
|---|---|---|---|
| 1 | Boolean | 2 (Yes/No) | MarketCard |
| 2 | Participant Boolean | 2 (Yes/No with participant) | MarketCard |
| 3 | Selection | Many (param1 = max picks) | MarketCard (expandable) |
| 4 | Binary Selection | 2 (both participants) | MarketCard |
| 5 | Outcome List (Exact Score) | 7 per row, overflow hidden | MarketScoreCard |

**MarketCard for type 3:** Show first 2 rows, "Show more" expands to all outcomes.

## Procedure

1. **Define Types** in `features/<domain>/types/index.ts` matching API response
2. **Create API Functions** in `features/<domain>/api/<domain>.ts`
3. **Create Hooks** in `features/<domain>/hooks/use<Domain>.ts` (useQuery, useMutation)
4. **Build UI Components** in `features/<domain>/ui/` — separate per market_type if needed
5. **Export** via `features/<domain>/index.ts` and `features/<domain>/types/index.ts`
6. **Create Page** in `pages/<Domain>/<Domain>Page.tsx` using design system components
7. **Verify**: `npm run build` passes, page renders in browser

## Quality Bar

- [ ] Types match Laravel API Resources exactly
- [ ] API functions use shared `api` axios instance
- [ ] Hooks use TanStack Query with proper queryKeys
- [ ] UI components in `features/<domain>/ui/` (not `components/ui/`)
- [ ] Design system components used from `components/ui/`
- [ ] market_type_id conditional rendering implemented
- [ ] Stake payload matches backend expectation
- [ ] `npm run lint` and `npm run build` pass

## Anti-patterns

- Putting visual styles (colors, shadows, spacing) in feature UI components
- Creating MarketCard variants with if/else inside one component (use separate components)
- Using `fetch` directly instead of shared `api` instance
- Missing queryKey invalidation after mutations
- Hardcoding API URLs instead of using named routes via axios baseURL
- Placing business logic in page components (use hooks)