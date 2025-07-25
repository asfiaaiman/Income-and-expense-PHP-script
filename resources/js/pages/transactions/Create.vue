<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { TransactionType } from '@/types/TransactionType';
import { CategoryType } from '@/types/CategoryType';
import { computed, watch } from 'vue';

interface Props {
    categories: Array<{
        id: number;
        title: string;
        type: CategoryType;
    }>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Transactions', href: '/transactions' },
    { title: 'Create Transaction', href: '#' },
];

const form = useForm({
    title: '',
    category_id: null as number | null,
    type: TransactionType.EXPENSE,
    amount: '',
    commission: '',
    date: new Date().toISOString().split('T')[0],
});

// Filter categories based on transaction type (but allow cross-category usage)
const filteredCategories = computed(() => {
    // Show all categories, but prioritize matching type
    const matchingCategories = props.categories.filter(category => category.type === form.type as string);
    const otherCategories = props.categories.filter(category => category.type !== form.type as string);

    // Return matching categories first, then others
    return [...matchingCategories, ...otherCategories];
});

// Reset category selection when transaction type changes
watch(() => form.type, () => {
    const currentCategory = props.categories.find(cat => cat.id === form.category_id);
    if (currentCategory && currentCategory.type !== form.type as string) {
        // Keep the category but show a visual indicator that it's a different type
        // Don't reset automatically - let user decide
    }
});

const submit = () => {
    form.post(route('transactions.store'));
};
</script>

<template>
    <Head title="Create Transaction" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto mt-8 min-w-md p-6">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <!-- Transaction Title -->
                <div class="grid gap-2">
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-900"
                    >
                        Transaction Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="e.g. Grocery shopping"
                        :disabled="form.processing"
                        class="block w-full rounded-md border-0 bg-white px-3 py-2.5 text-gray-900
                               shadow-sm outline-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                               focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Transaction Type -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="type">
                        Transaction Type <span class="text-red-500">*</span>
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
                            <option :value="TransactionType.EXPENSE">Expense</option>
                            <option :value="TransactionType.INCOME">Income</option>
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

                <!-- Category -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="category_id">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            required
                            :disabled="form.processing"
                            class="block w-full appearance-none rounded-md border-0 bg-white px-3 py-2.5 pr-10
                                   text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none
                                   focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                        >
                            <option :value="null" disabled>Select a category</option>
                            <optgroup v-if="filteredCategories.filter(cat => cat.type === (form.type as string)).length > 0" :label="`${form.type === 'income' ? 'Income' : 'Expense'} Categories`">
                                <option v-for="category in filteredCategories.filter(cat => cat.type === (form.type as string))" :key="category.id" :value="category.id">
                                    {{ category.title }}
                                </option>
                            </optgroup>
                            <optgroup v-if="filteredCategories.filter(cat => cat.type !== (form.type as string)).length > 0" :label="`${form.type === 'income' ? 'Expense' : 'Income'} Categories (Cross-category)`">
                                <option v-for="category in filteredCategories.filter(cat => cat.type !== (form.type as string))" :key="category.id" :value="category.id">
                                    {{ category.title }}
                                </option>
                            </optgroup>
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
                    <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.category_id }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">
                        You can use any category type for any transaction type. Cross-category usage is allowed.
                    </p>
                </div>

                <!-- Transaction Amount -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="amount">
                        Transaction Amount <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="amount"
                        v-model="form.amount"
                        type="number"
                        step="0.01"
                        min="0.01"
                        required
                        autocomplete="off"
                        placeholder="e.g. 100.00"
                        :disabled="form.processing"
                        class="block w-full rounded-md border-0 bg-white px-3 py-2.5 text-gray-900
                               shadow-sm outline-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                               focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    />
                    <p v-if="form.errors.amount" class="mt-1 text-sm text-red-600">
                        {{ form.errors.amount }}
                    </p>
                </div>

                <!-- Transaction Commission -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="commission">
                        Transaction Commission <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="commission"
                        v-model="form.commission"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        autocomplete="off"
                        placeholder="e.g. 10.00"
                        :disabled="form.processing"
                        class="block w-full rounded-md border-0 bg-white px-3 py-2.5 text-gray-900
                               shadow-sm outline-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                               focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    />
                    <p v-if="form.errors.commission" class="mt-1 text-sm text-red-600">
                        {{ form.errors.commission }}
                    </p>
                </div>

                <!-- Transaction Date -->
                <div class="grid gap-2">
                    <label class="block text-sm font-medium text-gray-900" for="date">
                        Transaction Date <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="date"
                        v-model="form.date"
                        type="date"
                        required
                        autocomplete="off"
                        :disabled="form.processing"
                        class="block w-full rounded-md border-0 bg-white px-3 py-2.5 text-gray-900
                               shadow-sm outline-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                               focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    />
                    <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                        {{ form.errors.date }}
                    </p>
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
                    {{ form.processing ? 'Creating...' : 'Create Transaction' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
