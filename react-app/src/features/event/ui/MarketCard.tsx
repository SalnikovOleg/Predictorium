import { useState } from 'react'
import { cn } from '@/lib/utils'
import type { Market } from '../types'
import { MarketOutcomeButton } from './MarketOutcomeButton'
import { ViewStatsButton, ShowMoreButton, DescriptionTooltip } from '@/components/ui/event/buttons'
import { CurrentStatsPopover } from './CurrentStatsPopover'
import {H3} from '@/components/ui/common'

interface MarketCardProps {
  market: Market
  selectedOutcomeIds: number[]
  onSelectOutcome: (outcomeId: number) => void
  isPending?: boolean
  isDisabled?: boolean
}

export function MarketCard({ market, selectedOutcomeIds, onSelectOutcome, isPending, isDisabled }: MarketCardProps) {
  const [showAll, setShowAll] = useState(false)
  const [showStats, setShowStats] = useState(false)
  const isMultiOutcome = market.market_type_id === 3 && market.outcomes.length > 2
  const visibleOutcomes = isMultiOutcome && !showAll
    ? market.outcomes.slice(0, 8)
    : market.outcomes

  return (
    <div data-id={market.id} className="relative rounded-lg border border-[--color-border] bg-[#0a1e24]/60 p-4">
      <div className="flex items-center justify-between mb-3">
        <div className="flex items-center gap-2">
          <H3 className="text-lg">{market.name}</H3>
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
        isMultiOutcome
          ? "grid grid-cols-2 md:grid-cols-4"
          : "grid grid-cols-2"
      )}>
        {visibleOutcomes.map((outcome) => (
          <MarketOutcomeButton
            key={outcome.id}
            outcome={outcome}
            isSelected={selectedOutcomeIds.includes(outcome.id)}
            isLoading={isPending && selectedOutcomeIds.includes(outcome.id)}
            isDisabled={isDisabled || (isPending && !selectedOutcomeIds.includes(outcome.id))}
            onClick={() => onSelectOutcome(outcome.id)}
          />
        ))}
      </div>

      {isMultiOutcome && market.outcomes.length > 8 && (
        <ShowMoreButton
          showAll={showAll}
          onClick={() => setShowAll(!showAll)}
          remainingCount={market.outcomes.length - 8}
        />
      )}
    </div>
  )
}
