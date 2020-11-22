<?php

namespace App\Components\Expenses\Repositories;

use App\Components\Base\Repositories\BaseRepository;
use App\Components\Expenses\Exceptions\ExpensesCreateErrorException;
use App\Components\Expenses\Exceptions\ExpensesUpdateErrorException;
use App\Components\Expenses\Exceptions\ExpensesNotFoundException;
use App\Components\Expenses\Expenses;
use App\Components\Expenses\Transformations\ExpensesTransformable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Components\Expenses\Repositories\Interfaces\ExpensesRepositoryInterface;

class ExpensesRepository extends BaseRepository  implements ExpensesRepositoryInterface
{
    use ExpensesTransformable;

    /**
     * ExpensesRepository constructor.
     * @param Expenses $expenses
     */
    public function __construct(Expenses $expenses)
    {
        parent::__construct($expenses);
        $this->model = $expenses;
    }

    /**
     * List all the expenses
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listExpenses(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the Expenses
     *
     * @param array $data
     *
     * @return Expenses
     * @throws ExpensesCreateErrorException
     */
    public function createExpenses(array $data) : Expenses
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new ExpensesCreateErrorException($e);
        }
    }

    /**
     * Update the Expenses
     *
     * @param array $data
     *
     * @return bool
     * @throws ExpensesUpdateErrorException
     */
    public function updateExpenses(array $data) : bool
    {
        $filtered = collect($data)->all();

        try {
            return $this->model->where('id', $this->model->id)->update($filtered);
        } catch (QueryException $e) {
            throw new ExpensesUpdateErrorException($e);
        }
    }

    /**
     * Find the Expenses by ID
     *
     * @param int $id
     *
     * @return Expenses
     * @throws ExpensesNotFoundException
     */
    public function findExpensesById(int $id) : Expenses
    {
        try {
            return $this->transformExpenses($this->findOneOrFail($id));
        } catch (ModelNotFoundException $e) {
            throw new ExpensesNotFoundException($e);
        }
    }

    /**
     * Delete the expenses
     *
     * @param Expenses $expenses
     *
     * @return bool
     * @throws \Exception
     * @deprecated
     * @use removeExpenses
     */
    public function deleteExpenses(Expenses $expenses) : bool
    {
        return $expenses->delete();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function removeExpenses() : bool
    {
        return $this->model->where('id', $this->model->id)->delete();
    }

    /**
     * Detach the categories
     */
    public function detachCategories()
    {
        $this->model->categories()->detach();
    }

    /**
     * Return the categories which the expenses is associated with
     *
     * @return Collection
     */
    public function getCategories() : Collection
    {
        return $this->model->categories()->get();
    }

    /**
     * Sync the categories
     *
     * @param array $params
     */
    public function syncCategories(array $params)
    {
        $this->model->categories()->sync($params);
    }

    /**
     * @param string $text
     * @return mixed
     */
    public function searchExpenses(string $text) : Collection
    {
        if (!empty($text)) {
            return $this->model->searchExpenses($text);
        } else {
            return $this->listExpensess();
        }
    }


}
