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
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        
        $data = [
            'title' => 'Formas de Pagamento',
            'payment_methods_list' => $model->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
        ]; 

        return view('templates/header', $data)
            . view('payment_methods/index')
            . view('templates/footer');
    }
}
