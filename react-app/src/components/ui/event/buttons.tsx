import { cn } from '@/lib/utils'

interface ViewStatsButtonProps {
  showStats: boolean
  onClick: () => void
  className?: string
}

export function ViewStatsButton({ showStats, onClick, className }: ViewStatsButtonProps) {
  return (
    <div className="relative">
      <button
        type="button"
        className={cn(
          'flex items-center gap-1 text-sm text-gray-400 hover:text-white transition-colors',
          className
        )}
        onClick={onClick}
        aria-expanded={showStats}
      >
        <div className="p-1 border border-accent rounded-sm">
          <svg className="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
            />
          </svg>
        </div>
        View stats
      </button>
    </div>
  )
}

interface ShowMoreButtonProps {
  showAll: boolean
  onClick: () => void
  remainingCount: number
  className?: string
}

export function ShowMoreButton({ showAll, onClick, remainingCount, className }: ShowMoreButtonProps) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={cn(
        'mt-3 w-full py-2 text-sm text-gray-400 hover:text-white transition-colors border border-[--color-border] rounded-lg hover:bg-[--color-border]/20',
        className
      )}
    >
      {showAll ? 'Show less' : `+ ${remainingCount} more`}
    </button>
  )
}

interface DescriptionTooltipProps {
  description: string
  className?: string
}

export function DescriptionTooltip({ description, className }: DescriptionTooltipProps) {
  return (
    <div className={cn('group relative', className)}>
      <button
        type="button"
        className="text-gray-400 hover:text-white transition-colors"
      >
        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
        </svg>
      </button>
      <div className="absolute left-0 top-6 z-10 hidden group-hover:block w-64 p-2 bg-[#0a1e24] border border-[--color-border] rounded-lg text-sm text-gray-300 shadow-lg">
        {description}
      </div>
    </div>
  )
}