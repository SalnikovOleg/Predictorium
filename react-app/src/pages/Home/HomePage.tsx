import { useHome } from '../../features/home'
import { LoadingSpinner } from '@/components/ui/LoadingSpinner'
import { ErrorMessage } from '@/components/ui/ErrorMessage'

export function HomePage() {
  const { data, isLoading, error } = useHome()

  if (isLoading) return <LoadingSpinner />
  if (error) return <ErrorMessage message={error.message} />

  const contents = data?.data.contents ?? []

  return (
    <div className="space-y-8">
      {contents.map((item) => (
        <article key={item.id} className="space-y-4">
          <h1 className="text-4xl font-bold text-white">{item.title}</h1>
          <div
            className="prose prose-invert max-w-none text-gray-300"
            dangerouslySetInnerHTML={{ __html: item.content }}
          />
        </article>
      ))}
      {contents.length === 0 && (
        <p className="text-gray-400">No content available.</p>
      )}
    </div>
  )
}
