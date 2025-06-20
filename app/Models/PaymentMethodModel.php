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

    protected $validationRules = [
        'description' => 'required|min_length[3]|max_length[255]'
    ];

    protected $validationMassages = [
        'description' => [
            'required'   => 'O campo "Novo método de pagamento" é obrigatório',
            'min_length' => 'O campo "Novo método de pagamento" deve ter pelo menos 3 caracteres',
            'max_lenght' => 'O campo "Novo método de pagamento" não deve exceder 255 caracteres'
        ]
    ];
    protected $skipValidation = false;

    public function getFilteredPaymentMethods($searchString = '', $sortBy = 'id', $sortOrder = 'DESC')
    {
        $builder = $this->builder();
        if ($searchString != '')
        {
            $builder->like('description', $searchString, 'both');
        }

        $builder->orderBy($sortBy, $sortOrder);

        return $this;
    }
}
