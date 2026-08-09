import { useState } from 'react'
import { cn } from '@/lib/utils'
import type { Market, Outcome } from '../types'
import { MarketOutcomeButton } from './MarketOutcomeButton'
import { ViewStatsButton, ShowMoreButton, DescriptionTooltip } from '@/components/ui/event/buttons'
import { CurrentStatsPopover } from './CurrentStatsPopover'
import {H3} from '@/components/ui/common'

interface MarketScoreCardProps {
  market: Market
  selectedOutcomeIds: number[]
  onSelectOutcome: (outcomeId: number) => void
  isPending?: boolean
  isDisabled?: boolean
}

function toMatrix7x7(outcomes: Outcome[]): (Outcome | null)[][] {
  const matrix: (Outcome | null)[][] = []
  for (let row = 0; row < 7; row++) {
    const start = row * 7
    const slice: (Outcome | null)[] = outcomes.slice(start, start + 7)
    while (slice.length < 7) slice.push(null)
    matrix.push(slice)
  }
  return matrix
}

export function MarketScoreCard({ market, selectedOutcomeIds, onSelectOutcome, isPending, isDisabled }: MarketScoreCardProps) {
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
          <DescriptionTooltip description={market.description} />
        </div>
        <div className="relative">
          <ViewStatsButton
            showStats={showStats}
            onClick={() => setShowStats((prev) => !prev)}
          />
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
                isSelected={selectedOutcomeIds.includes(outcome.id)}
                isLoading={isPending && selectedOutcomeIds.includes(outcome.id)}
                isDisabled={isDisabled || (isPending && !selectedOutcomeIds.includes(outcome.id))}
                onClick={() => onSelectOutcome(outcome.id)}
              />
            ) : (
              <div key={`empty-${rowIdx}-${colIdx}`} />
            )
          )
        )}
      </div>

      {market.outcomes.length > 25 && (
       <ShowMoreButton
          showAll={showAll}
          onClick={() => setShowAll(!showAll)}
          remainingCount={market.outcomes.length - 25}
        />
      )}
    </div>
  )
}
