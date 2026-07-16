import { BrowserRouter, Routes, Route } from 'react-router'
import { Layout } from './shared/ui/Layout'
import { HomePage } from './pages/Home/HomePage'
import { CategoryPage } from './pages/Category/CategoryPage'
import { TournamentPage } from './pages/Tournament/TournamentPage'
import { EventPage } from './pages/Event/EventPage'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<HomePage />} />
          <Route path="/categories/:slug/" element={<CategoryPage />} />
          <Route path="/tournaments/:tournamentId/" element={<TournamentPage />} />
          <Route path="/events/:eventId/" element={<EventPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  )
}

export default App
