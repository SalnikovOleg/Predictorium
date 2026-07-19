import { Link } from 'react-router'
import type { TournamentSummary } from '../types'
import { Card } from '@/components/ui/card'
import { H3, Description } from '@/components/ui/common'
import { PeriodLine } from '@/components/ui/date'
import {TournamentIcon} from '@/components/ui/icons'

export function TournamentCard({ tournament }: { tournament: TournamentSummary }) {
  return (
     <Link to={`/tournaments/${tournament.slug}/`} className="block">
      <Card className="flex items-center gap-4">
        {tournament.icon ? (
          <img
            src={tournament.icon}
            alt={tournament.name}
            className="h-full w-44 shrink-0 rounded-lg object-cover ml-1"
          />
        ) : (
          <div className="h-full w-44 shrink-0 rounded-lg bg-[--color-secondary] flex items-center justify-center text-[--color-accent] text-2xl">
            &#x2655;
          </div>
        )}

        <div className="flex-1 space-y-4">
          <H3>{tournament.name}</H3>

          {tournament.description && (
            <Description>{tournament.description}</Description>
          )}

          <PeriodLine start_date={tournament.start_date} end_date={tournament.end_date}/>

        </div>

        <TournamentIcon />
      </Card>  
    </Link>
  )
}
