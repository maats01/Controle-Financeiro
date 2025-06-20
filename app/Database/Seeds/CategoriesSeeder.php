<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CategoryModel;


class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $model = model(CategoryModel::class);
        $data = [
            ['type' => 0, 'name' => 'Lazer e Entretenimento'],
            ['type' => 0, 'name' => 'Alimentação'],
            ['type' => 0, 'name' => 'Saúde'],
            ['type' => 0, 'name' => 'Educação'],
            ['type' => 0, 'name' => 'Seguros'],
            ['type' => 0, 'name' => 'Moradia'],
            ['type' => 0, 'name' => 'Transporte'],
            ['type' => 0, 'name' => 'Contas de Consumo'],
            ['type' => 0, 'name' => 'Impostos e Taxas'],
            ['type' => 0, 'name' => 'Cuidados Pessoais'],
            ['type' => 0, 'name' => 'Dívidas'],
            ['type' => 0, 'name' => 'Vestuários e Acessórios'],
            ['type' => 1, 'name' => 'Salário'],
            ['type' => 1, 'name' => 'Investimentos'],
            ['type' => 1, 'name' => 'Renda Extra'],
            ['type' => 1, 'name' => 'Outras Fontes'],
        ];

        $model->insertBatch($data);
    }
}
