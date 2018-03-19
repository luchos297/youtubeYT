<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TemasDiaPalabraClave Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PalabraClaves
 */
class TemasDiaPalabraClaveTable extends Table
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

        $this->table('temas_dia_palabra_clave');
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
            ->boolean('clave')
            ->requirePresence('clave', 'create')
            ->notEmpty('clave');
        
        $validator
            ->boolean('actual')
            ->requirePresence('actual', 'create')
            ->notEmpty('actual');

        $validator
            ->dateTime('creado')
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->dateTime('modificado')
            ->allowEmpty('modificado');

        return $validator;
    }
}
