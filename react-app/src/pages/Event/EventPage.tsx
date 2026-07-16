import { useParams } from 'react-router'

export function EventPage() {
  const { eventId } = useParams<{ eventId: string }>()

  return (
    <div className="space-y-4">
      <h1 className="text-4xl font-bold text-white">Event #{eventId}</h1>
      <p className="text-gray-400">Coming soon.</p>
    </div>
  )
}
