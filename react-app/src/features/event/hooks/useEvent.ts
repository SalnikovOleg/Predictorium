import { useCallback } from 'react'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { fetchEvent, fetchStakes, createStake, fetchMarketStats } from '../api/event'

export function useEvent(slug: string) {
  return useQuery({
    queryKey: ['event', slug],
    queryFn: () => fetchEvent(slug),
  })
}

export function useStakes(
  groupId: number,
  userId: number | undefined,
  eventId: number | undefined,
) {
  return useQuery({
    queryKey: ['stakes', groupId, userId, eventId],
    queryFn: () => fetchStakes(groupId, userId!, eventId!),
    enabled: userId !== undefined && eventId !== undefined,
  })
}

export function useCreateStake() {
  const queryClient = useQueryClient()

  const mutation = useMutation({
    mutationFn: createStake,
    onSuccess: (_data, variables) => {
      queryClient.invalidateQueries({ queryKey: ['event', variables.event_id] })
    },
  })

  const mutateDebounced = useCallback(
    (payload: Parameters<typeof createStake>[0]) => {
      mutation.mutate(payload)
    },
    [mutation],
  )

  return {
    ...mutation,
    mutate: mutateDebounced,
  }
}

export function useMarketStats(marketId: number | null) {
  return useQuery({
    queryKey: ['marketStats', marketId],
    queryFn: () => fetchMarketStats(marketId!),
    enabled: marketId !== null,
  })
}
