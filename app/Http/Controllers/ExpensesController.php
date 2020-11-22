<?php

namespace App\Http\Controllers;

use App\Components\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Components\Expenses\Exceptions\ExpensesInvalidArgumentException;
use App\Components\Expenses\Exceptions\ExpensesNotFoundException;
use App\Components\Expenses\Expenses;
use App\Components\Expenses\Repositories\Interfaces\ExpensesRepositoryInterface;
use App\Components\Expenses\Repositories\ExpensesRepository;
use App\Components\Expenses\Requests\CreateExpensesRequest;
use App\Components\Expenses\Requests\UpdateExpensesRequest;
use App\Http\Controllers\Controller;
use App\Components\Expenses\Transformations\ExpensesTransformable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller
{
    use ExpensesTransformable;

    /**
     * @var ExpensesRepositoryInterface
     */
    private $expensesRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;


    /**
     * ExpensesController constructor.
     *
     * @param ExpensesRepositoryInterface $expensesRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ExpensesRepositoryInterface $expensesRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->expensesRepo = $expensesRepository;
        $this->categoryRepo = $categoryRepository;

        // $this->middleware(['permission:create-expenses'], ['only' => ['create', 'store']]);
        // $this->middleware(['permission:update-expenses'], ['only' => ['edit', 'update']]);
        // $this->middleware(['permission:delete-expenses'], ['only' => ['destroy']]);
        // $this->middleware(['permission:view-expenses'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->expensesRepo->listExpenses('id');

        if (request()->has('q') && request()->input('q') != '') {
            $list = $this->expensesRepo->searchExpenses(request()->input('q'));
        }

        $expenses = $list->map(function (Expenses $item) {
            return $this->transformExpenses($item);
        })->all();

        return response()->json($this->expensesRepo->paginateArrayResults($expenses, 25), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateExpensesRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExpensesRequest $request)
    {
        $data = $request->except('_token', '_method');

        $expenses = $this->expensesRepo->createExpenses($data);

        return response()->json(compact('expenses'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $expenses = $this->expensesRepo->findExpensesById($id);

        return response()->json(compact('expenses'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateExpensesRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Components\Expenses\Exceptions\ExpensesUpdateErrorException
     */
    public function update(UpdateExpensesRequest $request, int $id)
    {
        $expenses = $this->expensesRepo->findExpensesById($id);
        $expensesRepo = new ExpensesRepository($expenses);

        $data = $request->except(
            'expense_category',
            '_token',
            '_method'
        );

        if ($request->has('expense_category')) {
            $expensesRepo->syncCategories($request->input('expense_category'));
        } else {
            $expensesRepo->detachCategories();
        }

        $expensesRepo->updateExpenses($data);

        return response()->json('Update successful', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expenses = $this->expensesRepo->findExpensesById($id);
        $expenses->categories()->sync([]);

        $expensesRepo = new ExpensesRepository($expenses);
        $expensesRepo->removeExpenses();

        return response()->json('Delete successful', 200);
    }

}
