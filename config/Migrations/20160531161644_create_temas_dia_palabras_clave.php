<?php

use Phinx\Migration\AbstractMigration;

class CreateTemasDiaPalabrasClave extends AbstractMigration
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
        $table = $this->table('temas_dia_palabra_clave');
        $table->addColumn('actual', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('clave', 'string', [
            'default' => null,
            'limit' => 255,
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
        $table->create();
    }
}
