<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { CategoryType } from '@/types/CategoryType';

interface Props {
    categories: Array<{
        id: number;
        title: string;
    }>;
    category: {
        id: number;
        title: string;
        type: CategoryType;
        parent_id: number | null;
        is_active: boolean;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Categories', href: '/categories' },
    { title: 'Edit Category', href: '#' },
];

const form = useForm({
    title: props.category.title,
    type: props.category.type,
    parent_id: props.category.parent_id,
    is_active: props.category.is_active,
});

const submit = () => {
    form.put(route('categories.update', { category: props.category.id }));
};
</script>

<template>
    <Head title="Edit Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 min-w-md p-6">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <!-- Category Name -->
                <div class="grid gap-2">
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-900"
                    >
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="e.g. Rental"
                        :disabled="form.processing"
                        class="block w-full rounded-md border-0 bg-white px-3 py-2.5 text-gray-900
                               shadow-sm outline-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                               focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Category Type -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="type">
                        Category Type <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="type"
                            v-model="form.type"
                            required
                            :disabled="form.processing"
                            class="block w-full appearance-none rounded-md border-0 bg-white px-3 py-2.5 pr-10
                                   text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none
                                   focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                        >
                            <option :value="CategoryType.EXPENSE">Expense</option>
                            <option :value="CategoryType.INCOME">Income</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                        {{ form.errors.type }}
                    </p>
                </div>

                <!-- Parent Category -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="parent_id">
                        Parent Category
                    </label>
                    <div class="relative">
                        <select
                            id="parent_id"
                            v-model="form.parent_id"
                            :disabled="form.processing"
                            class="block w-full appearance-none rounded-md border-0 bg-white px-3 py-2.5 pr-10
                                   text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none
                                   focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                        >
                            <option :value="null">No parent category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.title }}
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                    <p v-if="form.errors.parent_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.parent_id }}
                    </p>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-4 text-sm font-medium text-gray-900">
                        Status
                        <div class="flex items-center">
                            <span :class="!form.is_active ? 'text-indigo-600' : 'text-gray-500'">
                                Inactive
                            </span>
                            <button
                                type="button"
                                :disabled="form.processing"
                                class="relative mx-2 inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                :class="form.is_active ? 'bg-indigo-600' : 'bg-gray-300'"
                                @click="form.is_active = !form.is_active"
                            >
                                <span
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                    :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
                                />
                            </button>
                            <span :class="form.is_active ? 'text-indigo-600' : 'text-gray-500'">
                                Active
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold
                           text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline
                           focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
                           disabled:opacity-50"
                >
                    <svg
                        v-if="form.processing"
                        class="-ml-1 mr-2 h-4 w-4 animate-spin"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    {{ form.processing ? 'Updating...' : 'Update Category' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
