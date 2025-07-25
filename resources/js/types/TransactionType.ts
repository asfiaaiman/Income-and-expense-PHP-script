export enum TransactionType {
    INCOME = 'income',
    EXPENSE = 'expense'
}

export const TransactionTypeLabels: Record<TransactionType, string> = {
    [TransactionType.INCOME]: 'Income',
    [TransactionType.EXPENSE]: 'Expense',
};

export const TransactionTypeOptions = [
    { value: TransactionType.INCOME, label: TransactionTypeLabels[TransactionType.INCOME] },
    { value: TransactionType.EXPENSE, label: TransactionTypeLabels[TransactionType.EXPENSE] },
];
