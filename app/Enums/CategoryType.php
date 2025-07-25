<?php

namespace App\Enums;

enum CategoryType: string
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

    /**
     * Get all category types as an array.
     * Useful for form selects and validation rules
     */
    public static function toArray(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    /**
     * Get all category types with their labels.
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
