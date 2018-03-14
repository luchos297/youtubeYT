<?php
use Migrations\AbstractMigration;

class ModifyTieneVideoToUrlVideo extends AbstractMigration
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
        $table = $this->table('articulos');
        $table->addColumn('url_video', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->removeColumn('tiene_video');
        $table->update();
    }
}
