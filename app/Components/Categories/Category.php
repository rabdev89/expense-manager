<?php

namespace App\Components\Categories;

use App\Components\Expenses\Expenses;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'expenses_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function expenses()
    {
        return $this->belongsToMany(Expenses::class);
    }
}
