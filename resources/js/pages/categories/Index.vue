<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { useGlobalToast } from '@/composables/useGlobalToast';
import { CategoryType } from '@/types/CategoryType';
import Pagination from '@/components/Pagination.vue';

interface Props {
    categories: {
        data: Array<{
            id: number;
            title: string;
            type: CategoryType;
            parent_id: number | null;
            is_active: boolean;
            parent?: { title: string };
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from?: number;
        to?: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/categories',
    },
];

// Delete confirmation state
const showDeleteDialog = ref(false);
const categoryToDelete = ref<{ id: number; title: string } | null>(null);

// Toast functionality is handled by FlashMessage component

// Open delete confirmation dialog
const openDeleteDialog = (category: { id: number; title: string }) => {
    categoryToDelete.value = category;
    showDeleteDialog.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (categoryToDelete.value) {
        router.delete(route('categories.destroy', { category: categoryToDelete.value.id }));
        showDeleteDialog.value = false;
        categoryToDelete.value = null;
    }
};

// Cancel delete
const cancelDelete = () => {
    showDeleteDialog.value = false;
    categoryToDelete.value = null;
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                </div>
                <Link :href="route('categories.create')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Create Category
                </Link>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Sr#</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Type</th>
                            <th scope="col" class="px-6 py-3">Parent</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(category, index) in categories.data"
                            :key="category.id"
                            class="border-b border-gray-200 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                {{ (categories.current_page - 1) * categories.per_page + index + 1 }}
                            </th>
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                {{ category.title }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        category.type === 'income'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]"
                                >
                                    {{ category.type === 'income' ? 'Income' : 'Expense' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ category.parent?.title || 'None' }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        category.is_active
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]"
                                >
                                    {{ category.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <!-- Edit Link -->
                                <Link
                                    :href="route('categories.edit', { category: category.id })"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                >
                                    Edit
                                </Link>

                                <!-- Delete Button -->
                                <button
                                    @click="openDeleteDialog(category)"
                                    class="ms-3 font-medium text-red-600 hover:underline dark:text-red-500"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <Pagination :pagination="categories" />
        </div>

        <!-- Delete Confirmation Dialog -->
        <Dialog :open="showDeleteDialog" @update:open="showDeleteDialog = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Category</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the category "<strong>{{ categoryToDelete?.title }}</strong>"?
                        This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="flex gap-2">
                    <Button variant="outline" @click="cancelDelete">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="confirmDelete">
                        Delete Category
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
