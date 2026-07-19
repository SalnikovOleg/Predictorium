import { useParams } from 'react-router'
import { useCategory } from '@/features/category'
import { TournamentCard } from '@/features/tournament'
import { H1, H2, H3, LoadingSpinner, ErrorMessage, TournamentHr } from '@/components/ui/common'
import { CategoryAsideBlock } from '@/components/ui/blocks'

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
  const content = category.contents[0]?.content
  
  return (
    <div className="space-y-6">
      <H1>{category.name}</H1>

      <div className="grid gap-8 lg:grid-cols-[1fr_340px]">
        <div className="space-y-4">

          {subtitle && <H2>{subtitle}</H2>}

          <TournamentHr/>
          
          {category.tournaments.length === 0 ? (
            <H3>No tournaments available.</H3>
            ) : (
            <div className="space-y-4">
              {category.tournaments.map((tournament) => (
                <TournamentCard key={tournament.id} tournament={tournament} />
              ))}
            </div>
          )}
        </div>

        {(category.icon || category.contents.length > 0) && (
          <aside>
            <CategoryAsideBlock
                content={content}
              >
              <img
                src={category.icon}
                alt={category.name}
                className="w-full rounded-2xl object-contain "
              />
            </CategoryAsideBlock> 
          </aside>
        )}
      </div>
    </div>
  )
}
