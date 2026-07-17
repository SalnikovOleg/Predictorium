import { useParams } from 'react-router'
import { useCategory } from '@/features/category'
import { TournamentCard } from '@/features/tournament'
import { LoadingSpinner } from '@/components/ui/LoadingSpinner'
import { ErrorMessage } from '@/components/ui/ErrorMessage'

export function CategoryPage() {
  const { slug } = useParams<{ slug: string }>()
  const { data, isLoading, error } = useCategory(slug!)

  if (isLoading) {
    return <LoadingSpinner />
  }

  if (error) {
    return <ErrorMessage message={error.message} />
  }

  const category = data?.data

  if (!category) {
    return <ErrorMessage message="Category not found" />
  }

  const subtitle = category.contents[0]?.title

  return (
    <div className="space-y-4">
      <h1 className="text-4xl font-bold">
        {category.name}{subtitle ? `. ${subtitle}` : ''}
      </h1>
      <div className="grid gap-8 lg:grid-cols-[1fr_320px]">
        <div className="space-y-4">
          {category.tournaments.length === 0 ? (
            <p className="text-muted-foreground">No tournaments available.</p>
          ) : (
            <div className="space-y-6">
              {category.tournaments.map((tournament) => (
                <TournamentCard key={tournament.id} tournament={tournament} />
              ))}
            </div>
          )}
        </div>

        {(category.icon || category.contents.length > 0) && (
          <aside className="space-y-6">
            {category.icon && (
              <img
                src={category.icon}
                alt={category.name}
                className="w-full rounded-lg object-contain"
              />
            )}
            {category.contents.map((item) => (
              <div key={item.title} className="space-y-2">
                <div
                  className="text-sm text-muted-foreground prose prose-sm dark:prose-invert max-w-none"
                  dangerouslySetInnerHTML={{ __html: item.content }}
                />
              </div>
            ))}
          </aside>
        )}
      </div>
    </div>
  )
}
