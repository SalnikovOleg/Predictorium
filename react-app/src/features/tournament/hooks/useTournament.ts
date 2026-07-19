import { useQuery } from '@tanstack/react-query'
import { fetchTournament } from '../api/tournament'

export function useTournament(slug: string) {
  return useQuery({
    queryKey: ['tournament', slug],
    queryFn: () => fetchTournament(slug),
  })
}
