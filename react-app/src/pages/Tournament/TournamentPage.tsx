import { useParams } from 'react-router'
import { useTournament } from '@/features/tournament'
import { EventCard } from '@/features/event'
import { H1, H2, H3, LoadingSpinner, ErrorMessage, TournamentHr } from '@/components/ui/common'
import { TournamentAsideBlock } from '@/components/ui/blocks'

export function TournamentPage() {
  const { slug } = useParams<{ slug: string }>()
  const { data, isLoading, error } = useTournament(slug!)

  if (isLoading) {
    return <LoadingSpinner />
  }

  if (error) {
    return <ErrorMessage message={error.message} />
  }

  const tournament = data?.data

  if (!tournament) {
    return <ErrorMessage message="Tournament not found" />
  }

  return (
    <div className="space-y-6">
      <H1>{tournament.category.name}</H1>

      <div className="grid gap-8 lg:grid-cols-[1fr_340px]">
        <div className="space-y-4">
          <H2 className="lg:text-3xl">{tournament.name}</H2>

          <TournamentHr />

          {tournament.events.length === 0 ? (
            <H3>No events available.</H3>
          ) : (
            <div className="space-y-4">
              {tournament.events.map((event) => (
                <EventCard key={event.id} event={event} categorySlug={tournament.category.slug} />
              ))}
            </div>
          )}
        </div>

        <aside>
          <TournamentAsideBlock
            description={tournament.description}
            start_date={tournament.start_date}
            end_date={tournament.end_date}
          >
            {tournament.icon && (
              <img
                src={tournament.icon}
                alt={tournament.name}
                className="w-full rounded-2xl object-contain"
              />
            )}
          </TournamentAsideBlock>
        </aside>
      </div>
    </div>
  )
}
