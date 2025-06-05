<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'type'              => '1',
                'date'              => '2025-01-15',
                'category_id'       => 13,
                'description'       => 'Salário Mensal Janeiro',
                'amount'            => 3500.00,
                'situation_id'      => 5,
                'payment_method_id' => 4,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-01-20',
                'category_id'       => 2,
                'description'       => 'Compras Supermercado Semanal',
                'amount'            => 150.75,
                'situation_id'      => 2,
                'payment_method_id' => 5,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-02-05',
                'category_id'       => 7,
                'description'       => 'Combustível Carro',
                'amount'            => 200.00,
                'situation_id'      => 2, 
                'payment_method_id' => 1,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '1',
                'date'              => '2025-02-10',
                'category_id'       => 15,
                'description'       => 'Projeto Web Design Cliente X',
                'amount'            => 850.00,
                'situation_id'      => 4,
                'payment_method_id' => 4,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-03-01',
                'category_id'       => 6,
                'description'       => 'Aluguel Março',
                'amount'            => 1200.00,
                'situation_id'      => 2,
                'payment_method_id' => 4,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-03-12',
                'category_id'       => 2,
                'description'       => 'Jantar Restaurante Y',
                'amount'            => 85.50,
                'situation_id'      => 2,
                'payment_method_id' => 5,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '1',
                'date'              => '2025-06-05',
                'category_id'       => 15,
                'description'       => 'Bônus Trimestral',
                'amount'            => 500.00,
                'situation_id'      => 5,
                'payment_method_id' => 4,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-06-18',
                'category_id'       => 1,
                'description'       => 'Ingressos Cinema',
                'amount'            => 60.00,
                'situation_id'      => 2,
                'payment_method_id' => 3, 
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '1',
                'date'              => '2025-06-02',
                'category_id'       => 15,
                'description'       => 'Consultoria Empresa Z',
                'amount'            => 1250.00,
                'situation_id'      => 5,
                'payment_method_id' => 4,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
            [
                'type'              => '0',
                'date'              => '2025-06-25',
                'category_id'       => 3,
                'description'       => 'Consulta Médica',
                'amount'            => 300.00,
                'situation_id'      => 1,
                'payment_method_id' => 5,
                'user_id'           => 1,
                'created_at'        => Time::now()->toDateTimeString(),
                'updated_at'        => Time::now()->toDateTimeString(),
            ],
        ];

        foreach ($data as $record) {
            $this->db->table('transactions')->insert($record);
        }
    }
}
