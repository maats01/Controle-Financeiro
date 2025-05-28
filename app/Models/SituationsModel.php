<?php

namespace App\Models;

use CodeIgniter\Model;

class SituationsModel extends Model
{
    protected $table = 'situations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type',
        'description',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
