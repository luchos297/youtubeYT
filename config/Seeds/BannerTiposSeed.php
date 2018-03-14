<?php
use Phinx\Seed\AbstractSeed;

/**
 * BannerTipos seed.
 */
class BannerTiposSeed extends AbstractSeed
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
        $this->execute(
            'INSERT INTO banner_tipos (id, nombre, ancho, alto,creado, modificado)
             VALUES(1, "300x250", 300, 250, NOW(), NULL),
                   (2, "728x90", 728, 90, NOW(), NULL),
                   (3, "468x60", 468, 60, NOW(), NULL),
                   (4, "160x600", 160, 600, NOW(), NULL),
                   (5, "830x90", 830, 90, NOW(), NULL)'
            );
    }
    
    public function down()
    {
        $this->dropTable('banner_tipos');
    }
}
