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

        $data = [
            'transactions_list' => $model->getTransactionsWithDetails(),
            'title' => 'Lançamentos',
        ];

        return view('templates/header', $data)
            . view('transactions/index');
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
