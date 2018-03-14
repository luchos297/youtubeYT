<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TemasDiaPalabraClave Entity.
 *
 * @property int $id
 * @property bool $actual
 * @property int $palabra_clave_id
 * @property \App\Model\Entity\PalabraClave $palabra_clave
 * @property \Cake\I18n\Time $creado
 * @property \Cake\I18n\Time $modificado
 */
class TemasDiaPalabraClave extends Entity
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
