import { Link } from 'react-router'
import { useMainMenu } from '../hooks/useMainMenu'
import { useAppStore } from '../../../app/store'
import { ErrorMessage } from '@/components/ui/ErrorMessage'

function MenuIcon() {
  return (
    <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  )
}

function CloseIcon() {
  return (
    <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
      <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
  )
}

export function NavBar() {
  const { data, isLoading, error } = useMainMenu()
  const { sidebarOpen, toggleSidebar } = useAppStore()

  return (
    <>
    <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
      <Link to="/" className="text-xl font-bold text-white">
        Predictorium
      </Link>
    
      {/* Desktop nav */}
      <nav className="hidden md:block">
        {isLoading && (
          <div className="h-6 w-32 animate-pulse rounded bg-gray-700" />
        )}
        {error && <ErrorMessage message="Failed to load menu" />}
        {data && (
          <ul className="flex items-center gap-6">
            {data.data.items.map((item) => (
              <li key={item.url}>
                <Link
                  to={item.url}
                  className="text-gray-300 transition-colors hover:text-white"
                >
                  {item.icon} {item.label}
                </Link>
              </li>
            ))}
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
        <nav className="md:hidden border-t border-gray-800 px-4 pb-4">
          {isLoading && (
            <div className="h-6 w-32 animate-pulse rounded bg-gray-700" />
          )}
          {error && <ErrorMessage message="Failed to load menu" />}
          {data && (
            <ul className="flex flex-col gap-3 pt-3">
              {data.data.items.map((item) => (
                <li key={item.url}>
                  <Link
                    to={item.url}
                    onClick={toggleSidebar}
                    className="block text-gray-300 transition-colors hover:text-white"
                  >
                    {item.icon} {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          )}
        </nav>
      )}
    </>
  )
}
