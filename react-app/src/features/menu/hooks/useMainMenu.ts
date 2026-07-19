import { useQuery } from '@tanstack/react-query'
import { fetchMainMenu } from '../api/menu'
import type { MenuItem } from '../types'

function normalizeMenuItems(items: MenuItem[]): MenuItem[] {
  return items.map((item) => ({
    ...item,
    url:  item.url === null ? '/' : item.url.startsWith('/') ? item.url : `/${item.url}`,
    children: normalizeMenuItems(item.children),
  }))
}

export function useMainMenu() {
  const response = useQuery({
    queryKey: ['main-menu'],
    queryFn: fetchMainMenu,
    staleTime: 5 * 60 * 100,
    select: (data) => ({
      ...data,
      data: { items: normalizeMenuItems(data.data.items) },
    }),
  })

  return response;
}
