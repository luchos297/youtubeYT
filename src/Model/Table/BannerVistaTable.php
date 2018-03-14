<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BannerVista Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Banners
 * @property \Cake\ORM\Association\BelongsTo $Vistas
 */
class BannerVistaTable extends Table
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

        $this->table('banner_vista');
        $this->displayField('id');
        $this->primaryKey('id');
                
        $this->belongsTo('Banners', [            
            'foreignKey' => 'banner_id',
            'joinType' => 'INNER',             
        ]); 
        
        $this->belongsTo('Vistas', [
            'foreignKey' => 'vista_id',
            'joinType' => 'INNER'           
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
            ->requirePresence('posicion', 'create')
            ->notEmpty('posicion');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['banner_id'], 'Banners'));
        $rules->add($rules->existsIn(['vista_id'], 'Vistas'));
        return $rules;
    }
}
