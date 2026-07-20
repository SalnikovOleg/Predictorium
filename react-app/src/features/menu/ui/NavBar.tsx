import { Link } from 'react-router'
import { useMainMenu } from '../hooks/useMainMenu'
import { useAppStore } from '../../../app/store'
import { useAuthStore } from '@/features/auth'
import { ErrorMessage, MenuIcon, CloseIcon } from '@/components/ui/common'

export function NavBar() {
  const { data, isLoading, error } = useMainMenu()
  const { sidebarOpen, toggleSidebar } = useAppStore()
  const { isAuthenticated, user, openLoginModal } = useAuthStore()

  return (
    <>
    <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
      <Link to="/" className="text-xl font-bold text-white">
        Predictorium
      </Link>

      {/* Desktop nav */}
      <nav className="hidden md:block">
        {isLoading && (
          <div className="h-6 w-32 animate-pulse rounded bg-white/10" />
        )}
        {error && <ErrorMessage message="Failed to load menu" />}
        {data && (
          <ul className="flex items-center gap-6">
            {data.data.items.map((item) => (
              <li key={item.url}>
                <Link
                  to={item.url}
                  className="text-gray-300 transition-colors hover:text-[var(--color-accent)]"
                >
                  {item.icon} {item.label}
                </Link>
              </li>
            ))}
            <li>
              {isAuthenticated && user ? (
                <Link
                  to={`/profile/${user.id}`}
                  className="text-gray-300 transition-colors hover:text-[var(--color-accent)]"
                >
                  👽 Profile ({user.name})
                </Link>
              ) : (
                <button
                  type="button"
                  onClick={openLoginModal}
                  className="text-gray-300 transition-colors hover:text-[var(--color-accent)]"
                >
                  🔑 Login
                </button>
              )}
            </li>
          </ul>
        )}
      </nav>

      {/* Hamburger button */}
      <button
        type="button"
        className="md:hidden text-gray-300 hover:text-white"
        onClick={toggleSidebar}
        aria-label={sidebarOpen ? 'Close menu' : 'Open menu'}
      >
        {sidebarOpen ? <CloseIcon /> : <MenuIcon />}
      </button>

    </div>

      {/* Mobile nav */}
      {sidebarOpen && (
        <nav className="md:hidden border-t border-[--color-border] px-4 pb-4 bg-[#0a1e24]/80 backdrop-blur-sm">
          {isLoading && (
            <div className="h-6 w-32 animate-pulse rounded bg-white/10" />
          )}
          {error && <ErrorMessage message="Failed to load menu" />}
          {data && (
            <ul className="flex flex-col gap-3 pt-3">
              {data.data.items.map((item) => (
                <li key={item.url}>
                  <Link
                    to={item.url}
                    onClick={toggleSidebar}
                    className="block text-gray-300 transition-colors hover:text-[--color-accent]"
                  >
                    {item.icon} {item.label}
                  </Link>
                </li>
              ))}
              <li>
                {isAuthenticated && user ? (
                  <Link
                    to={`/profile/${user.id}`}
                    onClick={toggleSidebar}
                    className="block text-gray-300 transition-colors hover:text-[--color-accent]"
                  >
                    👽 Profile ({user.name})
                  </Link>
                ) : (
                  <button
                    type="button"
                    onClick={() => {
                      toggleSidebar()
                      openLoginModal()
                    }}
                    className="block text-left text-gray-300 transition-colors hover:text-[--color-accent]"
                  >
                    🔑 Login
                  </button>
                )}
              </li>
            </ul>
          )}
        </nav>
      )}
    </>
  )
}
