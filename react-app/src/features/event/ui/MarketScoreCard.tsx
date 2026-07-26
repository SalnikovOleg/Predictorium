import { useState } from 'react'
import { cn } from '@/lib/utils'
import type { Market, Outcome } from '../types'
import { MarketOutcomeButton } from './MarketOutcomeButton'
import { CurrentStatsPopover } from './CurrentStatsPopover'
import {H3} from '@/components/ui/common'

interface MarketScoreCardProps {
  market: Market
  selectedOutcomeId: number | null
  onSelectOutcome: (outcomeId: number) => void
  isPending?: boolean
  isDisabled?: boolean
}

function toMatrix7x7(outcomes: Outcome[]): (Outcome | null)[][] {
  const matrix: (Outcome | null)[][] = []
  for (let row = 0; row < 7; row++) {
    const start = row * 7
    const slice = outcomes.slice(start, start + 7)
    while (slice.length < 7) slice.push(null)
    matrix.push(slice)
  }
  return matrix
}

export function MarketScoreCard({ market, selectedOutcomeId, onSelectOutcome, isPending, isDisabled }: MarketScoreCardProps) {
  const [showAll, setShowAll] = useState(false)
  const [showStats, setShowStats] = useState(false)
  const matrix = toMatrix7x7(market.outcomes)
  const visibleRows = showAll ? matrix : matrix.slice(0, 5)
  const visibleCols = showAll ? 7 : 5

  return (
    <div className="relative rounded-lg border border-[--color-border] bg-[#0a1e24]/60 p-4">
      <div className="flex items-center justify-between mb-3">
        <div className="flex items-center gap-2">
          <H3 className="text-lg font-semibold text-white">{market.name}</H3>
          <div className="group relative">
            <button
              type="button"
              className="text-gray-400 hover:text-white transition-colors"
            >
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
              </svg>
            </button>
            <div className="absolute left-0 top-6 z-10 hidden group-hover:block w-64 p-2 bg-[#0a1e24] border border-[--color-border] rounded-lg text-sm text-gray-300 shadow-lg">
              {market.description}
            </div>
          </div>
        </div>
        <div className="relative">
          <button
            type="button"
            className="flex items-center gap-1 text-sm text-gray-400 hover:text-white transition-colors"
            onClick={() => setShowStats((prev) => !prev)}
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            View stats
          </button>
          {showStats && (
            <CurrentStatsPopover marketId={market.id} onClose={() => setShowStats(false)} />
          )}
        </div>
      </div>

      {market.description && (
        <p className="lg:hidden text-sm text-gray-400 mb-3">{market.description}</p>
      )}

      <div className={cn(
        "gap-2",
        showAll ? "grid grid-cols-7" : "grid grid-cols-5"
      )}>
        {visibleRows.map((row, rowIdx) =>
          row.slice(0, visibleCols).map((outcome, colIdx) =>
            outcome ? (
              <MarketOutcomeButton
                key={outcome.id}
                outcome={outcome}
                isSelected={selectedOutcomeId === outcome.id}
                isLoading={isPending && selectedOutcomeId === outcome.id}
                isDisabled={isDisabled || (isPending && selectedOutcomeId !== outcome.id)}
                onClick={() => onSelectOutcome(outcome.id)}
              />
            ) : (
              <div key={`empty-${rowIdx}-${colIdx}`} />
            )
          )
        )}
      </div>

      {market.outcomes.length > 25 && (
        <button
          type="button"
          onClick={() => setShowAll(!showAll)}
          className="mt-3 w-full py-4 text-base font-semibold text-gray-300 hover:text-white transition-colors border border-[--color-border] rounded-lg hover:bg-[--color-border]/20"
        >
          {showAll ? 'Show less' : `+ ${market.outcomes.length - 25} more`}
        </button>
      )}
    </div>
  )
}
