import { useQuery } from '@tanstack/react-query'
import { fetchEventsByTournament } from '../api/event'

export function useEventsByTournament(tournamentId: number) {
  return useQuery({
    queryKey: ['events', 'tournament', tournamentId],
    queryFn: () => fetchEventsByTournament(tournamentId),
  })
}
