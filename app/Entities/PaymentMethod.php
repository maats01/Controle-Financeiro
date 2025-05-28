<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PaymentMethod extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'            => 'integer',
        'description'   => 'string',
        'created_at'    => 'datetime',
        'deleted_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}
