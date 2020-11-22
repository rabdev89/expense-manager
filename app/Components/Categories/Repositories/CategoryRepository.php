<?php

namespace App\Components\Categories\Repositories;

use App\Components\Base\Repositories\BaseRepository;
use App\Components\Categories\Category;
use App\Components\Categories\Exceptions\CategoryInvalidArgumentException;
use App\Components\Categories\Exceptions\CategoryNotFoundException;
use App\Components\Expenses\Expenses;
use App\Components\Expenses\Transformations\ExpensesTransformable;
use App\Components\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CategoryRepository  extends BaseRepository implements CategoryRepositoryInterface
{

    /**
     * CategoryRepository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);
        $this->model = $category;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * List all root categories
     *
     * @param  string $order
     * @param  string $sort
     * @param  array  $except
     * @return \Illuminate\Support\Collection
     */
    public function rootCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->whereIsRoot()
                        ->orderBy($order, $sort)
                        ->get()
                        ->except($except);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Category
     * @throws CategoryInvalidArgumentException
     * @throws CategoryNotFoundException
     */
    public function createCategory(array $params) : Category
    {
        try {
            return $this->create($params);
        } catch (QueryException $e) {
            throw new CategoryInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the category
     *
     * @param array $params
     *
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function updateCategory(array $params) : Category
    {
        $category = $this->findCategoryById($this->model->id);
        if ($category) {
            $collection = collect($params)->except('_token');
            $category->update($collection->toArray());

            return $category;
        }

    }

    /**
     * @param int $id
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function findCategoryById(int $id) : Category
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e->getMessage());
        }
    }

    /**
     * Delete a category
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteCategory() : bool
    {
        return $this->model->delete();
    }

    /**
     * Associate a expenses in a category
     *
     * @param Expenses $expenses
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associateExpenses(Expenses $expenses)
    {
        return $this->model->expenses()->save($expenses);
    }

    /**
     * Return all the expenses associated with the category
     *
     * @return mixed
     */
    public function findExpenses() : Collection
    {
        return $this->model->expenses;
    }

    /**
     * @param array $params
     */
    public function syncExpenses(array $params)
    {
        $this->model->expenses()->sync($params);
    }

    /**
     * Detach the association of the expenses
     *
     */
    public function detachExpenses()
    {
        $this->model->expenses()->detach();
    }


}
