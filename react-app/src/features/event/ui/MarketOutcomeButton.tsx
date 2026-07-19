import { cn } from '@/lib/utils'
import type { Outcome } from '@/shared/types'

interface MarketOutcomeButtonProps {
  outcome: Outcome
  isSelected: boolean
  onClick: () => void
}

export function MarketOutcomeButton({ outcome, isSelected, onClick }: MarketOutcomeButtonProps) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={cn(
        "flex items-center justify-between px-4 py-3 rounded-lg border transition-all duration-200",
        "hover:bg-[--color-border]/30",
        isSelected
          ? "border-accent bg-accent/20 text-white"
          : "border-[--color-border] bg-[#0a1e24]/40 text-gray-300 hover:border-gray-500"
      )}
    >
      <span className="flex items-center gap-2">
        <span className={cn(
          "w-3 h-3 rounded-full border-2",
          isSelected ? "border-accent bg-accent" : "border-gray-500"
        )} />
        <span className="text-sm">{outcome.name}</span>
      </span>
      <span className="hidden text-sm font-medium text-accent">{outcome.coef}</span>
    </button>
  )
}
