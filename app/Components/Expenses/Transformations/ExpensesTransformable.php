<?php

namespace App\Components\Expenses\Transformations;

use App\Components\Expenses\Expenses;
use Illuminate\Support\Facades\Storage;

trait ExpensesTransformable
{
    /**
     * Transform the expense
     *
     * @param Expenses $expense
     * @return Expenses
     */
    protected function transformExpenses(Expenses $expense)
    {
        $expe = new Expenses;
        $expe->id = (int) $expense->id;
        $expe->category = $expense->categories->name;
        $expe->amount = $expense->expense_amount;
        $expe->entry_date = $expense->expense_date;
        $expe->created_at = $expense->created_at;

        return $expe;
    }
}
