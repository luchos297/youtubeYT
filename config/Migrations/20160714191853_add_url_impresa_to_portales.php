<?php
use Migrations\AbstractMigration;

class AddUrlImpresaToPortales extends AbstractMigration
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
        $table->addColumn('url_impresa', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('en_portada', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
