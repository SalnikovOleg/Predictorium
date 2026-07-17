import { useQuery } from '@tanstack/react-query'
import { fetchCategory } from '../api/category'

export function useCategory(slug: string) {
  return useQuery({
    queryKey: ['category', slug],
    queryFn: () => fetchCategory(slug),
  })
}
