<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminUserSeeder');
        $this->call('CategoriesSeeder');
        $this->call('SituationsSeeder');
        $this->call('PaymentMethodsSeeder');
        $this->call('TransactionsSeeder');
    }
}
