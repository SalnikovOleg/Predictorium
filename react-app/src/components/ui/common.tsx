import { cn } from "@/lib/utils";

export function Background() {
    return (
    <div
        className="fixed inset-0 bg-cover bg-center bg-no-repeat"
        style={{ backgroundImage: "url('/background.png')" }}
    />
    )
}

interface HeaderProps extends React.ComponentProps<"header"> {
  children?: React.ReactNode;
}

export function Header({ className, children }: HeaderProps) {
  return (
    <header
      className={cn("border-b border-[--color-border] bg-[#0a1e24]/80 backdrop-blur-sm",className)}
    >
      {children}
    </header>
  )
}

interface ComponentProps {
  children:  React.ReactNode
  className?: string
}

export function H1({children, className }: ComponentProps) {
  return (
    <div className={cn("text-sm font-bold text-accent uppercase", className)}>
        <span className="inline-block scale-x-200 mr-4">// </span>
        <span className="tracking-[0.2em] ">{children}</span>
        <span className="inline-block scale-x-200 origin-left ml-2">// </span>
    </div>
  )
}

export function H2({ children, className }: ComponentProps) {
  return (
    <h2 className={cn("text-4xl lg:text-6xl font-bold text-white font-serif tracking-[0.1em]", className)}>
      {children}
    </h2>
  )
}

export function H3({ children, className }: ComponentProps) {
  return (
    <h3 className={cn("text-lg font-semibold text-white font-serif tracking-[0.1em]", className)}>
      {children}
    </h3>
  )
}

export function Description({ children, className }: ComponentProps) {
  return (
    <p className={cn("mt-1 text-lg lg:text-sm  line-clamp-2", className)}>
      {children}
    </p>
  )
}

interface ErrorMessageProps {
  message?: string
}

export function ErrorMessage({ message = 'Something went wrong' }: ErrorMessageProps) {
  return (
    <div className="rounded-lg border border-red-300 bg-red-50 p-4 text-red-800 dark:border-red-700 dark:bg-red-950 dark:text-red-200">
      <p className="font-medium">Error</p>
      <p className="text-sm">{message}</p>
    </div>
  )
}

export function LoadingSpinner() {
  return (
    <div className="flex items-center justify-center p-8">
      <div className="h-8 w-8 animate-spin rounded-full border-4 border-gray-300 border-t-blue-600" />
    </div>
  )
}

export function TournamentHr() {
  return (
    <div
      className="h-12 w-full bg-contain bg-center bg-no-repeat"
      style={{ backgroundImage: "url('/assets/tournamnet_hr.png')" }}
      role="separator"
    />
  )
}