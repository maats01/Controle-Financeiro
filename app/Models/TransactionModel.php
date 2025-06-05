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

    public function getFilteredTransactionsWithDetails($startDate, $endDate, $type, $category_id, $situation_id, $searchString, $userId, $sortBy = 'id', $sortOrder = 'DESC')
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

        $query = $builder->get();
        $row = $query->getRow();

        if ($row && isset($row->total_value))
        {
            return (float) $row->total_value;
        }

        return 0.0;
    }
}
