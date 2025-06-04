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

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'type' => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'O campo "Nome da Categoria" é obrigatório.',
            'min_length' => 'O campo "Nome da Categoria" deve ter pelo menos 3 caracteres.',
            'max_length' => 'O campo "Nome da Categoria" não pode exceder 255 caracteres.',
        ],
        'type' => [
            'required' => 'O campo "Tipo" é obrigatório.',
            'in_list'  => 'O tipo de categoria selecionado é inválido. Por favor, escolha Despesa ou Receita.',
        ],
    ];
    protected $skipValidation = false;

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
