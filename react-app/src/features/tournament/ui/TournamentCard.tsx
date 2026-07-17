import { Link } from 'react-router'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card'
import type { TournamentSummary } from '../types'

export function TournamentCard({ tournament }: { tournament: TournamentSummary }) {
  return (
    <Link to={`/tournaments/${tournament.id}/`} className="block">
      <Card className="transition-colors hover:bg-muted/50">
        <div className="flex items-stretch mx-4 gap-2">
          {tournament.icon && (
            <img
              src={tournament.icon}
              alt={tournament.name}
              className="h-full w-32 shrink-0 rounded object-cover"
            />
          )}
          <div className="flex-1 min-w-0">
            <CardHeader>
              <CardTitle>{tournament.name}</CardTitle>
              <CardDescription>{tournament.description}</CardDescription>
            </CardHeader>
            <CardContent>
              <span className="text-sm text-muted-foreground">
                {tournament.start_date} – {tournament.end_date}
              </span>
            </CardContent>
          </div>
        </div>
      </Card>
    </Link>
  )
}
