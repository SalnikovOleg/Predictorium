import { useParams } from 'react-router'

export function CategoryPage() {
  const { slug } = useParams<{ slug: string }>()

  return (
    <div className="space-y-4">
      <h1 className="text-4xl font-bold text-white">Category: {slug}</h1>
      <p className="text-gray-400">Coming soon.</p>
    </div>
  )
}
