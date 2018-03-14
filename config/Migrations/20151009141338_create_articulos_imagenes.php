<?php
use Migrations\AbstractMigration;

class CreateArticulosImagenes extends AbstractMigration
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
        $table = $this->table('articulo_imagen');
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('articulo_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('imagen_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->addIndex('articulo_id');
        $table->addIndex('imagen_id');
        $table->create();
    }
}
