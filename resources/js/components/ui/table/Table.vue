<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div data-slot="table-container" class="relative w-full overflow-auto">
    <table data-slot="table" :class="cn('w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400', props.class)">
      <slot />
    </table>
  </div>
</template>
