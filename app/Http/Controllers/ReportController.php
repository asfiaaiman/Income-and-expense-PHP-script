<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, annual, custom

        // Validate and adjust date range based on period
        $dateRange = $this->getDateRange($period, $startDate, $endDate);

        // Get transactions for the date range
        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate summary statistics
        $summary = $this->calculateSummary($transactions);

        // Get category-wise breakdown
        $categoryBreakdown = $this->getCategoryBreakdown($transactions);

        // Get daily/weekly/monthly trends
        $trends = $this->getTrends($transactions, $period);

        // Get top categories
        $topCategories = $this->getTopCategories($transactions);

        return Inertia::render('reports/Index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'categoryBreakdown' => $categoryBreakdown,
            'trends' => $trends,
            'topCategories' => $topCategories,
            'filters' => [
                'start_date' => $dateRange['start'],
                'end_date' => $dateRange['end'],
                'period' => $period,
            ],
            'periods' => [
                ['value' => 'daily', 'label' => 'Daily'],
                ['value' => 'weekly', 'label' => 'Weekly'],
                ['value' => 'monthly', 'label' => 'Monthly'],
                ['value' => 'annual', 'label' => 'Annual'],
                ['value' => 'custom', 'label' => 'Custom Range'],
            ],
        ]);
    }

    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'daily':
                return [
                    'start' => $now->format('Y-m-d'),
                    'end' => $now->format('Y-m-d'),
                ];
            case 'weekly':
                return [
                    'start' => $now->startOfWeek()->format('Y-m-d'),
                    'end' => $now->endOfWeek()->format('Y-m-d'),
                ];
            case 'monthly':
                return [
                    'start' => $now->startOfMonth()->format('Y-m-d'),
                    'end' => $now->endOfMonth()->format('Y-m-d'),
                ];
            case 'annual':
                return [
                    'start' => $now->startOfYear()->format('Y-m-d'),
                    'end' => $now->endOfYear()->format('Y-m-d'),
                ];
            case 'custom':
                return [
                    'start' => $startDate ?: $now->startOfMonth()->format('Y-m-d'),
                    'end' => $endDate ?: $now->endOfMonth()->format('Y-m-d'),
                ];
            default:
                return [
                    'start' => $now->startOfMonth()->format('Y-m-d'),
                    'end' => $now->endOfMonth()->format('Y-m-d'),
                ];
        }
    }

    private function calculateSummary($transactions)
    {
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalCommission = $transactions->sum('commission');
        $netAmount = $totalIncome - $totalExpense;

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'total_commission' => $totalCommission,
            'net_amount' => $netAmount,
            'transaction_count' => $transactions->count(),
            'income_count' => $transactions->where('type', 'income')->count(),
            'expense_count' => $transactions->where('type', 'expense')->count(),
        ];
    }

    private function getCategoryBreakdown($transactions)
    {
        $breakdown = [];

        // Income categories
        $incomeCategories = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'category_name' => $category ? $category->title : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count(),
                    'type' => 'income',
                ];
            })
            ->values();

        // Expense categories
        $expenseCategories = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'category_name' => $category ? $category->title : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count(),
                    'type' => 'expense',
                ];
            })
            ->values();

        return [
            'income' => $incomeCategories,
            'expense' => $expenseCategories,
        ];
    }

    private function getTrends($transactions, $period)
    {
        $trends = [];

        switch ($period) {
            case 'daily':
                $trends = $transactions->groupBy('date')
                    ->map(function ($group) {
                        return [
                            'date' => $group->first()->date,
                            'income' => $group->where('type', 'income')->sum('amount'),
                            'expense' => $group->where('type', 'expense')->sum('amount'),
                            'net' => $group->where('type', 'income')->sum('amount') - $group->where('type', 'expense')->sum('amount'),
                            'count' => $group->count(),
                        ];
                    })
                    ->values();
                break;

            case 'weekly':
                $trends = $transactions->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->startOfWeek()->format('Y-W');
                })
                ->map(function ($group) {
                    $firstDate = $group->first()->date;
                    return [
                        'week' => Carbon::parse($firstDate)->startOfWeek()->format('M d'),
                        'income' => $group->where('type', 'income')->sum('amount'),
                        'expense' => $group->where('type', 'expense')->sum('amount'),
                        'net' => $group->where('type', 'income')->sum('amount') - $group->where('type', 'expense')->sum('amount'),
                        'count' => $group->count(),
                    ];
                })
                ->values();
                break;

            case 'monthly':
                $trends = $transactions->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->format('Y-m');
                })
                ->map(function ($group) {
                    $firstDate = $group->first()->date;
                    return [
                        'month' => Carbon::parse($firstDate)->format('M Y'),
                        'income' => $group->where('type', 'income')->sum('amount'),
                        'expense' => $group->where('type', 'expense')->sum('amount'),
                        'net' => $group->where('type', 'income')->sum('amount') - $group->where('type', 'expense')->sum('amount'),
                        'count' => $group->count(),
                    ];
                })
                ->values();
                break;

            case 'annual':
                $trends = $transactions->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->date)->format('Y');
                })
                ->map(function ($group) {
                    $firstDate = $group->first()->date;
                    return [
                        'year' => Carbon::parse($firstDate)->format('Y'),
                        'income' => $group->where('type', 'income')->sum('amount'),
                        'expense' => $group->where('type', 'expense')->sum('amount'),
                        'net' => $group->where('type', 'income')->sum('amount') - $group->where('type', 'expense')->sum('amount'),
                        'count' => $group->count(),
                    ];
                })
                ->values();
                break;
        }

        return $trends;
    }

    private function getTopCategories($transactions)
    {
        $topIncomeCategories = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'category_name' => $category ? $category->title : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('amount')
            ->take(5)
            ->values();

        $topExpenseCategories = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'category_name' => $category ? $category->title : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('amount')
            ->take(5)
            ->values();

        return [
            'income' => $topIncomeCategories,
            'expense' => $topExpenseCategories,
        ];
    }
}
