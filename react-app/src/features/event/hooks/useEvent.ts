import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { fetchEvent, createStake } from '../api/event'

export function useEvent(slug: string) {
  return useQuery({
    queryKey: ['event', slug],
    queryFn: () => fetchEvent(slug),
  })
}

export function useCreateStake() {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: createStake,
    onSuccess: (_data, variables) => {
      queryClient.invalidateQueries({ queryKey: ['event', variables.event_id] })
    },
  })
}
