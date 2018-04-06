<?php
use Migrations\AbstractMigration;

class CreateCanciones extends AbstractMigration
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
        $table = $this->table('canciones');
        $table->addColumn('url_yt', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('video_id', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('artist', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('album', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('duration', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);        
        $table->addColumn('year', 'integer', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('image_path', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('downloaded', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('fecha_publish', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('genre', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]); 
        $table->addColumn('filesize', 'string', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]); 
        $table->addColumn('sample_rate', 'integer', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]); 
        $table->addColumn('bitrate', 'integer', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]); 
        $table->addColumn('dataformat', 'string', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]);
        $table->addColumn('quality', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('url_yt_download', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('filename', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'id',
        ]);
        $table->create();
    }
}
