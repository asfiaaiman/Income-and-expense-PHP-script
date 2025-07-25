<script setup lang="ts">
import { provide, ref, type Ref } from 'vue'

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

// Provide toast context to child components
provide('toasts', toasts)
provide('addToast', addToast)
provide('removeToast', removeToast)
provide('clearToasts', clearToasts)

// Expose methods for global use
defineExpose({
  addToast,
  removeToast,
  clearToasts
})
</script>

<template>
  <slot />
</template>
