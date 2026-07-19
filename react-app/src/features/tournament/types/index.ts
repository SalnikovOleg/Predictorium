import type { ApiResponse, PageContent } from '@/shared/types'
import type { EventSummary } from '@/features/event/types'

export interface TournamentSummary {
  id: number
  name: string
  slug: string
  icon: string | null
  description: string | null
  start_date: string
  end_date: string
  events_count?: number
}

export interface Tournament extends TournamentSummary {
  category: {
    id: number
    name: string
    slug: string
  }
  contents: PageContent[]
  events: EventSummary[]
}

export type TournamentResponse = ApiResponse<Tournament>
