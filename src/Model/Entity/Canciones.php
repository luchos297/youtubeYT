<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Canale Entity.
 *
 * @property int $id
 * @property string $url
 * @property string $video_id
 * @property string $title
 * @property string $$artist
 * @property string $album
 * @property string $duration
 * @property int $year
 * @property string $genre
 * @property int $filesize
 * @property int $sample_rate
 * @property int $bitrate
 * @property string $dataformat
 * @property string $image_path
 * @property boolean $downloaded
 * @property datetime $fecha_publish
 * @property \Cake\I18n\Time $creado
 * @property \Cake\I18n\Time $modificado
 */
class Canale extends Entity
{
    
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
