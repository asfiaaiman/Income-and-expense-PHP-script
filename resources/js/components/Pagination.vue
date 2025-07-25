<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
    pagination: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from?: number;
        to?: number;
    };
    links?: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

const props = defineProps<Props>();

// Generate page numbers to show
const getPageNumbers = () => {
    const current = props.pagination.current_page;
    const last = props.pagination.last_page;
    const delta = 2; // Number of pages to show on each side of current page

    const range = [];
    const rangeWithDots = [];

    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }

    if (current - delta > 2) {
        rangeWithDots.push(1, '...');
    } else {
        rangeWithDots.push(1);
    }

    rangeWithDots.push(...range);

    if (current + delta < last - 1) {
        rangeWithDots.push('...', last);
    } else if (last > 1) {
        rangeWithDots.push(last);
    }

    return rangeWithDots;
};

// Per page options
const perPageOptions = [10, 25, 50, 100];

// Get current URL parameters
const getCurrentUrl = () => {
    const url = new URL(window.location.href);
    return url;
};

// Update per page
const updatePerPage = (perPage: number) => {
    const url = getCurrentUrl();
    url.searchParams.set('per_page', perPage.toString());
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
};

// Generate pagination URL with preserved parameters
const getPaginationUrl = (page: number) => {
    const url = getCurrentUrl();
    url.searchParams.set('page', page.toString());
    return url.toString();
};
</script>

<template>
    <div class="flex items-center justify-between px-6 py-4 bg-white border-t border-gray-200 sm:px-8 dark:bg-gray-800 dark:border-gray-700">
        <!-- Pagination Info -->
        <div class="flex items-center justify-between w-full sm:hidden">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.from || 1 }}</span> to
                <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.to || pagination.total }}</span> of
                <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.total }}</span> results
            </div>
            <div class="flex space-x-2">
                <Link
                    v-if="pagination.current_page > 1"
                    :href="getPaginationUrl(pagination.current_page - 1)"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                >
                    Previous
                </Link>
                <Link
                    v-if="pagination.current_page < pagination.last_page"
                    :href="getPaginationUrl(pagination.current_page + 1)"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                >
                    Next
                </Link>
            </div>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Showing</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.from || 1 }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">to</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.to || pagination.total }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">of</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.total }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">results</span>
                </div>

                <!-- Per Page Selector -->
                <div class="flex items-center space-x-3">
                    <label for="per-page" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Show:
                    </label>
                    <div class="relative">
                        <select
                            id="per-page"
                            :value="pagination.per_page"
                            @change="updatePerPage(Number(($event.target as HTMLSelectElement).value))"
                            class="appearance-none block w-20 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-blue-400"
                        >
                            <option v-for="option in perPageOptions" :key="option" :value="option">
                                {{ option }}
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">per page</span>
                </div>
            </div>

            <div>
                <nav class="flex items-center space-x-1" aria-label="Pagination">
                    <!-- Previous Page -->
                    <Link
                        v-if="pagination.current_page > 1"
                        :href="getPaginationUrl(pagination.current_page - 1)"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-gray-300"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </Link>

                    <!-- Page Numbers -->
                    <template v-for="(page, index) in getPageNumbers()" :key="index">
                        <span
                            v-if="page === '...'"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                        >
                            ...
                        </span>
                        <Link
                            v-else
                            :href="getPaginationUrl(Number(page))"
                            :class="[
                                'inline-flex items-center px-3 py-2 text-sm font-medium border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                                page === pagination.current_page
                                    ? 'bg-blue-600 border-blue-600 text-white shadow-sm hover:bg-blue-700'
                                    : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-gray-300'
                            ]"
                        >
                            {{ page }}
                        </Link>
                    </template>

                    <!-- Next Page -->
                    <Link
                        v-if="pagination.current_page < pagination.last_page"
                        :href="getPaginationUrl(pagination.current_page + 1)"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-gray-300"
                    >
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>
