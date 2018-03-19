<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use ArrayObject;

/**
 * Portales Model
 *
 */
class PortalesTable extends Table
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

        /*$this->addBehavior('Proffer.Proffer', [
            'Imagen.filename' => [
                'dir' => 'Imagen.file_url',
                'thumbnailSizes' => [
                    //'square' => ['w' => 100, 'h' => 100],
                    //'large' => ['w' => 250, 'h' => 250]
                ]
            ]
        ]);*/

        $this->table('portales');
        $this->displayField('nombre');
        $this->primaryKey('id');

        $this->belongsTo('Imagenes', [
            'foreignKey' => 'imagen_id',
            'joinType' => 'LEFT',
            'dependent' => false,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('Articulos',[
            'foreignKey' => 'categoria_id',
            'dependent' => false,
            'cascadeCallbacks' => true
        ]);
    }

    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options){
        if(!empty($entity->imagen)){
            $this->Imagenes->delete($entity->imagen);
        }
    }

    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options){
        $extra = $this->findByCodigo('EXTRA')->first();
        if($extra != null){
            $this->Articulos->updateAll(
                ['portal_id' => $extra->id],
                ['portal_id' => $entity->id]);
        }
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
            ->requirePresence('nombre', 'create')
            ->notEmpty('nombre');

        $validator
            ->requirePresence('codigo', 'create')
            ->notEmpty('codigo');

        $validator->add('codigo', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table'
        ]);

        $validator
            ->requirePresence('url', 'create')
            ->notEmpty('url');

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
