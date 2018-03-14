<?php
use Migrations\AbstractMigration;

class CreateArticulosPalabrasClaves extends AbstractMigration
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
        $table = $this->table('articulo_palabra_clave');
        $table->addColumn('articulo_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('palabra_clave_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->addIndex('palabra_clave_id');
        $table->addIndex('articulo_id');
        $table->create();
    }
}
