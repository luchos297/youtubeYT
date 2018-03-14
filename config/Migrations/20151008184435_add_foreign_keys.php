<?php
use Migrations\AbstractMigration;

class AddForeignKeys extends AbstractMigration
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
    }
    
    public function up()
    {
        $this->table('rsses')
            ->addForeignKey('portal_id', 'portales', 'id')
            ->addForeignKey('categoria_id', 'categorias', 'id')
            ->save();
    }
 
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('rsses')
            ->dropForeignKey('portal_id')
            ->dropForeignKey('categoria_id');
    }
}
