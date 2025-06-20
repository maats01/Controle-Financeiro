<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Category extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'            => 'integer',
        'type'          => 'boolean',
        'name'          => 'string',
        'created_at'    => 'datetime',
        'deleted_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}
