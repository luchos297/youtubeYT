<?php
use Migrations\AbstractMigration;

class CreateVista extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('vistas');
        $table->addColumn('codigo', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('descripcion', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('creado', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modificado', 'datetime', [
            'default' => null,
            'null' => false,
        ]);        
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
