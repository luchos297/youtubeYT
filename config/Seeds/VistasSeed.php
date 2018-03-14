<?php
use Phinx\Seed\AbstractSeed;

/**
 * Vistas seed.
 */
class VistasSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
//        $data = [];
//
//        $table = $this->table('vistas');
//        $table->insert($data)->save();
        $this->execute(
            'INSERT INTO vistas (id, codigo, descripcion,creado, modificado)
             VALUES(1, "HOME", "", NOW(), NULL),
                   (2, "SECCION", "", NOW(), NULL),
                   (3, "RADIOS", "", NOW(), NULL),
                   (4, "TV", "", NOW(), NULL),
                   (5, "NOTA", "", NOW(), NULL),
                   (6, "REVISTAS", "", NOW(), NULL)'
            );
    }
    
    public function down()
    {
        $this->dropTable('vistas');
    }
}
