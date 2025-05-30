<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\SituationModel;
use App\Models\TransactionModel;

class TransactionsController extends BaseController
{
    public function index()
    {
        $model = model(TransactionModel::class);
        $categoryModel = model(CategoryModel::class);
        $situationModel = model(SituationModel::class);
        $request = $this->request;

        // filters
        $startDate = $request->getGet('start_date') ?? '';
        $endDate = $request->getGet('end_date') ?? '';
        $type = $request->getGet('type') ?? '';
        $description = $request->getGet('desc') ?? '';
        $categoryId = $request->getGet('category_id') ?? '';
        if (isset($categoryId) && is_numeric($categoryId))
        {
            $category = $categoryModel->find((int) $categoryId);
        }
        $situationId = $request->getGet('situation_id') ?? '';
        if (isset($situationId) && is_numeric($situationId))
        {
            $situation = $situationModel->find((int) $situationId);
        }


        $perPage = (int) ($request->getGet('per_page') ?? 10);

        $data = [
            'transactions_list' => $model->getFilteredTransactionsWithDetails($startDate, $endDate, $type, $categoryId, $situationId, $description)->paginate($perPage),
            'per_page' => $perPage,
            'selected_situation' => $situation ?? null,
            'selected_category' => $category ?? null,
            'pager' => $model->pager,
            'title' => 'Lançamentos',
        ];

        return view('transactions/index', $data);
    }

    public function create()
    {
        // TODO
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function delete(int $id)
    {
        // TODO
    }
}
