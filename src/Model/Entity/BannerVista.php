<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BannerVistum Entity.
 *
 * @property int $id
 * @property int $banner_id
 * @property \App\Model\Entity\Banner $banner
 * @property int $vista_id
 * @property \App\Model\Entity\Vista $vista
 * @property string $posicion
 */
class BannerVista extends Entity
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
        'banner' => true,
        'vista' => true,
        'id' => false,
    ];
}
