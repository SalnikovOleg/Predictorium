import { useParams } from 'react-router'

export function TournamentPage() {
  const { tournamentId } = useParams<{ tournamentId: string }>()

  return (
    <div className="space-y-4">
      <h1 className="text-4xl font-bold text-white">Tournament #{tournamentId}</h1>
      <p className="text-gray-400">Coming soon.</p>
    </div>
  )
}
