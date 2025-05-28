<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\SituationsModel;

class SituationsSeeder extends Seeder
{
    public function run()
    {
        $model = model(SituationsModel::class);
        $data = [
            ['type' => 0, 'description' => 'A Pagar'],
            ['type' => 0, 'description' => 'Pago'],
            ['type' => 0, 'description' => 'Vencido'],
            ['type' => 1, 'description' => 'A Receber'],
            ['type' => 1, 'description' => 'Recebido'],
            ['type' => 1, 'description' => 'Vencido'],
        ];

        $model->insertBatch($data);
    }
}
