export interface ApiResponse<T> {
  status: boolean
  data: T
}

export interface PageContent {
  id: number
  model_type: string
  model_id: number
  lang: string
  title: string
  content: string
  created_at: string
  updated_at: string
}

export interface SimplePage {
  id: number
  slug: string
  is_active: boolean
  params_json: unknown[]
  created_at: string
  updated_at: string
  deleted_at: string | null
  contents: PageContent[]
}

export interface Category {
  id: number
  name: string
  slug: string
  is_active: boolean
  sort_order: number
  icon: string
  contents: Array<{
    id: number
    title: string
    content: string
    lang: string
  }>
  tournaments: TournamentSummary[]
}

export interface TournamentSummary {
  id: number
  name: string
  slug: string
  description: string | null
  status: string
  start_date: string
  end_date: string | null
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

export interface EventSummary {
  id: number
  name: string
  slug: string
  status: string
  start_date: string
  end_date: string
}

export interface OutcomeType {
  id: number
  name: string
}

export interface Participant {
  id: number
  name: string
}

export interface Outcome {
  id: number
  coef: string
  result: string | null
  outcome_type: OutcomeType
  participant: Participant | null
}

export interface Market {
  id: number
  description: string
  outcomes: Outcome[]
}

export interface Event extends EventSummary {
  tournament: {
    id: number
    name: string
    slug: string
  }
  contents: PageContent[]
  markets: Market[]
}
