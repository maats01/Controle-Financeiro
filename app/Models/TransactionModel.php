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

    public function getTransactionsWithDetails()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions as t');
        $builder->select('
            t.id, 
            t.type, 
            t.date, 
            categories.name as category_name,
            t.description,
            t.amount,
            situations.description as situation_desc,
            pm.description as pm_desc,
            t.created_at
        ');
        $builder->join('categories', 't.category_id = categories.id', 'left');
        $builder->join('situations', 't.situation_id = situations.id', 'left');
        $builder->join('payment_methods as pm', 't.payment_method_id = pm.id', 'left');

        $query = $builder->get();

        return $query->getResultArray();
    }
}
