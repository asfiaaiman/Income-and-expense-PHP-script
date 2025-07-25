<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateCategoryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set all existing categories to 'expense' type as default
        DB::statement("UPDATE categories SET type = 'expense' WHERE type IS NULL");

        // Create some sample categories for testing
        $sampleCategories = [
            ['title' => 'Salary', 'type' => 'income'],
            ['title' => 'Freelance', 'type' => 'income'],
            ['title' => 'Investment', 'type' => 'income'],
            ['title' => 'Groceries', 'type' => 'expense'],
            ['title' => 'Transportation', 'type' => 'expense'],
            ['title' => 'Utilities', 'type' => 'expense'],
            ['title' => 'Entertainment', 'type' => 'expense'],
        ];

        foreach ($sampleCategories as $categoryData) {
            Category::firstOrCreate(
                ['title' => $categoryData['title']],
                [
                    'title' => $categoryData['title'],
                    'type' => $categoryData['type'],
                    'is_active' => true,
                ]
            );
        }

        // Create test transactions for pagination
        $user = User::first();
        if ($user) {
            $categories = Category::all();

            for ($i = 1; $i <= 25; $i++) {
                Transaction::firstOrCreate(
                    ['title' => 'Test Transaction ' . $i],
                    [
                        'title' => 'Test Transaction ' . $i,
                        'type' => $i % 2 == 0 ? 'income' : 'expense',
                        'amount' => rand(10, 1000),
                        'commission' => rand(0, 50),
                        'category_id' => $categories->random()->id,
                        'user_id' => $user->id,
                        'date' => now()->format('Y-m-d'),
                    ]
                );
            }
        }
    }
}
