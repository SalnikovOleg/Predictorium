import { useQuery } from '@tanstack/react-query'
import { fetchHome } from '../api/home'

export function useHome() {
  return useQuery({
    queryKey: ['home'],
    queryFn: fetchHome,
  })
}
