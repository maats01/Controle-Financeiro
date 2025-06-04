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

    protected $validationRules = [
        'description' => 'required|min_length[3]|max_length[255]',
        'type' => 'required|in_list[0,1]',
    ];

    protected $validationMassages = [
        'description' => [
            'required'   => 'O campo "Descrição da situação" é obrigatório',
            'min_length' => 'O campo "Descrição da situação" deve ter pelo menos 3 caracteres.',
            'max_length' => 'O campo "Descrição da situação" não deve exceder 255 caracteres',
        ],
        'type' => [
            'required'  => 'O campo "Tipo" é obrigatório',
            'in_list'   => 'O tipo de situação selecionada é inválida. Por favor, escolha Despesa ou Receita'
        ], 
    ];
    protected $skipValidation = false;

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
