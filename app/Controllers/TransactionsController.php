<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\SituationModel;
use App\Models\PaymentMethodModel;
use App\Models\TransactionModel;
use App\Entities\Transaction;
use CodeIgniter\I18n\Time;

class TransactionsController extends BaseController
{
    protected $categoryModel;
    protected $situationModel;
    protected $paymentMethodModel;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
        $this->situationModel = model(SituationModel::class);
        $this->paymentMethodModel = model(PaymentMethodModel::class);
    }

    public function index()
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();
        $categoryModel = model(CategoryModel::class);
        $situationModel = model(SituationModel::class);
        $request = $this->request;

        // filters
        $sortBy = $request->getGet('sort') ?? 'id';
        $sortOrder = $request->getGet('order') ?? 'ASC';
        $startDate = $request->getGet('start_date') ?? '';
        $endDate = $request->getGet('end_date') ?? '';
        $type = $request->getGet('type') ?? '';
        $description = $request->getGet('desc') ?? '';
        $categoryId = $request->getGet('category_id') ?? '';
        $paymentMethodId = $request->getGet('payment_method_id') ?? '';

        if (isset($categoryId) && is_numeric($categoryId))
        {
            $category = $categoryModel->find((int) $categoryId);
        }
        $situationId = $request->getGet('situation_id') ?? '';
        if (isset($situationId) && is_numeric($situationId))
        {
            $situation = $situationModel->find((int) $situationId);
        }
        if (isset($paymentMethodId) && is_numeric($paymentMethodId))
        {
            $paymentMethod = $this->paymentMethodModel->find((int) $paymentMethodId);
        }

        $perPage = (int) ($request->getGet('per_page') ?? 10);

        $data = [
            'transactions_list' => $model->getFilteredTransactionsWithDetails($startDate, $endDate, $type, $categoryId, $situationId, $description, $paymentMethodId, $currentUser->id, $sortBy, $sortOrder)->paginate($perPage),
            'per_page' => $perPage,
            'selected_situation' => $situation ?? null,
            'selected_category' => $category ?? null,
            'payment_methods' => $this->paymentMethodModel->findAll(),
            'selected_payment_method' => $paymentMethod ?? null,
            'pager' => $model->pager,
            'title' => 'Lançamentos',
        ];

        return view('transactions/index', $data);
    }

    public function dashboard()
    {
        $time = Time::now('America/Sao_Paulo', 'pt_BR');
        $currentUser = auth()->user();
        $model = model(TransactionModel::class);

        $transactions = $model->getLatestTransactions($currentUser->id);
        $despesasMes = $model->getCurrentCosts($currentUser->id);
        $receitasMes = $model->getCurrentRevenue($currentUser->id);
        $currentMonthYear = $time->toLocalizedString('MMMM yyyy');

        $data = [
            'transactions_list' => $transactions,
            'despesasMes' => $despesasMes,
            'receitasMes' => $receitasMes,
            'saldoAtualMes' => $receitasMes - $despesasMes,
            'title' => 'Dashboard Financeiro - ' . ucfirst($currentMonthYear), 
        ];

        return view('transactions/dashboard', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar Lançamento',
            'categories' => $this->categoryModel->findAll(),
            'situations' => $this->situationModel->findAll(),
        ];
        
        return view('transactions/create', $data);

    }

    public function createPost()
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();

        $rules = [
            'description' => 'required|min_length[3]|max_length[255]',
            'value' => 'required|numeric|greater_than[0]',
            'type' => 'required|in_list[0,1]',
            'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
            'situation_id' => 'required|is_natural_no_zero|is_not_unique[situations.id]',
            'due_date' => 'required|valid_date', 
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $transaction = new Transaction();
        $transaction->fill($this->request->getPost());
        $transaction->user_id = $currentUser->id;
        $transaction->value = (float) str_replace(',', '.', $this->request->getPost('value'));

        if($model->save($transaction)){
            session()->setFlashdata('success', 'Lançamento adicionado com sucesso!');
            return redirect()->to('/lancamentos');
        }else{
            session()->setFlashdata('error', 'Erro ao adicionar lançamento. Verifique os dados e tente novamente');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function edit(int $id)
    {
        // TODO
    }

    public function editPost()
    {
        
    }

    public function delete(int $id)
    {
        // TODO
    }
}
