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
