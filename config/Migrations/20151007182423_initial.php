<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('usuarios');
        
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);        
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('creado', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ]);
        $table->addColumn('modificado', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ]);
        $table->create();

    }

    public function down()
    {
        $this->dropTable('usuarios');
    }
}
