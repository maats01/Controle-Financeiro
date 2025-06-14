<?php

namespace App\Models;

use App\Entities\Transaction;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type',
        'date',
        'category_id',
        'description',
        'amount',
        'situation_id',
        'payment_method_id',
        'user_id',
    ];

    protected $returnType = Transaction::class;
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

        protected $validationRules = [
        'description'       => 'required|min_length[3]|max_length[255]',
        'amount'            => 'required|numeric|greater_than[0]',
        'type'              => 'required|in_list[0,1]',
        'date'              => 'required|valid_date',
        'category_id'       => 'required|is_natural_no_zero',
        'situation_id'      => 'required|is_natural_no_zero',
        'payment_method_id' => 'required|is_natural_no_zero',
    ];

    protected $validationMessages = [
        'description' => [
            'required'   => 'A descrição do lançamento é obrigatória.',
            'min_length' => 'A descrição deve ter pelo menos 3 caracteres.',
            'max_length' => 'A descrição não pode exceder 255 caracteres.',
        ],
        'amount' => [
            'required'      => 'O valor do lançamento é obrigatório.',
            'numeric'       => 'O valor deve ser um número válido.',
            'greater_than'  => 'O valor deve ser maior que zero.',
        ],
        'type' => [
            'required' => 'O tipo do lançamento (Despesa/Receita) é obrigatório.',
            'in_list'  => 'O tipo selecionado é inválido. Escolha Despesa ou Receita.',
        ],
        'date' => [
            'required'   => 'A data do lançamento é obrigatória.',
            'valid_date' => 'A data informada é inválida.',
        ],
        'category_id' => [
            'required'         => 'A categoria é obrigatória.',
            'is_natural_no_zero' => 'A categoria selecionada é inválida.',
        ],
        'situation_id' => [
            'required'         => 'A situação é obrigatória.',
            'is_natural_no_zero' => 'A situação selecionada é inválida.',
        ],
        'payment_method_id' => [
            'required'         => 'A forma de pagamento é obrigatória.',
            'is_natural_no_zero' => 'A forma de pagamento selecionada é inválida.',
        ],
    ];

    protected $skipValidation = false; // Garante que a validação seja executada automaticamente no save()

    public function getFilteredTransactionsWithDetails($startDate, $endDate, $type, $category_id, $situation_id, $searchString, $paymentMethodId, $userId, $sortBy = 'id', $sortOrder = 'DESC')
    {
        $builder = $this->builder();
        $builder->select('
            transactions.id, 
            transactions.type, 
            transactions.date, 
            categories.name as category_name,
            transactions.description,
            transactions.amount,
            situations.description as situation_desc,
            pm.description as pm_desc,
            transactions.created_at
        ');

        $builder->join('categories', 'transactions.category_id = categories.id', 'left');
        $builder->join('situations', 'transactions.situation_id = situations.id', 'left');
        $builder->join('payment_methods as pm', 'transactions.payment_method_id = pm.id', 'left');

        $validStartDate = isset($startDate) && $startDate != '';
        $validEndDate = isset($endDate) && $endDate != '';

        if ($validStartDate && $validEndDate) {
            $builder->where('transactions.date >=', $startDate);
            $builder->where('transactions.date <=', $endDate);
        } else if ($validStartDate) {
            $builder->where('transactions.date >=', $startDate);
        } else if ($validEndDate) {
            $builder->where('transactions.date <=', $endDate);
        }

        if (isset($type) && $type != '') {
            $builder->where('transactions.type', $type);
        }

        if (isset($category_id) && is_numeric($category_id)) {
            $builder->where('transactions.category_id', (int) $category_id);
        }

        if (isset($situation_id) && is_numeric($situation_id)) {
            $builder->where('transactions.situation_id', (int) $situation_id);
        }

        if (isset($paymentMethodId) && is_numeric($paymentMethodId)) {
            $builder->where('transactions.payment_method_id', (int) $paymentMethodId);
        }

        if (isset($searchString) && $searchString != '') {
            $builder->like('transactions.description', $searchString, 'both');
        }

        $builder->where('transactions.user_id', $userId);
        
        $builder->orderBy($sortBy, $sortOrder);

        return $this;
    }

    public function getLatestTransactions($userId)
    {
        $builder = $this->builder();
        $builder->select('
            transactions.id, 
            transactions.type, 
            transactions.date, 
            categories.name as category_name,
            transactions.description,
            transactions.amount,
            situations.description as situation_desc,
            pm.description as pm_desc,
            transactions.created_at
        ');

        $builder->join('categories', 'transactions.category_id = categories.id', 'left');
        $builder->join('situations', 'transactions.situation_id = situations.id', 'left');
        $builder->join('payment_methods as pm', 'transactions.payment_method_id = pm.id', 'left');

        $builder->where('MONTH(transactions.date)', date('n'));
        $builder->where('YEAR(transactions.date)', date('Y'));
        $builder->where('transactions.user_id', $userId);
        $builder->where('transactions.deleted_at', null);
        
        $builder->orderBy('date', 'desc');
        $query = $builder->get();

        return $query->getResult();
    }

    public function getCurrentRevenue($userId)
    {
        $builder = $this->builder();
        $builder->select('SUM(transactions.amount) as total_value');

        $builder->where('transactions.type', 1);
        $builder->where('MONTH(transactions.date)', date('n'));
        $builder->where('YEAR(transactions.date)', date('Y'));
        $builder->where('transactions.user_id', $userId);
        $builder->where('transactions.deleted_at', null);

        $query = $builder->get();
        $row = $query->getRow();

        if ($row && isset($row->total_value))
        {
            return (float) $row->total_value;
        }

        return 0.0;
    }

    public function getCurrentCosts($userId)
    {
        $builder = $this->builder();
        $builder->select('SUM(transactions.amount) as total_value');

        $builder->where('transactions.type', 0);
        $builder->where('MONTH(transactions.date)', date('n'));
        $builder->where('YEAR(transactions.date)', date('Y'));
        $builder->where('transactions.user_id', $userId);
        $builder->where('transactions.deleted_at', null);

        $query = $builder->get();
        $row = $query->getRow();

        if ($row && isset($row->total_value))
        {
            return (float) $row->total_value;
        }

        return 0.0;
    }

    public function getCurrentYearTransactions($userId)
    {
        $builder = $this->builder();
        $builder->select(
            'DATE_FORMAT(transactions.date, \'%c\') AS month, 
            SUM(CASE WHEN transactions.type = 0 THEN transactions.amount ELSE 0 END) AS expenses,
            SUM(CASE WHEN transactions.type = 1 THEN transactions.amount ELSE 0 END) AS revenues'
        );

        $builder->where('transactions.user_id', $userId);
        $builder->where('YEAR(transactions.date) = YEAR(CURDATE())');
        $builder->where('transactions.deleted_at', null);

        $builder->groupBy(['YEAR(transactions.date)', 'MONTH(transactions.date)', 'month']);
        $builder->orderBy('YEAR(transactions.date)', 'ASC');
        $builder->orderBy('MONTH(transactions.date)', 'ASC');

        $query = $builder->get();

        return $query->getResult();
    }
}
