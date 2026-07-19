import { useState, useEffect } from 'react'
import { useParams, Link } from 'react-router'
import { useEvent, useStakes, useCreateStake } from '@/features/event'
import { useAuthStore, useRequireAuth } from '@/features/auth'
import { H1, H2, LoadingSpinner, ErrorMessage, TournamentHr } from '@/components/ui/common'
import { DateLine } from '@/components/ui/date'
import { MarketCard } from '@/features/event/ui/MarketCard'

const CURRENT_GROUP_ID = 1

export function EventPage() {
  const { slug } = useParams<{ slug: string }>()
  const { data, isLoading, error } = useEvent(slug!)
  const createStake = useCreateStake()
  const user = useAuthStore((s) => s.user)
  const { requireAuth } = useRequireAuth()
  const [selectedOutcomes, setSelectedOutcomes] = useState<Record<number, number | null>>({})

  const { data: stakes } = useStakes(
    CURRENT_GROUP_ID,
    user?.id,
    data?.id,
  )

  useEffect(() => {
    if (!stakes || stakes.length === 0) return

    const initial: Record<number, number | null> = {}
    for (const stake of stakes) {
      initial[stake.market_id] = stake.outcome_id
    }
    setSelectedOutcomes(initial)
  }, [stakes])

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

  const handleSelectOutcome = (marketId: number, outcomeId: number) => {
    if (isStakesLocked) return

    requireAuth(() => {
      const currentSelected = selectedOutcomes[marketId]
      const newSelected = currentSelected === outcomeId ? null : outcomeId

      setSelectedOutcomes((prev) => ({
        ...prev,
        [marketId]: newSelected,
      }))

      if (newSelected !== null && user) {
        createStake.mutate({
          group_id: CURRENT_GROUP_ID,
          user_id: user.id,
          event_id: event.id,
          market_id: marketId,
          outcome_id: newSelected,
        })
      }
    })
  }

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
              <p className="text-sm text-yellow-400 font-medium">Stakes is locked</p>
            </div>
          )}

          {event.markets.length === 0 ? (
            <p className="text-gray-400">No markets available for this event.</p>
          ) : (
            <div className="space-y-4">
              {event.markets.map((market) => (
                <MarketCard
                  key={market.id}
                  market={market}
                  selectedOutcomeId={selectedOutcomes[market.id] ?? null}
                  onSelectOutcome={(outcomeId) => handleSelectOutcome(market.id, outcomeId)}
                  isPending={createStake.isPending}
                  isDisabled={isStakesLocked}
                />
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
