import type { ApiResponse } from '../../../shared/types'

export interface MenuItem {
  label: string
  url: string
  icon: string
  children: MenuItem[]
}

export type MenuResponse = ApiResponse<{ items: MenuItem[] }>
