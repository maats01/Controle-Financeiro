<?php

namespace App\Models;

use App\Entities\Situation;
use CodeIgniter\Model;

class SituationModel extends Model
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

    public function getFilteredSituations($searchString = '', $type = null)
    {
        $builder = $this->builder();
        if ($searchString != '')
        {
            $builder->like('description', $searchString, 'both');
        }
        
        if (is_integer($type)) {
            $builder->where('type', $type);
        }

        return $this;
    }
}
