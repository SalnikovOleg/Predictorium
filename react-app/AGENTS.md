# AGENTS.md — React Frontend

React 19 + Vite 8 + TypeScript 6 SPA for Predictorium. Currently a Vite starter — no routing or state management yet.

## Commands

```bash
npm run dev       # Vite dev server on :3000 (proxies /api → nginx:80, Docker only)
npm run build     # tsc -b && vite build → build/
npm run lint      # eslint
```

## API Integration

All backend calls go through `/api/*`. The Vite proxy in `vite.config.ts` forwards to `http://nginx:80` — **this only works inside Docker**. Outside Docker, the proxy target needs to change to `http://localhost:80`.

Endpoints are documented in `API.md`. Key routes: `/api/ping`, `/api/home`, `/api/main-menu`, `/api/categories/{slug}`, `/api/tournaments/{id}`, `/api/events/{id}`.

## TypeScript

- `tsconfig.app.json`: `noUnusedLocals`, `noUnusedParameters`, `erasableSyntaxOnly`, `verbatimModuleSyntax` — all enforced
- `tsconfig.node.json`: covers `vite.config.ts` only
- Target: ES2023, JSX: react-jsx

## Styling

Tailwindcss. The UI Architecture and Design Rule in MEMORY

## Docker

Container runs Node 24 Alpine, mounts source as volume for HMR. Build output (`build/`) is mounted into nginx for static serving — run `npm run build` after changes.
