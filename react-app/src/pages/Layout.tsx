import { Outlet } from 'react-router'
import { NavBar } from '../features/menu'
import { useAuthStore, LoginModal } from '../features/auth'
import { Header, Background } from '../components/ui/common'

export function Layout() {
  const loginModalOpen = useAuthStore((s) => s.loginModalOpen)
  const closeLoginModal = useAuthStore((s) => s.closeLoginModal)

  return (
    <div className="relative min-h-screen text-gray-100">
      {/* Background image */}
      <Background />

      {/* Content */}
      <div className="relative z-10">
        <Header>
          <NavBar />
        </Header>

        <main className="mx-auto max-w-7xl px-4 py-8">
          <Outlet />
        </main>
      </div>

      {/* Login modal - rendered outside z-10 container to avoid stacking context */}
      <LoginModal open={loginModalOpen} onClose={closeLoginModal} />
    </div>
  )
}
