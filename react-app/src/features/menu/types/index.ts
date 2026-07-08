import type { ApiResponse, MenuItem } from '../../../shared/types'

export type MenuResponse = ApiResponse<{ items: MenuItem[] }>
