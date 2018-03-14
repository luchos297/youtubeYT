<?php
use Migrations\AbstractMigration;

class CreateBanner extends AbstractMigration
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
        $table = $this->table('banners');        
        $table->addColumn('descripcion', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('banner_tipos_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('filename', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('file_url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('creado', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modificado', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
