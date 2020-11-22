<?php

namespace App\Components\Categories\Repositories\Interfaces;

use App\Components\Categories\Category;
use App\Components\Expenses\Expenses;
use Illuminate\Support\Collection;
use App\Components\Base\Repositories\BaseRepositoryInterface;

interface CategoryRepositoryInterface  extends BaseRepositoryInterface
{
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createCategory(array $params) : Category;

    public function updateCategory(array $params) : Category;

    public function findCategoryById(int $id) : Category;

    public function deleteCategory() : bool;

    public function associateExpenses(Expenses $product);

    public function findExpenses() : Collection;

    public function syncExpenses(array $params);

    public function detachExpenses();

    public function rootCategories(string $string, string $string1);
}
