import { inject, ref, type Ref } from 'vue'

export interface Toast {
  id: string
  title?: string
  description?: string
  action?: {
    label: string
    onClick: () => void
  }
  variant?: 'default' | 'destructive' | 'success' | 'warning' | 'info'
  duration?: number
}

export function useToast() {
  const toasts = inject<Ref<Toast[]>>('toasts', ref([]))
  const addToast = inject<(toast: Omit<Toast, 'id'>) => string>('addToast', () => '')
  const removeToast = inject<(id: string) => void>('removeToast', () => {})
  const clearToasts = inject<() => void>('clearToasts', () => {})

  const toast = {
    success: (title: string, description?: string, options?: Partial<Toast>) => {
      return addToast({
        title,
        description,
        variant: 'success',
        ...options
      })
    },
    error: (title: string, description?: string, options?: Partial<Toast>) => {
      return addToast({
        title,
        description,
        variant: 'destructive',
        ...options
      })
    },
    warning: (title: string, description?: string, options?: Partial<Toast>) => {
      return addToast({
        title,
        description,
        variant: 'warning',
        ...options
      })
    },
    info: (title: string, description?: string, options?: Partial<Toast>) => {
      return addToast({
        title,
        description,
        variant: 'info',
        ...options
      })
    },
    default: (title: string, description?: string, options?: Partial<Toast>) => {
      return addToast({
        title,
        description,
        variant: 'default',
        ...options
      })
    }
  }

  return {
    toasts,
    toast,
    removeToast,
    clearToasts
  }
}
