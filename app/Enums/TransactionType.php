<?php

namespace App\Enums;

enum TransactionType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Income',
            self::EXPENSE => 'Expense',
        };
    }

    public function getBalanceMultiplier(): int
    {
        return match ($this) {
            self::INCOME => 1, // income adds
            self::EXPENSE => -1, // expense substracts
        };
    }

    /**
     * Get all transaction types as an array.
     * Useful for form selects and validation rules
     */
    public static function toArray(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    /**
     * Get all transaction types with their labels.
     * Perfect for building select dropdowns in forms
     */
    public static function options(): array
    {
        return array_map(
            fn(self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
