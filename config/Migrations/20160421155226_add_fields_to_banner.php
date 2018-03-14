<?php

use Phinx\Migration\AbstractMigration;

class AddFieldsToBanner extends AbstractMigration
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
        $table->addColumn('filename_mobile', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('file_mobile_url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        
        $table->addColumn('mobile', 'boolean', [
            'default' => null,
            'null' => false,
        ]);        
        $table->update();
    }
}
