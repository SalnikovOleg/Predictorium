import { Link } from 'react-router'
import type { EventSummary } from '@/shared/types'
import { Card } from '@/components/ui/card'
import { H3 } from '@/components/ui/common'
import { DateLine } from '@/components/ui/date'
import { EventIcon } from '@/components/ui/icons'

interface EventCardProps {
  event: EventSummary
}

export function EventCard({ event }: EventCardProps) {
  return (
    <Link to={`/events/${event.slug}/`} className="block">
      <Card className="flex items-center gap-4 p-4">
        <div className="flex-1 min-w-0">
          <H3>{event.name}</H3>
          <DateLine date={event.start_date} label="start"/>
      
          {event.status === 'finished' && event.end_date && (
            <DateLine date={event.end_date} label="finish"/>
          )}
        </div>

        <EventIcon status={event.status}/>
      </Card>
    </Link>
  )
}
