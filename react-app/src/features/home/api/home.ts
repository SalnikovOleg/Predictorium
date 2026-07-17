import { apiClient } from '../../../lib/api/client'
import type { HomeResponse } from '../types'

export async function fetchHome(): Promise<HomeResponse> {
  const { data } = await apiClient.get<HomeResponse>('/home')
  return data
}
