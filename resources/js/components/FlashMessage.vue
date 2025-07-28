<script setup lang="ts">
import { useGlobalToast } from '@/composables/useGlobalToast'
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'

const page = usePage()
const { toast } = useGlobalToast()

// Watch for flash messages in the page props
watch(
  () => page.props.flash,
  (flash) => {
    if (flash && typeof flash === 'object') {
      Object.entries(flash).forEach(([key, value]) => {
        if (value && typeof value === 'string') {
          switch (key) {
            case 'success':
              toast.success('Success', value)
              break
            case 'error':
              toast.error('Error', value)
              break
            case 'warning':
              toast.warning('Warning', value)
              break
            case 'info':
              toast.info('Info', value)
              break
          }
        }
      })
    }
  },
  { immediate: true, deep: true }
)

// Watch for status messages (used by Laravel auth)
watch(
  () => page.props.status as string | undefined,
  (status) => {
    if (status) {
      toast.success('Success', status)
    }
  },
  { immediate: true }
)

// Watch for error messages
watch(
  () => page.props.errors as any,
  (errors) => {
    if (errors && Object.keys(errors).length > 0) {
      const errorMessages = Object.values(errors).flat()
      errorMessages.forEach((message) => {
        if (typeof message === 'string') {
          toast.error('Error', message)
        }
      })
    }
  },
  { immediate: true, deep: true }
)
</script>

<template>
  <div></div>
</template>
