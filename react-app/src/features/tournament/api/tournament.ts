import { apiClient } from '@/lib/api/client'
import type { TournamentResponse } from '../types'

export async function fetchTournament(id: number): Promise<TournamentResponse> {
  const { data } = await apiClient.get<TournamentResponse>(`/tournaments/${id}`)
  return data
}
