import { useMutation } from '@tanstack/react-query'
import { register } from '../api/auth'
import { useAuthStore } from './useAuthStore'

export function useRegister() {
  const setAuth = useAuthStore((s) => s.setAuth)

  return useMutation({
    mutationFn: ({
      name,
      email,
      password,
      password_confirmation,
    }: {
      name: string
      email: string
      password: string
      password_confirmation: string
    }) => register(name, email, password, password_confirmation),
    onSuccess: (data) => {
      setAuth(data.data, data.token)
    },
  })
}
