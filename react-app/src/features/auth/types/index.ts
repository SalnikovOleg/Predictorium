export interface User {
  id: number
  name: string
  email: string
  roles: string[]
}

export interface AuthResponse {
  status: boolean
  message: string
  data: User
  token: string
}
