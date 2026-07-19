import { useState } from 'react'
import { cn } from '@/lib/utils'
import type { Market } from '@/shared/types'
import { MarketOutcomeButton } from './MarketOutcomeButton'

interface MarketCardProps {
  market: Market
  selectedOutcomeId: number | null
  onSelectOutcome: (outcomeId: number) => void
}

export function MarketCard({ market, selectedOutcomeId, onSelectOutcome }: MarketCardProps) {
  const [showAll, setShowAll] = useState(false)
  const isMultiOutcome = market.market_type_id === 3
  const visibleOutcomes = isMultiOutcome && !showAll ? market.outcomes.slice(0, 8) : market.outcomes

  return (
    <div className="rounded-lg border border-[--color-border] bg-[#0a1e24]/60 p-4">
      <div className="flex items-center justify-between mb-3">
        <div className="flex items-center gap-2">
          <h3 className="text-lg font-semibold text-white">{market.name}</h3>
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
        <button
          type="button"
          className="flex items-center gap-1 text-sm text-gray-400 hover:text-white transition-colors"
          onClick={() => { console.log('get_stat()'); }}
        >
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          View stats
        </button>
      </div>

      {market.description && (
        <p className="lg:hidden text-sm text-gray-400 mb-3">{market.description}</p>
      )}

      <div className={cn(
        "grid gap-2",
        isMultiOutcome ? "grid-cols-2 md:grid-cols-4" : "grid-cols-2"
      )}>
        {visibleOutcomes.map((outcome) => (
          <MarketOutcomeButton
            key={outcome.id}
            outcome={outcome}
            isSelected={selectedOutcomeId === outcome.id}
            onClick={() => onSelectOutcome(outcome.id)}
          />
        ))}
      </div>

      {isMultiOutcome && market.outcomes.length > 8 && (
        <button
          type="button"
          onClick={() => setShowAll(!showAll)}
          className="mt-3 w-full py-2 text-sm text-gray-400 hover:text-white transition-colors border border-[--color-border] rounded-lg hover:bg-[--color-border]/20"
        >
          {showAll ? 'Show less' : `+ ${market.outcomes.length - 8} more`}
        </button>
      )}
    </div>
  )
}
