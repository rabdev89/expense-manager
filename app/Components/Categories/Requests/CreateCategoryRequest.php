<?php

namespace App\Components\Categories\Requests;

use App\Components\Base\Requests\BaseFormRequest;

class CreateCategoryRequest extends BaseFormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:expenses_categories'],
            'description' => ['required']
        ];
    }
}
