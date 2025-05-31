<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PaymentMethodModel;

class PaymentMethodsSeeder extends Seeder
{
    public function run()
    {
        $model = model(PaymentMethodModel::class);
        $data = [
            ['description' => 'Dinheiro'],
            ['description' => 'Cartão de Débito'],
            ['description' => 'Pix'],
            ['description' => 'Transferência Bancária'],
            ['description' => 'Cartão de Crédito'],
            ['description' => 'Boleto Bancário'],
            ['description' => 'Cheque'],
        ];

        $model->insertBatch($data);
    }
}
