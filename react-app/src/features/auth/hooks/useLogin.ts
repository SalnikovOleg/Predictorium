import { useMutation } from '@tanstack/react-query'
import { login } from '../api/auth'
import { useAuthStore } from './useAuthStore'

export function useLogin() {
  const setAuth = useAuthStore((s) => s.setAuth)

  return useMutation({
    mutationFn: ({ email, password }: { email: string; password: string }) =>
      login(email, password),
    onSuccess: (data) => {
      setAuth(data.data, data.token)
    },
  })
}
