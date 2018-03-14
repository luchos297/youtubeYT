<?php
use Migrations\AbstractMigration;

class RemovePalabrasClavesFromArticulos extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('articulos');
        if($table->hasColumn('palabras_claves')){
            $table->removeColumn('palabras_claves');
        }
        
    }
    
    public function down()
    {
        $table = $this->table('articulos');
        $table->addColumn('palabras_claves', 'string', [
            'default' => null,
            'limit' => 200,
            'null' => false,
        ]);
    }
}
