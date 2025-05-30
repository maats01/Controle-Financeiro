<?php

namespace App\Models;

use App\Entities\PaymentMethod;
use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'description',
    ];

    protected $returnType = PaymentMethod::class;
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function getFilteredPaymentMethods($searchString = '')
    {
        $builder = $this->builder();
        if ($searchString != '')
        {
            $builder->like('description', $searchString, 'both');
        }

        return $this;
    }
}
