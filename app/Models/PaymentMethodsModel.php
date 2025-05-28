<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodsModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'description',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
