<?php
namespace App\Model\Table;

use App\Model\Entity\Imagen;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Imagenes Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Articulos
 */
class ImagenesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
                $this->addBehavior('Proffer.Proffer', [
            'filename' => [
                'dir' => 'file_url',
                'thumbnailSizes' => [
                    //'square' => ['w' => 100, 'h' => 100],
                    //'large' => ['w' => 250, 'h' => 250]
                ]
            ]
        ]);

        $this->table('imagenes');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('Articulos', [
            'foreignKey' => 'imagen_id',
            'targetForeignKey' => 'articulo_id',
            'joinTable' => 'articulo_imagen'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('filename', 'create')
            ->notEmpty('filename');

        $validator
            ->allowEmpty('descripcion');

        $validator
            ->allowEmpty('comentario');

        $validator
            ->allowEmpty('url');

        $validator
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('modificado');

        return $validator;
    }
}
