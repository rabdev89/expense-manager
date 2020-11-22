<?php

namespace App\Components\Expenses\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpensesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'expense_category' => ['required'],
            'expense_amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required'],
        ];
    }
}
