import { useMutation } from '@tanstack/react-query'
import { updateUser, type UpdateUserPayload } from '../api/auth'
import { useAuthStore } from './useAuthStore'

export function useUpdateUser() {
  const setUser = useAuthStore((s) => s.setUser)

  return useMutation({
    mutationFn: (payload: UpdateUserPayload) => updateUser(payload),
    onSuccess: (data) => {
      setUser(data.data)
    },
  })
}
