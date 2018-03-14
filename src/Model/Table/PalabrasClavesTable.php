<?php
namespace App\Model\Table;

use App\Model\Entity\PalabrasClave;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PalabrasClaves Model
 *
 */
class PalabrasClavesTable extends Table
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

        $this->table('palabras_claves');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsToMany('Articulos', [
            'foreignKey' => 'palabra_clave_id',
            'targetForeignKey' => 'articulo_id',
            'joinTable' => 'articulo_palabra_clave'             
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
            ->requirePresence('texto', 'create')
            ->notEmpty('texto');

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
