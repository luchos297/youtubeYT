<?php
namespace App\Model\Table;

use App\Model\Entity\BannerTipo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BannerTipos Model
 *
 */
class BannerTiposTable extends Table
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

        $this->table('banner_tipos');
        $this->displayField('nombre');
        $this->primaryKey('id');
        
        
        $this->belongsTo('Banners', [
            'foreignKey' => 'banner_tipos_id',
            'targetForeignKey' => 'banner_id'
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
            ->add('id', 'valid', ['rule' => 'integer'])
            ->requirePresence('alto', 'create')
            ->notEmpty('alto');

        $validator
            ->add('id', 'valid', ['rule' => 'integer'])
            ->requirePresence('ancho', 'create')
            ->notEmpty('ancho');

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
