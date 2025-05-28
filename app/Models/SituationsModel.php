<?php

namespace App\Models;

use CodeIgniter\Model;

class SituationsModel extends Model
{
    protected $table = 'situations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
