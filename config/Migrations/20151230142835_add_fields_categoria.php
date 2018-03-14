<?php
use Migrations\AbstractMigration;

class AddFieldsCategoria extends AbstractMigration
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
        $table = $this->table('categorias');
        $table->addColumn('en_menu', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('descripcion', 'string', [
            'default' => null,
            'limit' => 70,
            'null' => true,
        ]);
        $table->addColumn('posicion', 'integer', [
            'default' => 0,
            'limit' => 3,
        ]);
        $table->addColumn('disenio_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('color', 'string', [
            'default' => '#c0c0c0',
            'limit' => 7,
            'null' => false,
        ]);
        $table->update();
    }
}
