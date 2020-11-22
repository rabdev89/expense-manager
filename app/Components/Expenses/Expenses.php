<?php

namespace App\Components\Expenses;
use Illuminate\Database\Eloquent\Model;
use App\Components\Categories\Category;

class Expenses extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expense_category',
        'expense_amount',
        'expense_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


    public function categories()
    {
        return $this->hasOne(Category::class, 'id', 'expense_category');
    }
}
