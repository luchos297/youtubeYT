<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Articulo Entity.
 *
 * @property int $id
 * @property int $categoria_id
 * @property \App\Model\Entity\Categoria $categoria
 * @property int $portal_id
 * @property \App\Model\Entity\Portal $portal
 * @property string $url
 * @property string $url_rss
 * @property string $titulo
 * @property string $descripcion
 * @property string $texto
 * @property \Cake\I18n\Time $publicado
 * @property bool $habilitado
 * @property \Cake\I18n\Time $creado
 * @property \Cake\I18n\Time $modificado
 * @property bool $tiene_imagen
 * @property bool $tiene_video
 * @property int $visitas
 * @property string $localizacion
 * @property \App\Model\Entity\Image[] $image
 */
class Articulo extends Entity
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
        'imagenes' => true,
        'id' => false,
    ];
}
