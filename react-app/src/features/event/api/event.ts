import { apiClient } from '@/lib/api/client'
import type { EventSummary, Event, Stake, MarketStat } from '../types'

export async function fetchEventsByTournament(tournamentId: number): Promise<EventSummary[]> {
  const { data } = await apiClient.get<{ data: EventSummary[] }>(`/tournaments/${tournamentId}/events`)
  return data.data
}

export async function fetchEvent(slug: string): Promise<Event> {
  const { data } = await apiClient.get<{ data: Event }>(`/events/${slug}`)
  return data.data
}

export async function fetchStakes(
  groupId: number,
  userId: number,
  eventId: number,
): Promise<Stake[]> {
  const { data } = await apiClient.get<{ data: Stake[] }>('/stakes', {
    params: { group_id: groupId, user_id: userId, event_id: eventId },
  })
  return data.data
}

export async function createStake(payload: {
  group_id: number
  user_id: number
  event_id: number
  market_id: number
  outcome_ids: number[]
}): Promise<void> {
  await apiClient.post('/stake', payload)
}

export async function fetchMarketStats(marketId: number): Promise<MarketStat[]> {
  const { data } = await apiClient.get<{ data: MarketStat[] }>(`/stakes/stat/${marketId}`)
  return data.data
}
