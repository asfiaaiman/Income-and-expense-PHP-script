<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowUpRight, ArrowDownRight, DollarSign, TrendingUp, Plus, BarChart3 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Mock data - in a real app, this would come from props
const summary = {
    totalIncome: 12500.00,
    totalExpense: 8200.00,
    netAmount: 4300.00,
    transactionCount: 156,
    monthlyGrowth: 12.5,
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6 overflow-x-auto">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Welcome back! Here's an overview of your finances.
                    </p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('transactions.create')">
                        <Button class="flex items-center gap-2">
                            <Plus class="w-4 h-4" />
                            New Transaction
                        </Button>
                    </Link>
                    <Link :href="route('reports.index')">
                        <Button variant="outline" class="flex items-center gap-2">
                            <BarChart3 class="w-4 h-4" />
                            View Reports
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Income -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Income</CardTitle>
                        <ArrowUpRight class="h-4 w-4 text-green-600" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-green-600">
                            {{ formatCurrency(summary.totalIncome) }}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            +{{ summary.monthlyGrowth }}% from last month
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Expense -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Expense</CardTitle>
                        <ArrowDownRight class="h-4 w-4 text-red-600" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600">
                            {{ formatCurrency(summary.totalExpense) }}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            -8.2% from last month
                        </p>
                    </CardContent>
                </Card>

                <!-- Net Amount -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Net Amount</CardTitle>
                        <TrendingUp class="h-4 w-4 text-blue-600" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ formatCurrency(summary.netAmount) }}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Positive balance
                        </p>
                    </CardContent>
                </Card>

                <!-- Transaction Count -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Transactions</CardTitle>
                        <DollarSign class="h-4 w-4 text-gray-600" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ summary.transactionCount }}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            This month
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <Card>
                    <CardHeader>
                        <CardTitle>Quick Actions</CardTitle>
                        <CardDescription>
                            Manage your finances efficiently
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <Link :href="route('transactions.create')">
                                <Button variant="outline" class="w-full h-20 flex flex-col items-center justify-center gap-2">
                                    <Plus class="w-6 h-6" />
                                    <span>Add Transaction</span>
                                </Button>
                            </Link>
                            <Link :href="route('categories.create')">
                                <Button variant="outline" class="w-full h-20 flex flex-col items-center justify-center gap-2">
                                    <Plus class="w-6 h-6" />
                                    <span>Add Category</span>
                                </Button>
                            </Link>
                            <Link :href="route('transactions.index')">
                                <Button variant="outline" class="w-full h-20 flex flex-col items-center justify-center gap-2">
                                    <DollarSign class="w-6 h-6" />
                                    <span>View Transactions</span>
                                </Button>
                            </Link>
                            <Link :href="route('reports.index')">
                                <Button variant="outline" class="w-full h-20 flex flex-col items-center justify-center gap-2">
                                    <BarChart3 class="w-6 h-6" />
                                    <span>View Reports</span>
                                </Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                <!-- Financial Tips -->
                <Card>
                    <CardHeader>
                        <CardTitle>Financial Tips</CardTitle>
                        <CardDescription>
                            Tips to improve your financial health
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Track Every Transaction</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Record all income and expenses to get a clear picture of your spending habits.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Set Budget Goals</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Create realistic budgets for different categories to control your spending.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Review Regularly</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Check your reports weekly to stay on track with your financial goals.
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
