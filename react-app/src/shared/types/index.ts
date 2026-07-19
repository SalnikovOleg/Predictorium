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
  outcome_type_id: number
  name: string
}

export interface Market {
  id: number
  name: string
  description: string
  market_type_id: number
  param1: number
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
