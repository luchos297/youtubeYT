<?php
use Migrations\AbstractMigration;

class AddCodigoToPortales extends AbstractMigration
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
        $table = $this->table('portales');
        $table->addColumn('codigo', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->update();
    }
}
