<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';

interface Props {
    transactions: Array<{
        id: number;
        title: string;
        type: 'income' | 'expense';
        amount: number;
        commission: number;
        date: string;
        category?: { title: string };
    }>;
    summary: {
        total_income: number;
        total_expense: number;
        total_commission: number;
        net_amount: number;
        transaction_count: number;
        income_count: number;
        expense_count: number;
    };
    categoryBreakdown: {
        income: Array<{
            category_name: string;
            amount: number;
            count: number;
            type: string;
        }>;
        expense: Array<{
            category_name: string;
            amount: number;
            count: number;
            type: string;
        }>;
    };
    trends: Array<{
        date?: string;
        week?: string;
        month?: string;
        year?: string;
        income: number;
        expense: number;
        net: number;
        count: number;
    }>;
    topCategories: {
        income: Array<{
            category_name: string;
            amount: number;
            count: number;
        }>;
        expense: Array<{
            category_name: string;
            amount: number;
            count: number;
        }>;
    };
    filters: {
        start_date: string;
        end_date: string;
        period: string;
    };
    periods: Array<{
        value: string;
        label: string;
    }>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
];

// Filter form
const filterForm = ref({
    period: props.filters.period,
    start_date: props.filters.start_date,
    end_date: props.filters.end_date,
});

// Apply filters
const applyFilters = () => {
    const params = new URLSearchParams();

    if (filterForm.value.period === 'custom') {
        params.set('start_date', filterForm.value.start_date);
        params.set('end_date', filterForm.value.end_date);
    }
    params.set('period', filterForm.value.period);

    router.get(`/reports?${params.toString()}`);
};

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};

// Format date
const formatDate = (dateString: string) => {
    if (!dateString) return '';

    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);
};

// Format percentage
const formatPercentage = (value: number, total: number) => {
    if (total === 0) return '0%';
    return `${((value / total) * 100).toFixed(1)}%`;
};

// Computed properties for better UX
const isCustomPeriod = computed(() => filterForm.value.period === 'custom');
const netAmountClass = computed(() =>
    props.summary.net_amount >= 0 ? 'text-green-600' : 'text-red-600'
);
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Financial Reports</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Detailed analysis of your income and expenses
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <div class="flex flex-wrap items-end gap-4">
                    <!-- Period Selector -->
                    <div class="flex-1 min-w-48">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Report Period
                        </label>
                        <div class="relative">
                            <select
                                v-model="filterForm.period"
                                @change="applyFilters"
                                class="block w-full appearance-none rounded-md border-0 bg-white dark:bg-gray-700 px-3 py-2.5 pr-10
                                       text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none
                                       focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 sm:text-sm
                                       transition-all duration-200 hover:ring-gray-400 dark:hover:ring-gray-500"
                            >
                                <option v-for="period in periods" :key="period.value" :value="period.value">
                                    {{ period.label }}
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg
                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 transition-colors duration-200"
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
                    </div>

                    <!-- Custom Date Range -->
                    <div v-if="isCustomPeriod" class="flex gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Date
                            </label>
                            <input
                                v-model="filterForm.start_date"
                                type="date"
                                class="block w-full rounded-md border-0 bg-white dark:bg-gray-700 px-3 py-2.5
                                       text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none
                                       focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 sm:text-sm
                                       transition-all duration-200 hover:ring-gray-400 dark:hover:ring-gray-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Date
                            </label>
                            <input
                                v-model="filterForm.end_date"
                                type="date"
                                class="block w-full rounded-md border-0 bg-white dark:bg-gray-700 px-3 py-2.5
                                       text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-none
                                       focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 sm:text-sm
                                       transition-all duration-200 hover:ring-gray-400 dark:hover:ring-gray-500"
                            />
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="applyFilters"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2.5 rounded-md
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800
                                       transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md"
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Income -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Income</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ formatCurrency(summary.total_income) }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ summary.income_count }} transactions
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Expense -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expense</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ formatCurrency(summary.total_expense) }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ summary.expense_count }} transactions
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Net Amount -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Amount</p>
                            <p :class="['text-2xl font-semibold', netAmountClass]">
                                {{ formatCurrency(summary.net_amount) }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ summary.transaction_count }} total transactions
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Commission -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Commission</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ formatCurrency(summary.total_commission) }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Across all transactions
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Breakdowns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Category Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Category Breakdown</h3>

                    <!-- Income Categories -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-green-600 dark:text-green-400 mb-3">Income Categories</h4>
                        <div class="space-y-3">
                            <div v-for="category in categoryBreakdown.income" :key="category.category_name" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ category.category_name }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ formatCurrency(category.amount) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatPercentage(category.amount, summary.total_income) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Categories -->
                    <div>
                        <h4 class="text-sm font-medium text-red-600 dark:text-red-400 mb-3">Expense Categories</h4>
                        <div class="space-y-3">
                            <div v-for="category in categoryBreakdown.expense" :key="category.category_name" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ category.category_name }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ formatCurrency(category.amount) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatPercentage(category.amount, summary.total_expense) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Categories</h3>

                    <!-- Top Income Categories -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-green-600 dark:text-green-400 mb-3">Top Income Sources</h4>
                        <div class="space-y-3">
                            <div v-for="(category, index) in topCategories.income" :key="category.category_name" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-6">#{{ index + 1 }}</span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ category.category_name }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ formatCurrency(category.amount) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ category.count }} transactions
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Expense Categories -->
                    <div>
                        <h4 class="text-sm font-medium text-red-600 dark:text-red-400 mb-3">Top Expense Categories</h4>
                        <div class="space-y-3">
                            <div v-for="(category, index) in topCategories.expense" :key="category.category_name" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 w-6">#{{ index + 1 }}</span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ category.category_name }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ formatCurrency(category.amount) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ category.count }} transactions
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trends Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trends</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Period
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Income
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Expense
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Net
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Transactions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="trend in trends" :key="trend.date || trend.week || trend.month || trend.year">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ trend.date ? formatDate(trend.date) : (trend.week || trend.month || trend.year) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400">
                                    {{ formatCurrency(trend.income) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">
                                    {{ formatCurrency(trend.expense) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="trend.net >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ formatCurrency(trend.net) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ trend.count }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
