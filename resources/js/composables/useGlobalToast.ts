import { ref } from 'vue'

export interface Toast {
  id: string
  title?: string
  description?: string
  variant?: 'default' | 'destructive' | 'success' | 'warning' | 'info'
  duration?: number
}

const toasts = ref<Toast[]>([])

const addToast = (toast: Omit<Toast, 'id'>) => {
  const id = Math.random().toString(36).substr(2, 9)
  const newToast = { ...toast, id }

  toasts.value.push(newToast)

  // Auto remove toast after duration
  const duration = toast.duration ?? 5000
  if (duration > 0) {
    setTimeout(() => {
      removeToast(id)
    }, duration)
  }

  return id
}

const removeToast = (id: string) => {
  const index = toasts.value.findIndex(toast => toast.id === id)
  if (index > -1) {
    toasts.value.splice(index, 1)
  }
}

const clearToasts = () => {
  toasts.value = []
}

export function useGlobalToast() {
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
