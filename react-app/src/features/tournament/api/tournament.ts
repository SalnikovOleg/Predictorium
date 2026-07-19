import { apiClient } from '@/lib/api/client'
import type { TournamentResponse } from '../types'

export async function fetchTournament(slug: string): Promise<TournamentResponse> {
  const { data } = await apiClient.get<TournamentResponse>(`/tournaments/${slug}`)
  return data
}
