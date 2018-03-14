<?php
use Migrations\AbstractMigration;

class AddCategoriaToCategorias extends AbstractMigration
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
        $table->addColumn('categoria_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addForeignKey('categoria_id', 'categorias', 'id');
        $table->update();
    }
}
