import { useMarketStats } from '../hooks/useEvent'

interface CurrentStatsPopoverProps {
  marketId: number
  onClose: () => void
}

export function CurrentStatsPopover({ marketId, onClose }: CurrentStatsPopoverProps) {
  const { data: stats, isLoading, isError } = useMarketStats(marketId)

  return (
    <>
      <div className="fixed inset-0 z-40" onClick={onClose} />
      <div className="absolute left-0 top-full mt-2 z-50 w-72 rounded-lg border border-[var(--color-border)]/60 bg-[#0a1e24]/95 p-4 shadow-xl shadow-black/30">
        <h4 className="text-sm font-semibold text-white mb-3">Current stats</h4>

        {isLoading && (
          <div className="space-y-3">
            {[1, 2, 3].map((i) => (
              <div key={i} className="animate-pulse space-y-1.5">
                <div className="flex justify-between">
                  <div className="h-3 w-24 rounded bg-white/10" />
                  <div className="h-3 w-8 rounded bg-white/10" />
                </div>
                <div className="h-1.5 w-full rounded-full bg-white/5" />
              </div>
            ))}
          </div>
        )}

        {isError && (
          <p className="text-xs text-[--color-muted-foreground]">Failed to load stats.</p>
        )}

        {stats && stats.length === 0 && (
          <p className="text-xs text-[--color-muted-foreground]">No predictions yet.</p>
        )}

        {stats && stats.length > 0 && (
          <div className="space-y-3">
            {stats.map((stat) => (
              <div key={stat.outcome_id}>
                <div className="flex items-center justify-between mb-1.5">
                  <span className="text-xs text-gray-300 truncate max-w-[160px]">{stat.name}</span>
                  <span className="text-xs font-semibold text-white tabular-nums">{stat.percent}%</span>
                </div>
                <div className="h-2 w-full rounded-full bg-white/10 overflow-hidden">
                  <div
                    className="h-full rounded-full bg-gradient-to-r from-[#3dd5de] to-[#2bc4c9] transition-all duration-500 ease-out"
                    style={{ width: `${stat.percent}%` }}
                  />
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </>
  )
}
