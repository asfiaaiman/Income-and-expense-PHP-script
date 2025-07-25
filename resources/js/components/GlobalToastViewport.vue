<script setup lang="ts">
import { useGlobalToast } from '@/composables/useGlobalToast'
import { cn } from '@/lib/utils'

const { toasts, removeToast } = useGlobalToast()

const getVariantClasses = (variant: string) => {
  switch (variant) {
    case 'destructive':
      return 'border-red-500 bg-red-50 text-red-900 dark:bg-red-900/20 dark:text-red-100'
    case 'success':
      return 'border-green-500 bg-green-50 text-green-900 dark:bg-green-900/20 dark:text-green-100'
    case 'warning':
      return 'border-yellow-500 bg-yellow-50 text-yellow-900 dark:bg-yellow-900/20 dark:text-yellow-100'
    case 'info':
      return 'border-blue-500 bg-blue-50 text-blue-900 dark:bg-blue-900/20 dark:text-blue-100'
    default:
      return 'border-gray-200 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100'
  }
}

const getIcon = (variant: string) => {
  switch (variant) {
    case 'destructive':
      return 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'
    case 'success':
      return 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    case 'warning':
      return 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'
    case 'info':
      return 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z'
    default:
      return 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  }
}
</script>

<template>
  <div
    class="fixed top-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]"
  >
    <TransitionGroup
      name="toast"
      tag="div"
      class="flex flex-col gap-2"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="cn(
          'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all',
          getVariantClasses(toast.variant || 'default')
        )"
      >
        <div class="flex items-center gap-3">
          <svg
            v-if="toast.variant"
            class="h-5 w-5 flex-shrink-0"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path :d="getIcon(toast.variant)" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <div class="flex flex-col gap-1">
            <div v-if="toast.title" class="text-sm font-semibold">
              {{ toast.title }}
            </div>
            <div v-if="toast.description" class="text-sm opacity-90">
              {{ toast.description }}
            </div>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <button
            @click="removeToast(toast.id)"
            class="rounded-md p-1 text-foreground/50 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-none focus:ring-2 group-hover:opacity-100"
          >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.toast-move {
  transition: transform 0.3s ease;
}
</style>
