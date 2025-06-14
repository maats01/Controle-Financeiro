<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
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

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
