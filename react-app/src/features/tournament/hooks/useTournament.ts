import { useQuery } from '@tanstack/react-query'
import { fetchTournament } from '../api/tournament'

export function useTournament(id: number) {
  return useQuery({
    queryKey: ['tournament', id],
    queryFn: () => fetchTournament(id),
  })
}
