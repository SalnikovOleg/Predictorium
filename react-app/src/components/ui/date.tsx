import { cn } from "@/lib/utils";

interface DateProps{
  label?: string
  date: string
  className?: string
}

export function DateLine({label, date, className }: DateProps) {
  const labels: Record<string, string> = {'':'📆', 'start': '🚩', 'finish': '🏁'}
  const icon = labels[label ?? ''] ?? '📆'

  return (
    <p className={cn("mt-2 text-xs text-accent", className)}>
       {icon}  {date}
    </p>
  )
}

interface PeriodProps{
  start_date: string
  end_date: string
  className?: string
}

export function PeriodLine({ start_date, end_date, className }: PeriodProps) {
  return (
    <p className={cn("mt-2 text-xs text-accent", className)}>
       &#x1F4C5; {start_date} &ndash; {end_date}
    </p>
  )
}
