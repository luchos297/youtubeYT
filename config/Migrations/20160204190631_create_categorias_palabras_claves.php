<?php
use Migrations\AbstractMigration;

class CreateCategoriasPalabrasClaves extends AbstractMigration
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
        $table = $this->table('categoria_palabra_clave');
        $table->addColumn('palabra_clave_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('categoria_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->addIndex('palabra_clave_id');
        $table->addIndex('categoria_id');
        $table->create();
    }
}
