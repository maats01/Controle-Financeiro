<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type',
        'name',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
