import { useQuery } from '@tanstack/react-query'
import { fetchMainMenu } from '../api/menu'
import type { MenuItem } from '../../../shared/types'

function normalizeMenuItems(items: MenuItem[]): MenuItem[] {
  return items.map((item) => ({
    ...item,
    url: item.url === 'home' ? '/' : item.url.startsWith('/') ? item.url : `/${item.url}`,
    children: normalizeMenuItems(item.children),
  }))
}

export function useMainMenu() {
  return useQuery({
    queryKey: ['main-menu'],
    queryFn: fetchMainMenu,
    staleTime: 5 * 60 * 1000,
    select: (data) => ({
      ...data,
      data: { items: normalizeMenuItems(data.data.items) },
    }),
  })
}
