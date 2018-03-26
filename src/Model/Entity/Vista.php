<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vista Entity.
 *
 * @property int $id
 * @property string $codigo
 * @property string $descripcion
 * @property \Cake\I18n\Time $creado
 * @property \Cake\I18n\Time $modificado
 * @property \App\Model\Entity\BannerVistum[] $banner_vista
 * @property \App\Model\Entity\Banner[] $banners
 */
class Vista extends Entity
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
        'banner_vista' => true,
        'id' => false,
    ];
}