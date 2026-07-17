import { apiClient } from '@/lib/api/client'
import type { CategoryResponse } from '../types'

export async function fetchCategory(slug: string): Promise<CategoryResponse> {
  const { data } = await apiClient.get<CategoryResponse>(`/categories/${slug}`)
  return data
}
