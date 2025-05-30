<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Category;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'type',
        'name',
    ];

    protected $returnType = Category::class;
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function getFilteredCategories($searchString = '', $type = null)
    {
        $builder = $this->builder();
        if ($searchString != '')
        {
            $builder->like('name', $searchString, 'both');
        }
        if (is_integer($type))
        {
            $builder->where('type', $type);
        }

        return $this;
    }
}
