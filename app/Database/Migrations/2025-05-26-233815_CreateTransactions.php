<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'type'               => ['type' => 'ENUM', 'constraint' => ['receita', 'despesa']],
            'date'               => ['type' => 'DATE'],
            'category_id'        => ['type' => 'INT', 'unsigned' => true],
            'description'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'amount'             => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'situation_id'       => ['type' => 'INT', 'unsigned' => true],
            'payment_method_id'  => ['type' => 'INT', 'unsigned' => true],
            'user_id'            => ['type' => 'INT', 'unsigned' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id');
        $this->forge->addForeignKey('situation_id', 'situations', 'id');
        $this->forge->addForeignKey('payment_method_id', 'payment_methods', 'id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
