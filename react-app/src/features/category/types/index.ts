import type { ApiResponse } from '@/shared/types'
import type { TournamentSummary } from '@/features/tournament/types'

export interface CategoryContent {
  title: string
  content: string
}

export interface Category {
  id: number
  name: string
  slug: string
  is_active: boolean
  sort_order: number
  icon: string
  contents: CategoryContent[]
  tournaments: TournamentSummary[]
}

export type CategoryResponse = ApiResponse<Category>
