import { cn } from '@/lib/utils'

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  children?: React.ReactNode
  loading?: boolean
  loadingText?: string
  fullWidth?: boolean
}

export function ButtonPrimary({
  children,
  loading,
  loadingText,
  fullWidth,
  className,
  disabled,
  ...props
}: ButtonProps) {
  return (
    <button
      type="submit"
      disabled={disabled || loading}
      className={cn(
        'rounded bg-teal-500 py-2.5 font-bold text-[#0a1e24] transition-colors hover:bg-teal-400 disabled:opacity-50',
        fullWidth && 'w-full',
        className,
      )}
      {...props}
    >
      {loading ? (loadingText ?? 'Loading...') : children}
    </button>
  )
}

export function ButtonSecondary({
  children,
  fullWidth,
  className,
  ...props
}: ButtonProps) {
  return (
    <button
      type="button"
      className={cn(
        'rounded border border-white/20 py-2.5 font-bold text-gray-300 transition-colors hover:bg-white/10',
        fullWidth && 'w-full',
        className,
      )}
      {...props}
    >
      {children}
    </button>
  )
}

export function ButtonLogout({
  children = 'Logout',
  fullWidth,
  className,
  ...props
}: ButtonProps) {
  return (
    <button
      type="button"
      className={cn(
        'w-full rounded border border-red-500/50 bg-red-500/10 py-2.5 font-bold text-red-400 transition-colors hover:bg-red-500/20',
        fullWidth && 'w-full',
        className,
      )}
      {...props}
    >
      {children}
    </button>
  )
}
