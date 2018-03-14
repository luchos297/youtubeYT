<?php
namespace App\Model\Table;

use App\Model\Entity\Vista;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vistas Model
 *
 * @property \Cake\ORM\Association\HasMany $BannerVista
 * @property \Cake\ORM\Association\HasMany $Banners
 */
class VistasTable extends Table
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

        $this->table('vistas');
        $this->displayField('codigo');
        $this->primaryKey('id');

        $this->belongsTo('BannerVista', [
            'foreignKey' => 'vista_id',
            'dependent' => true,
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
            ->requirePresence('codigo', 'create')
            ->notEmpty('codigo');

        $validator
            ->requirePresence('descripcion', 'create')
            ->notEmpty('descripcion');

        $validator
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->requirePresence('modificado', 'create')
            ->notEmpty('modificado');

        return $validator;
    }
}
