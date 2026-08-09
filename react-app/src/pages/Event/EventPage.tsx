import { useState, useMemo } from 'react'
import { useParams, Link } from 'react-router'
import { useEvent, useStakes, useCreateStake } from '@/features/event'
import { useAuthStore, useRequireAuth } from '@/features/auth'
import { H1, H2, LoadingSpinner, ErrorMessage, TournamentHr } from '@/components/ui/common'
import { DateLine } from '@/components/ui/date'
import { MarketCard } from '@/features/event/ui/MarketCard'
import { MarketScoreCard } from '@/features/event/ui/MarketScoreCard'

const CURRENT_GROUP_ID = 1

export function EventPage() {
  const { slug } = useParams<{ slug: string }>()
  const { data, isLoading, error } = useEvent(slug!)
  const createStake = useCreateStake()
  const user = useAuthStore((s) => s.user)
  const { requireAuth } = useRequireAuth()

  const { data: stakes } = useStakes(
    CURRENT_GROUP_ID,
    user?.id,
    data?.id,
  )

  // Derive selected outcomes from stakes data (server state)
  const serverSelectedOutcomes = useMemo(() => {
    if (!stakes || stakes.length === 0) return {}
    const initial: Record<number, number[]> = {}
    for (const stake of stakes) {
      for (const item of stake.stake_items) {
        if (item.outcome_id) {
          initial[item.market_id] = [...(initial[item.market_id] ?? []), item.outcome_id]
        }
      }
    }
    return initial
  }, [stakes])

  // Local state for optimistic updates (merges with server state)
  const [localSelections, setLocalSelections] = useState<Record<number, number[]>>({})

  // Combined selected outcomes: server + local optimistic updates
  const selectedOutcomes = useMemo(() => {
    const merged: Record<number, number[]> = { ...serverSelectedOutcomes }
    for (const [marketId, outcomeIds] of Object.entries(localSelections)) {
      merged[Number(marketId)] = outcomeIds
    }
    return merged
  }, [serverSelectedOutcomes, localSelections])

  const handleSelectOutcome = (marketId: number, outcomeId: number) => {
    if (isLoading || error || !data) return
    if (data.status !== 'active') return

    requireAuth(() => {
      const market = data.markets.find((m) => m.id === marketId)
      if (!market) return

      const maxSelections = market.param1 ?? 1
      const currentSelected = selectedOutcomes[marketId] ?? []
      const isSelected = currentSelected.includes(outcomeId)

      let newSelected: number[]
      if (isSelected) {
        // Deselect
        newSelected = currentSelected.filter((id) => id !== outcomeId)
      } else {
        // Select - check limit
        if (currentSelected.length >= maxSelections) return
        newSelected = [...currentSelected, outcomeId]
      }

      // Optimistic update for immediate UI feedback
      setLocalSelections((prev) => ({
        ...prev,
        [marketId]: newSelected,
      }))

      if (newSelected.length > 0 && user) {
        createStake.mutate({
          group_id: CURRENT_GROUP_ID,
          user_id: user.id,
          event_id: data.id,
          market_id: marketId,
          outcome_ids: newSelected,
        })
      }
    })
  }

  if (isLoading) {
    return <LoadingSpinner />
  }

  if (error) {
    return <ErrorMessage message={error.message} />
  }

  const event = data

  if (!event) {
    return <ErrorMessage message="Event not found" />
  }

  const isStakesLocked = event.status !== 'active'

  return (
    <div className="space-y-6">
      <H1>
        <Link
          to={`/tournaments/${event.tournament.slug}`}
          className="hover:text-white transition-colors"
        >
          {event.tournament.name}
        </Link>
      </H1>

      <div className="grid gap-8 lg:grid-cols-[1fr_340px]">
        <div className="space-y-4">

          <H2 className="lg:text-3xl">{event.name}</H2>

          <DateLine date={event.start_date} className="text-base"/>

          <TournamentHr />

          {isStakesLocked && (
            <div className="rounded-lg border border-yellow-500/30 bg-yellow-500/10 p-4">
              <p className="text-sm text-yellow-400 font-medium">Stakes is locked. Event is {event.status}</p>
            </div>
          )}

          {event.markets.filter((m) => m.outcomes.length > 0).length === 0 ? (
            <p className="text-gray-400">No markets available for this event.</p>
          ) : (
            <div className="space-y-4">
              {event.markets.filter((m) => m.outcomes.length > 0).map((market) => (
                market.market_type_id === 5 ? (
                  <MarketScoreCard
                    key={market.id}
                    market={market}
                    selectedOutcomeIds={selectedOutcomes[market.id] ?? []}
                    onSelectOutcome={(outcomeId) => handleSelectOutcome(market.id, outcomeId)}
                    isPending={createStake.isPending}
                    isDisabled={isStakesLocked}
                  />
                ) : (
                  <MarketCard
                    key={market.id}
                    market={market}
                    selectedOutcomeIds={selectedOutcomes[market.id] ?? []}
                    onSelectOutcome={(outcomeId) => handleSelectOutcome(market.id, outcomeId)}
                    isPending={createStake.isPending}
                    isDisabled={isStakesLocked}
                  />
                )
              ))}
            </div>
          )}
        </div>

        <aside className="space-y-4">
          <div className="rounded-lg border border-[--color-border] bg-[#0a1e24]/60 p-4">
            <h3 className="text-lg font-semibold text-white mb-3">About the tournament</h3>
            <p className="text-sm text-gray-400">
              {event.tournament.name}
            </p>
          </div>
        </aside>
      </div>
    </div>
  )
}
