export interface EventSummary {
  id: number
  slug: string
  name: string
  start_date: string
  end_date: string | null
  status: string
}

export interface Event {
  id: number
  slug: string
  name: string
  status: string
  start_date: string
  tournament: {
    slug: string
    name: string
  }
  markets: Market[]
}

export interface Outcome {
  id: number
  name: string
  coef: number
}

export interface Market {
  id: number
  name: string
  description: string
  market_type_id: number
  param1: number
  outcomes: Outcome[]
}

export interface Stake {
  market_id: number
  outcome_id: number
  outcome_ids: number[] | null
}
