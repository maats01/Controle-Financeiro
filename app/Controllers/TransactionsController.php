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
        $request = $this->request;
        
        // filters
        $sortBy = $request->getGet('sort') ?? 'id';
        $sortOrder = $request->getGet('order') ?? 'DESC';
        $startDate = $request->getGet('start_date') ?? '';
        $endDate = $request->getGet('end_date') ?? '';
        $type = $request->getGet('type') ?? '';
        $description = $request->getGet('desc') ?? '';
        $categoryId = $request->getGet('category_id') ?? '';
        $paymentMethodId = $request->getGet('payment_method_id') ?? '';

        if (isset($categoryId) && is_numeric($categoryId)) {
            $category = $this->categoryModel->find((int) $categoryId);
        }
        $situationId = $request->getGet('situation_id') ?? '';
        if (isset($situationId) && is_numeric($situationId)) {
            $situation = $this->situationModel->find((int) $situationId);
        }
        if (isset($paymentMethodId) && is_numeric($paymentMethodId)) {
            $paymentMethod = $this->paymentMethodModel->find((int) $paymentMethodId);
        }

        $perPage = (int) ($request->getGet('per_page') ?? 10);

        $data = [
            'transactions_list' => $model->getFilteredTransactionsWithDetails(
                $startDate, 
                $endDate, 
                $type, 
                $categoryId, 
                $situationId, 
                $description, 
                $paymentMethodId, 
                $currentUser->id, 
                $sortBy, 
                $sortOrder)
                ->paginate($perPage),
            'per_page' => $perPage,
            'selected_situation' => $situation ?? null,
            'selected_category' => $category ?? null,
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

        // getting data to display overall expenses and revenue of the current month
        $transactions = $model->getLatestTransactions($currentUser->id);
        $monthExpenses = $model->getCurrentCosts($currentUser->id);
        $monthRevenues = $model->getCurrentRevenue($currentUser->id);

        // getting data to plot the line graph
        $latest_transactions = $model->getCurrentYearTransactions($currentUser->id);
        $currentYear = date('Y');
        $labels_for_line_graph = ["Jan/$currentYear", "Fev/$currentYear", "Mar/$currentYear", "Abr/$currentYear", "Mai/$currentYear", "Jun/$currentYear", "Jul/$currentYear", "Ago/$currentYear", "Set/$currentYear", "Out/$currentYear", "Nov/$currentYear", "Dez/$currentYear"];
        $expensesData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $revenuesData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($latest_transactions as $t) {
            $month = (int) $t->month;
            $expensesData[$month - 1] = $t->expenses;
            $revenuesData[$month - 1] = $t->revenues;
        }

        // getting data to plot the doughnut graph
        $expenses_by_category = $model->getCurrentMonthExpensesByCategory($currentUser->id);
        $total_amounts_by_category = [];
        $labels_for_pie_graph = [];
        foreach ($expenses_by_category as $e) {
            $labels_for_pie_graph[] = $e->category;
            $total_amounts_by_category[] = (float) $e->total;
        }
        
        $data = [
            'transactions_list' => $transactions,
            'month_expenses' => $monthExpenses,
            'month_revenues' => $monthRevenues,
            'labels_for_pie_graph' => $labels_for_pie_graph,
            'data_for_pie_graph' => $total_amounts_by_category,
            'labels_for_line_graph' => $labels_for_line_graph,
            'latest_expenses' => $expensesData,
            'latest_revenues' => $revenuesData,
            'current_balance' => $monthRevenues - $monthExpenses,
            'title' => 'Dashboard Financeiro - ' . ucfirst($time->toLocalizedString('MMMM yyyy')),
        ];

        return view('transactions/dashboard', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Criar Lançamento',
            'categories' => $this->categoryModel->findAll(),
            'situations' => $this->situationModel->findAll(),
            'payment_methods' => $this->paymentMethodModel->findAll(),
        ];

        return view('transactions/create', $data);
    }

    public function createPost()
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();

        $transaction = new Transaction();
        $transaction->fill($this->request->getPost());
        $transaction->user_id = $currentUser->id;
        $transaction->amount = (float) str_replace(',', '.', $this->request->getPost('amount'));

        $rules = $model->getValidationRules();
        $messages = $model->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        if ($model->save($transaction)) {
            session()->setFlashdata('success', 'Lançamento adicionado com sucesso!');
            return redirect()->to('/lancamentos');
        } else {
            session()->setFlashdata('error', 'Erro ao adicionar lançamento. Verifique os dados e tente novamente');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function edit(int $id)
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();

        // Busca a transação pelo ID E pelo user_id para garantir permissão
        $transaction = $model->where('id', $id)
            ->where('user_id', $currentUser->id)
            ->first();

        if (empty($transaction)) {
            session()->setFlashdata('error', 'Lançamento não encontrado ou você não tem permissão para editá-lo.');
            return redirect()->to('/lancamentos');
        }
        $data = [
            'title' => 'Editar Lançamento',
            'transaction' => $transaction,
            'categories' => $this->categoryModel->findAll(),
            'situations' => $this->situationModel->findAll(),
            'payment_methods' => $this->paymentMethodModel->findAll(),
        ];

        return view('transactions/edit', $data);
    }

    public function editPost()
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();
        $postData = $this->request->getPost();

        $rules = $model->getValidationRules();
        $messages = $model->getValidationMessages();

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $transaction = $model->where('id', $postData['id'])
            ->where('user_id', $currentUser->id)
            ->first();

        if (empty($transaction)) {
            session()->setFlashdata('error', 'Lançamento não encontrado para atualização ou você não tem permissão.');
            return redirect()->to('/lancamentos');
        }

        $transaction->fill($postData);

        $transaction->amount = (float) str_replace(',', '.', $this->request->getPost('amount'));

        if (!$transaction->hasChanged()) {
            session()->setFlashdata('info', 'Nenhuma alteração detectada para o lançamento.');
            return redirect()->to('lancamentos');
        }

        if ($model->save($transaction)) {
            session()->setFlashdata('success', 'Lançamento atualizado com sucesso!');
            return redirect()->to('/lancamentos');
        } else {
            session()->setFlashdata('error', 'Erro ao atualizar o lançamento. Verifique os dados e tente novamente.');
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    public function delete(int $id)
    {
        $model = model(TransactionModel::class);
        $currentUser = auth()->user();

        $transaction = $model->where('id', $id)
            ->where('user_id', $currentUser->id)
            ->first();

        if (empty($transaction)) {
            session()->setFlashdata('error', 'Lançamento não encontrado ou você não tem permissão para excluí-lo.');
            return redirect()->to('/lancamentos');
        }

        if ($model->delete($id)) {
            session()->setFlashdata('success', 'Lançamento excluído com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir lançamento. Tente novamente mais tarde!');
        }

        return redirect()->to('/lancamentos');
    }
}
