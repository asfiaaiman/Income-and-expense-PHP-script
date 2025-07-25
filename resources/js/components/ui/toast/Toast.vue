<script setup lang="ts">
import { cn } from '@/lib/utils'

interface Props {
  variant?: 'default' | 'destructive' | 'success' | 'warning' | 'info'
  className?: string
}

const props = defineProps<Props>()
</script>

<template>
  <div :class="cn('group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all', props.className)">
    <slot />
  </div>
</template>
