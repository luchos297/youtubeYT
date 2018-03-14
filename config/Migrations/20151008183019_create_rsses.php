<?php
use Migrations\AbstractMigration;

class CreateRsses extends AbstractMigration
{

    public $autoId = false;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('rsses');
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'limit' => 11
        ]);
        $table->addColumn('categoria_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('portal_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('url', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('nombre', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('habilitado', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('creado', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modificado', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
