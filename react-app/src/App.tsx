import { BrowserRouter, Routes, Route } from 'react-router'
import { Layout } from '@/pages/Layout'
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
          <Route path="/tournaments/:slug/" element={<TournamentPage />} />
          <Route path="/events/:slug/" element={<EventPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  )
}

export default App
