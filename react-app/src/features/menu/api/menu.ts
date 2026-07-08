import { apiClient } from '../../../shared/api/client'
import type { MenuResponse } from '../types'

export async function fetchMainMenu(): Promise<MenuResponse> {
  const { data } = await apiClient.get<MenuResponse>('/main-menu')
  return data
}
