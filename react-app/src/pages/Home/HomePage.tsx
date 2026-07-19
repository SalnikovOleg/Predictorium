import { useHome } from '../../features/home'
import { H1, ErrorMessage, LoadingSpinner } from '@/components/ui/common'

export function HomePage() {
  const { data, isLoading, error } = useHome()

  if (isLoading) return <LoadingSpinner />
  if (error) return <ErrorMessage message={error.message} />

  const contents = data?.data.contents ?? []

  return (
    <div className="space-y-8">
      {contents.map((item) => (
        <article key={item.id} className="space-y-4">
          <H1>{item.title}</H1>
          <div
            className="prose prose-invert max-w-none text-gray-300"
            dangerouslySetInnerHTML={{ __html: item.content }}
          />
        </article>
      ))}
      {contents.length === 0 && (
        <p className="text-muted-foreground">No content available.</p>
      )}
    </div>
  )
}
