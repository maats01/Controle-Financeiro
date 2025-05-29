<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class TransactionsController extends BaseController
{
    public function index()
    {
        $model = model(TransactionModel::class);
        $request = $this->request;

        $pager = service('pager');
        $currentPage = (int) ($request->getGet('page') ?? 1);
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        $total = $model->getTotalRecords();
        $pager->makeLinks($currentPage, $perPage, $total);

        $data = [
            'transactions_list' => $model->getTransactionsWithDetails($perPage, $currentPage),
            'per_page' => $perPage,
            'pager' => $pager,
            'title' => 'Lançamentos',
        ];

        return view('templates/header', $data)
            . view('transactions/index')
            . view('templates/footer');
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
