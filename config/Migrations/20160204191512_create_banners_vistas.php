<?php
use Migrations\AbstractMigration;

class CreateBannersVistas extends AbstractMigration
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
        $table = $this->table('banner_vista');                
        $table->addColumn('banner_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('vista_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);        
        $table->addColumn('posicion', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);        
        $table->addPrimaryKey([
            'id',
        ]);        
        $table->addIndex('banner_id');
        $table->addIndex('vista_id');
        $table->create();
    }
}
