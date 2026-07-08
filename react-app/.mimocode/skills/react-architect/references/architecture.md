# Architecture Reference — Predictorium React

## Full Folder Structure Template

```
src/
├── app/
│   ├── providers/       # Context providers (QueryClient, Theme, Auth)
│   ├── router/          # Route definitions
│   ├── styles/          # Global styles, Tailwind config
│   └── App.tsx          # Root component
├── pages/
│   ├── Home/
│   │   └── index.tsx
│   └── Dashboard/
│       └── index.tsx
├── features/
│   └── auth/
│       ├── api/
│       │   └── auth.ts          # login, register, logout API calls
│       ├── components/
│       │   ├── LoginForm.tsx
│       │   └── RegisterForm.tsx
│       ├── hooks/
│       │   ├── useAuth.ts       # TanStack Query hook
│       │   └── useAuthStore.ts  # Zustand store (if needed)
│       ├── types/
│       │   └── index.ts         # User, LoginRequest, LoginResponse
│       └── index.ts             # Public: useAuth, LoginForm, etc.
├── shared/
│   ├── ui/                      # Button, Input, Modal, etc.
│   ├── utils/                   # formatDate, cn, etc.
│   └── types/                   # Shared types (ApiResponse, Paginated)
└── main.tsx
```

## Tech Stack

| Concern             | Tool           |
|---------------------|----------------|
| Language            | TypeScript (strict) |
| Framework           | React 19       |
| Data Fetching       | TanStack Query + Axios |
| Client State        | Zustand        |
| Styling             | Tailwind CSS   |
| Build               | Vite 8         |

## Axios Client

Create a centralized Axios instance in `src/shared/api/client.ts`:

```ts
import axios from 'axios'

export const apiClient = axios.create({
  baseURL: '/api',
  headers: { 'Content-Type': 'application/json' },
})
```

Features import `apiClient` and wrap calls in TanStack Query hooks.

## Zustand Store Pattern

Only for client state that isn't derivable from URL or server state:

```ts
import { create } from 'zustand'

interface SidebarState {
  isOpen: boolean
  toggle: () => void
}

export const useSidebarStore = create<SidebarState>((set) => ({
  isOpen: false,
  toggle: () => set((s) => ({ isOpen: !s.isOpen })),
}))
```

## Error Handling Pattern

Every async component must render loading, error, and empty states:

```tsx
function FeatureWidget() {
  const { data, isLoading, error } = useFeatureQuery()

  if (isLoading) return <Skeleton />
  if (error) return <ErrorMessage error={error} />
  if (!data) return <EmptyState />

  return <DataView data={data} />
}
```
