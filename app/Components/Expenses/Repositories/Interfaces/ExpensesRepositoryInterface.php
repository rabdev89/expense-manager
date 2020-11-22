<?php

namespace App\Components\Expenses\Repositories\Interfaces;

use App\Components\AttributeValues\AttributeValue;

use App\Components\Expenses\Expenses;
use App\Components\Base\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface ExpensesRepositoryInterface extends BaseRepositoryInterface
{
    public function listExpenses(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createExpenses(array $data) : Expenses;

    public function updateExpenses(array $data) : bool;

    public function findExpensesById(int $id) : Expenses;

    public function deleteExpenses(Expenses $product) : bool;

    public function removeExpenses() : bool;

    public function detachCategories();

    public function getCategories() : Collection;

    public function syncCategories(array $params);

    public function searchExpenses(string $text) : Collection;


}
