<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaymentMethodModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaymentMethodsController extends BaseController
{
    public function index()
    {
        $model = model(PaymentMethodModel::class);
        $data = [
            'title' => 'Formas de Pagamento',
            'payment_methods_list' => $model->findAll(),
        ]; 

        return view('templates/header', $data)
            . view('payment_methods/index');
    }
}
