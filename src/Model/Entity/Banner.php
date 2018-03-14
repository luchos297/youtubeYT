<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Banner Entity.
 *
 * @property int $id
 * @property string $description
 * @property int $banner_id
 * @property string $image_path
 * @property int $view_id
 * @property \App\Model\Entity\View $view
 * @property string $position
 * @property \Cake\I18n\Time $creado
 * @property \Cake\I18n\Time $modificado
 * @property \App\Model\Entity\Banner[] $banners
 */
class Banner extends Entity
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
        'banner_tipos' => true,
        'banner_vista' => true,
        'id' => false,
    ];
}
