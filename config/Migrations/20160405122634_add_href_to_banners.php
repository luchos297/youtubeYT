<?php
use Phinx\Migration\AbstractMigration;

class AddHrefToBanners extends AbstractMigration
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
        $table->addColumn('href', 'string', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
