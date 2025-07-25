export enum CategoryType {
    INCOME = 'income',
    EXPENSE = 'expense'
}

export const CategoryTypeLabels: Record<CategoryType, string> = {
    [CategoryType.INCOME]: 'Income',
    [CategoryType.EXPENSE]: 'Expense',
};

export const CategoryTypeOptions = [
    { value: CategoryType.INCOME, label: CategoryTypeLabels[CategoryType.INCOME] },
    { value: CategoryType.EXPENSE, label: CategoryTypeLabels[CategoryType.EXPENSE] },
];
