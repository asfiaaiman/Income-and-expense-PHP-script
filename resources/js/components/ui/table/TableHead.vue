<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <th
    data-slot="table-head"
    :class="cn('text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400', props.class)"
  >
    <slot />
  </th>
</template>
