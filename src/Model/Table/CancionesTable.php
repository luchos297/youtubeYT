<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Canales Model
 *
 */
class CancionesTable extends Table
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
     
        $this->table('canciones');
        $this->displayField('id');
        $this->primaryKey('id');
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
        ->integer('id')
        ->allowEmpty('id', 'create');
        
        $validator
        ->requirePresence('url', 'create')
        ->notEmpty('url');
        
        $validator
        ->requirePresence('video_id', 'create')
        ->notEmpty('video_id');
        
        $validator
        ->requirePresence('duration', 'create')
        ->notEmpty('duration');
        
        $validator
        ->requirePresence('artist', 'create')
        ->notEmpty('artist');
        
        $validator
        ->requirePresence('album', 'create')
        ->notEmpty('album');
        
        $validator
        ->requirePresence('year', 'create')
        ->notEmpty('year');
        
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
        
        return $validator;
    }
}
