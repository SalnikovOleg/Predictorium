import { BrowserRouter, Routes, Route } from 'react-router'
import { Layout } from './shared/ui/Layout'
import { HomePage } from './pages/Home/HomePage'
import { WhatToPlayPage } from './pages/WhatToPlay/WhatToPlayPage'
import { CategoryPage } from './pages/CategoryPage/CategoryPage'
import { TournamentPage } from './pages/TournamentPage/TournamentPage'
import { EventPage } from './pages/EventPage/EventPage'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<HomePage />} />
          <Route path="/what-to-play/" element={<WhatToPlayPage />} />
          <Route path="/categories/:slug/" element={<CategoryPage />} />
          <Route path="/tournaments/:tournamentId/" element={<TournamentPage />} />
          <Route path="/events/:eventId/" element={<EventPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  )
}

export default App
