<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


$validator
->requirePresence('fecha_scanned', 'create')
->notEmpty('fecha_scanned');

$validator
->requirePresence('fecha_publish', 'create')
->notEmpty('fecha_publish');

$validator
->requirePresence('image_path', 'create')
->notEmpty('image_path');

$validator
->requirePresence('downloaded', 'create')
->notEmpty('downloaded');

$validator
->dateTime('creado')
->requirePresence('creado', 'create')
->notEmpty('creado');

$validator
->dateTime('modificado')
->requirePresence('modificado', 'create')
->notEmpty('modificado');



/**
 * Canale Entity.
 *
 * @property int $id
 * @property string $url
 * @property string $video_id
 * @property string $duration
 * @property string $artist
 * @property string $album
 * @property integer $year
 * @property datetime $fecha_scanned
 * @property datetime $fecha_publish
 * @property string $image_path
 * @property boolean $downloaded
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
