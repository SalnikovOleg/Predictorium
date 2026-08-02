import { cn } from "@/lib/utils";

interface DateProps{
  label?: string
  date?: string | null
  className?: string
}

export function DateLine({label, date, className }: DateProps) {
  if (!date) return null

  const labels: Record<string, string> = {'':'📆', 'start': '🚩', 'finish': '🏁'}
  const icon = labels[label ?? ''] ?? '📆'

  return (
    <p className={cn("mt-2 text-xs text-accent", className)}>
       {icon}  {date}
    </p>
  )
}

interface PeriodProps{
  start_date?: string | null
  end_date?: string | null
  className?: string
}

export function PeriodLine({ start_date, end_date, className }: PeriodProps) {
  if (!start_date && !end_date) return null

  return (
    <p className={cn("mt-2 text-xs text-accent", className)}>
       &#x1F4C5; {start_date ?? ''} {start_date && end_date ? ' - ' : ''} {end_date ?? ''}
    </p>
  )
}
