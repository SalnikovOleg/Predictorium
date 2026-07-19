import { apiClient } from '@/lib/api/client'
import type { EventSummary, Event } from '../types'

export async function fetchEventsByTournament(tournamentId: number): Promise<EventSummary[]> {
  const { data } = await apiClient.get<{ data: EventSummary[] }>(`/tournaments/${tournamentId}/events`)
  return data.data
}

export async function fetchEvent(slug: string): Promise<Event> {
  const { data } = await apiClient.get<{ data: Event }>(`/events/${slug}`)
  return data.data
}

export async function createStake(payload: {
  group_id: number
  user_id: number
  event_id: number
  outcome_id: number
}): Promise<void> {
  await apiClient.post('/stake', payload)
}
