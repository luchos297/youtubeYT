<?php
namespace App\Model\Table;

use App\Model\Entity\Usuario;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validator;

/**
 * Usuarios Model
 *
 */
class UsuariosTable extends Table
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

        $this->table('usuarios');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->add('password',[
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'La contraseña debe tener al menos 6 caracteres.',
                ]
            ])
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->add('password2',[
                'match'=>[
                    'rule'=> ['compareWith','password'],
                    'message'=>'La contraseña no coincide.',
                ]
            ])
            ->notEmpty('password1');
        
        $validator
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('modificado');

        return $validator;
    }

    
    public function validationPassword(Validator $validator )
    { 
        $validator
            ->add('old_password','custom',[
                'rule'=>  function($value, $context){
                    $usuario = $this->get($context['data']['id']);
                    if ($usuario) {
                        if ((new DefaultPasswordHasher)->check($value, $usuario->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message'=>'No coincide con la contraseña actual.',
            ])
            ->notEmpty('old_password');
 
        $validator
            ->add('password1', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'La contraseña debe tener al menos 6 caracteres.',
                ]
            ])
            /*->add('password1',[
                'match'=>[
                    'rule'=> ['compareWith','password2'],
                    'message'=>'The passwords does not match!',
                ]
            ])*/
            ->notEmpty('password1');
        $validator
            /*->add('password2', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'The password have to be at least 6 characters!',
                ]
            ])*/
            ->add('password2',[
                'match'=>[
                    'rule'=> ['compareWith','password1'],
                    'message'=>'La contraseña no coincide.',
                ]
            ])
            ->notEmpty('password2');
 
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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
