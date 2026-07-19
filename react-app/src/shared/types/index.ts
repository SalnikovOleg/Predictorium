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