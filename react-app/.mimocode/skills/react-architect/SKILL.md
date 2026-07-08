---
name: react-architect
description: "Senior React developer guidance for the Predictorium project. Enforces Clean Architecture with feature-based folder structure, TanStack Query for data fetching, Zustand for client state, Tailwind CSS styling, and strict TypeScript. Use when creating new React components, features, pages, hooks, or refactoring existing React code. Trigger on: create component, add feature, new page, refactor, build feature, implement, create hook, add to project."
triggers: [react architect, react developer, feature-based architecture]
---

# React Architect — Predictorium

## Core Principles

1. **Feature-based organization** — all domain logic lives in `src/features/`.
   Each feature is self-contained with its own hooks, components, API services,
   types, and a public `index.ts`.

2. **Separation of concerns** — Logic (hooks/services) handles data fetching
   and state. View components are pure UI receiving data via props. Components
   never import API endpoints or stores directly.

3. **Composition over configuration** — small, atomic, reusable components.
   No god components. Break down anything growing too large.

4. **Tailwind CSS only** — no inline styles, no CSS modules, no CSS-in-JS.

5. **Strict TypeScript** — never use `any`. Define interfaces for all API
   responses and component props.

## Folder Structure

```
src/
├── app/          # Providers, router, global styles, entry point
├── pages/        # Page-level components (route targets)
├── features/     # Domain-specific modules
│   └── example/
│       ├── api/       # Axios calls for the feature
│       ├── components/# Feature-specific UI
│       ├── hooks/     # Feature-specific logic hooks
│       ├── types/     # Feature-specific types
│       └── index.ts   # Public API (only what's needed)
├── shared/       # Reusable UI kit, utilities, shared types
```

## When Creating a New Feature

1. Create the feature directory under `src/features/<feature-name>/`.
2. Add `api/`, `components/`, `hooks/`, `types/` subdirectories as needed.
3. Create `index.ts` as the public API — export only what other features/pages
   need. Internal helpers stay unexported.
4. Pages import from `features/<name>` (the index), never from internal files.
5. Features never import from other features' internal files.

## When Creating Components

- Props must have a named TypeScript interface (e.g., `ButtonProps`).
- Default-export the component, named-export the props type.
- Use Tailwind utility classes. Avoid inline `style={}`.
- Keep components small. If a component has 3+ conditional rendering paths,
  extract sub-components.
- Always handle loading and error states for async data.

## When Creating Hooks

- Custom hooks go in the feature's `hooks/` directory.
- Use TanStack Query (`useQuery`, `useMutation`) for server state.
- Use Zustand only for client-side state that can't be URL params or React Query.
- Name hooks `use<Feature><Action>` (e.g., `useAuthLogin`).

## When Creating Pages

- Pages go in `src/pages/<PageName>/`.
- Pages compose features into route-level views.
- Pages are thin — they wire features together, not implement logic.

## Dependency Rules

Pages → features → shared. Never the reverse. No circular dependencies.

## Reference

For the full folder structure template and detailed guidelines, see
`references/architecture.md`.
