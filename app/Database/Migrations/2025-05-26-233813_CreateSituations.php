<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSituations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'type'        => ['type' => 'BOOLEAN'],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('situations');
    }

    public function down()
    {
        $this->forge->dropTable('situations');
    }
}
