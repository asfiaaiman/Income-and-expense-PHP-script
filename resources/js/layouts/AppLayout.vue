<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import GlobalToastViewport from '@/components/GlobalToastViewport.vue';
import FlashMessage from '@/components/FlashMessage.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <FlashMessage />
        <GlobalToastViewport />
    </AppLayout>
</template>
