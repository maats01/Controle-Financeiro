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
        $sortBy = $request->getGet('sort') ?? 'id';
        $sortOrder = $request->getGet('order') ?? 'ASC';

        $data = [
            'title' => 'Formas de Pagamento',
            'payment_methods_list' => $model->getFilteredPaymentMethods($searchString, $sortBy, $sortOrder)->paginate($perPage),
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

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $paymentMethods = new paymentMethod();
        $paymentMethods->fill($this->request->getPost());

        if ($model->save($paymentMethods)) {
            session()->setFlashdata('success', 'Método de pagamento adicionado com sucesso!');
            return redirect()->to('admin/formas-de-pagamento');
        } else {
            session()->setFlashdata('error', 'Erro ao adicionar método de pagamento. Tente novamente. ');
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        $model = model(PaymentMethodModel::class);
        $paymentMethod = $model->find($id);

        if (empty($paymentMethod)) {
            session()->setFlashdata('error', 'Método de pagamento não encontrado.');
            return redirect()->to('admin/formas-de-pagamento');
        }

        $data = [
            'title' => 'Editar método de pagamento',
            'payment_method' => $paymentMethod,
        ];

        return view('payment_methods/edit', $data);
    }

    public function editPost()
    {
        $model = model(PaymentMethodModel::class);

        $postData = $this->request->getPost();

        $paymentMethod = $model->find($postData['id']);

        if (empty($paymentMethod)) {
            session()->setFlashdata('error', 'Método de pagamento não encontrado para atualização.');
            return redirect()->to('admin/formas-de-pagamento');
        }

        $paymentMethod->fill($postData);

        if (!$paymentMethod->hasChanged()) {
            session()->setFlashdata('info', 'Nenhuma alteração detectada para o método de pagamento.');
            return redirect()->to('admin/formas-de-pagamento');
        }

        if ($model->save($paymentMethod)) {
            session()->setFlashdata('success', 'Método de pagamento atualizado com sucesso!');
            return redirect()->to('admin/formas-de-pagamento');
        } else {
            session()->setFlashdata('error', 'Erro ao atualizar método de pagamento. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function delete(int $id)
    {
        $model = model(PaymentMethodModel::class);

        if (empty($id)) {
            session()->setFlashdata('error', 'Método de pagamento não encontrado para exclusão.');
            return redirect()->to('admin/formas-de-pagamento');
        }

        if ($model->delete($id)) {
            session()->setFlashdata('success', 'Método de pagamento excluído com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir forma de pagamento. Tente novamente mais tarde!');
        }

        return redirect()->to('/admin/formas-de-pagamento');
    }
}
