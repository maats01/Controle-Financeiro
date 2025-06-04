<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\PaymentMethod;
use App\Models\PaymentMethodModel;

class PaymentMethodsController extends BaseController
{
    public function index()
    {
        $model = model(PaymentMethodModel::class);
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        $searchString = $request->getGet('desc') ?? '';
        
        $data = [
            'title' => 'Formas de Pagamento',
            'payment_methods_list' => $model->getFilteredPaymentMethods($searchString)->paginate($perPage),
            'per_page' => $perPage,
            'pager' => $model->pager,
        ]; 

        return view('payment_methods/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar método de pagamento'
        ];
        return view('payment_methods/create', $data);
    }

    public function createPost()
    {
        $model = model(PaymentMethodModel::class);

        $rules = [
            'description' => 'required|min_length[3]|max_length[255]',
        ];

        if(! $this -> validate($rules)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $paymentMethods = new paymentMethod();
        $paymentMethods->fill($this->request->getPost());

        if($model->save($paymentMethods)){
            session()->setFlashdata('success', 'Método de pagamento adicionado com sucesso!');
            return redirect()->to('admin/formas-de-pagamento');
        }else{
            session()->setFlashdata('error', 'Erro ao adicionar método de pagamento. Tente novamente. ');
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function delete(int $id)
    {
        $model = model(PaymentMethodModel::class);

        if(empty($id)){
            session()->setFlashdata('error', 'Método de pagamento não encontrado para exclusão.');
            return redirect()->to('admin/formas-de-pagamento');
        }

        if($model->delete($id)){
            session()->setFlashdata('success', 'Método de pagamento excluído com sucesso!');
        }else{
            session()->setFlashdata('error', 'Erro ao excluir forma de pagamento. Tente novamente mais tarde!');
        }

        return redirect()->to('/admin/formas-de-pagamento');

    }
}
