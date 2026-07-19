import { useCallback } from 'react'
import { useAuthStore } from './useAuthStore'

export function useRequireAuth() {
  const isAuthenticated = useAuthStore((s) => s.isAuthenticated)
  const openLoginModal = useAuthStore((s) => s.openLoginModal)

  const requireAuth = useCallback(
    (action: () => void) => {
      if (isAuthenticated) {
        action()
      } else {
        openLoginModal()
      }
    },
    [isAuthenticated, openLoginModal],
  )

  return { requireAuth, isAuthenticated }
}
