import { apiClient } from '@/lib/api/client'
import type { AuthResponse, User } from '../types'

export async function login(email: string, password: string): Promise<AuthResponse> {
  const { data } = await apiClient.post<AuthResponse>('/auth/login', { email, password })
  return data
}

export async function register(
  name: string,
  email: string,
  password: string,
  password_confirmation: string,
): Promise<AuthResponse> {
  const { data } = await apiClient.post<AuthResponse>('/auth/register', {
    name,
    email,
    password,
    password_confirmation,
  })
  return data
}

export async function logout(): Promise<void> {
  await apiClient.post('/auth/logout')
}

export async function fetchMe(): Promise<{ data: User }> {
  const { data } = await apiClient.get<{ data: User }>('/auth/me')
  return data
}

export interface UpdateUserPayload {
  name?: string
  email?: string
  password?: string
  password_confirmation?: string
  current_password?: string
}

export interface UpdateUserResponse {
  status: boolean
  message: string
  data: User
}

export async function updateUser(payload: UpdateUserPayload): Promise<UpdateUserResponse> {
  const { data } = await apiClient.put<UpdateUserResponse>('/auth/update', payload)
  return data
}
