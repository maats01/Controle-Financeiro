<?php

namespace App\Models;

use App\Entities\Situation;
use CodeIgniter\Model;

class SituationsModel extends Model
{
    protected $table = 'situations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type',
        'description',
    ];

    protected $returnType = Situation::class;
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
