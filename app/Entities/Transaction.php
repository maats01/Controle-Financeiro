<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Transaction extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'                => 'integer',
        'type'              => 'string',
        'date'              => 'datetime',
        'category_id'       => 'integer',
        'description'       => 'string',
        'amount'            => 'float',
        'situation_id'      => 'integer',
        'payment_method_id' => 'integer',
        'user_id'           => 'integer',
        'created_at'        => 'datetime',
        'deleted_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
