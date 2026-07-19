import { cn } from '@/lib/utils'
import type { Outcome } from '../types'

interface MarketOutcomeButtonProps {
  outcome: Outcome
  isSelected: boolean
  isLoading?: boolean
  isDisabled?: boolean
  onClick: () => void
}

export function MarketOutcomeButton({ outcome, isSelected, isLoading, isDisabled, onClick }: MarketOutcomeButtonProps) {
  return (
    <button
      type="button"
      onClick={onClick}
      disabled={isLoading || isDisabled}
      className={cn(
        "flex items-center justify-between px-4 py-3 rounded-lg border transition-all duration-200",
        "hover:bg-[val(--color-border)]/30",
        isSelected
          ? "border-accent bg-accent/20 text-white"
          : "border-[val(--color-border)] bg-[#0a1e24]/40 text-gray-300 hover:border-gray-500",
        isLoading && "opacity-70 cursor-wait",
        isDisabled && "opacity-50 cursor-not-allowed"
      )}
    >
      <span className="flex items-center gap-2">
        {isLoading ? (
          <span className="h-3 w-3 animate-spin rounded-full border-2 border-accent border-t-transparent" />
        ) : (
          <span className={cn(
            "w-3 h-3 rounded-full border-2",
            isSelected ? "border-accent bg-accent" : "border-gray-500"
          )} />
        )}
        <span className="text-sm">{outcome.name}</span>
      </span>
      <span className="hidden text-sm font-medium text-accent">{outcome.coef}</span>
    </button>
  )
}
