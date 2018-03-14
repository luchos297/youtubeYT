<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Banners Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Banners
 * @property \Cake\ORM\Association\BelongsTo $Views
 * @property \Cake\ORM\Association\HasMany $Banners
 */
class BannersTable extends Table
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
            // The name of your upload field
            'filename' => [
                'root' => WWW_ROOT . '/img/images/',
                // The name of the field to store the folder
                'dir' => 'file_url',
            ],
             'filename_mobile' => [
                'root' => WWW_ROOT . '/img/images/',
                // The name of the field to store the folder
                'dir' => 'file_mobile_url',
            ]
        ]);
        
        $this->table('banners');
        $this->displayField('id');
        $this->primaryKey('id');
                
        $this->belongsTo('BannerTipos', [
            'foreignKey' => 'banner_tipos_id',
            'joinType' => 'INNER',
        ]);    
                
        $this->hasMany('BannerVista', [
            'foreignKey' => 'banner_id',
            'dependent' => true                     
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
            ->requirePresence('descripcion', 'create')
            ->allowEmpty('descripcion');
        
        $validator
            ->requirePresence('filename', 'create')
            ->allowEmpty('filename', 'update');
            //->notEmpty('filename'); 
        
        $validator
            ->requirePresence('filename_mobile', 'create')
            ->allowEmpty('filename_mobile', 'update');
            //->notEmpty('filename'); 
        
        $validator
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('modificado');

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
        $rules->add($rules->existsIn(['banner_tipos_id'], 'BannerTipos'));        
        return $rules;
    }
}
