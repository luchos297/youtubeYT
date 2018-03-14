<?php
use Migrations\AbstractMigration;

class CreatePalabrasClaves extends AbstractMigration
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
        $table = $this->table('palabras_claves');
        $table->addColumn('texto', 'string', [
            'default' => null,
            'limit' => 40,
            'null' => false,
        ]);
        $table->addColumn('creado', 'datetime', [
            'default' => null,
            'null' => true,
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
