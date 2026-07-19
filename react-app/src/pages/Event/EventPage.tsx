import { useState } from 'react'
import { useParams, Link } from 'react-router'
import { useEvent, useCreateStake } from '@/features/event'
import { H1, H2, LoadingSpinner, ErrorMessage, TournamentHr } from '@/components/ui/common'
import { DateLine } from '@/components/ui/date'
import { MarketCard } from '@/features/event/ui/MarketCard'

const CURRENT_GROUP_ID = 1
const CURRENT_USER_ID = 1

export function EventPage() {
  const { slug } = useParams<{ slug: string }>()
  const { data, isLoading, error } = useEvent(slug!)
  const createStake = useCreateStake()
  const [selectedOutcomes, setSelectedOutcomes] = useState<Record<number, number | null>>({})

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

  const handleSelectOutcome = (marketId: number, outcomeId: number) => {
    const currentSelected = selectedOutcomes[marketId]
    const newSelected = currentSelected === outcomeId ? null : outcomeId

    setSelectedOutcomes((prev) => ({
      ...prev,
      [marketId]: newSelected,
    }))

    if (newSelected !== null) {
      createStake.mutate({
        group_id: CURRENT_GROUP_ID,
        user_id: CURRENT_USER_ID,
        event_id: event.id,
        outcome_id: newSelected,
      })
    }
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
