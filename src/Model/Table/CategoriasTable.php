<?php
namespace App\Model\Table;

use App\Model\Entity\Categoria;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;
/**
 * Categorias Model
 *
 * @property \Cake\ORM\Association\HasMany $Rsses
 */
class CategoriasTable extends Table
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

        $this->table('categorias');
        $this->displayField('nombre');
        $this->primaryKey('id');

        $this->belongsTo('Parent',[
            'className' => 'Categorias',
            'foreignKey' => 'categoria_id'
        ]);

        $this->hasMany('Childs',[
            'className' => 'Categorias',
            'foreignKey' => 'categoria_id',
            'sort' => ['posicion' => 'ASC'],
            'conditions' => ['en_menu' => true],
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Articulos',[
            'foreignKey' => 'categoria_id',
            'dependent' => false,
            'cascadeCallbacks' => true
        ]);

        $this->belongsToMany('PalabrasClaves', [
            'foreignKey' => 'categoria_id',
            'targetForeignKey' => 'palabra_clave_id',
            'joinTable' => 'categoria_palabra_clave',
            'dependent' => false
        ]);

    }

    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options){
        $extra = $this->findByCodigo('EXTRA')->first();
        if($extra != null){
            $this->Articulos->updateAll(
                ['categoria_id' => $extra->id], 
                ['categoria_id' => $entity->id]); 
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
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('modificado');

        $validator
            ->add('en_menu', 'valid', ['rule' => 'boolean'])
            ->requirePresence('en_menu', 'create')
            ->notEmpty('en_menu');

        return $validator;
    }

    public function findEspecial(){
        $query = $this->find('all')
                ->select([
                    'Categorias.nombre',
                    'Categorias.codigo'
                ])
                ->contain(['Parent'])
                ->where(['Categorias.en_especial' => 1])->toArray();

        return $query;
    }

    public function categoriasEnCartelera(){
        $query = $this->find('all')
                ->select([
                    'Categorias.id'
                ])
                ->contain(['Parent', 'PalabrasClaves'])
                ->where(['Categorias.en_cartelera' => 1])->toArray();

        return $query;
    }
}
