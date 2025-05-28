<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Category;

class CategoriesModel extends Model
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
}
