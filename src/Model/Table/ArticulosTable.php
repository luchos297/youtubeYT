<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use ArrayObject;

/**
 * Articulos Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Categorias
 * @property \Cake\ORM\Association\BelongsTo $Portales
 * @property \Cake\ORM\Association\BelongsToMany $Imagenes
 */
class ArticulosTable extends Table
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

        $this->table('articulos');
        $this->displayField('titulo');
        $this->primaryKey('id');

        $this->belongsTo('Categorias', [
            'foreignKey' => 'categoria_id',
            'joinType' => 'INNER',
            'dependent' => false,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('Portales', [
            'foreignKey' => 'portal_id',
            'joinType' => 'INNER',
            //'cascadeCallbacks' => false,
        ]);

        $this->belongsToMany('Imagenes', [
            'foreignKey' => 'articulo_id',
            'targetForeignKey' => 'imagen_id',
            'joinTable' => 'articulo_imagen',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsToMany('PalabrasClaves', [
            'foreignKey' => 'articulo_id',
            'targetForeignKey' => 'palabra_clave_id',
            'joinTable' => 'articulo_palabra_clave',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options){
        if(count($entity->imagenes) > 0){
            foreach($entity->imagenes as $imagen){
                $this->Imagenes->delete($imagen);
            }
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
            ->allowEmpty('url');

        $validator
            ->allowEmpty('url_rss');

        $validator
            ->requirePresence('titulo', 'create')
            ->notEmpty('titulo');

        $validator
            ->requirePresence('descripcion', 'create')
            ->notEmpty('descripcion');

        $validator
            ->requirePresence('texto', 'create')
            ->notEmpty('texto');

        $validator
            ->add('publicado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publicado');

        $validator
            ->add('habilitado', 'valid', ['rule' => 'boolean'])
            ->requirePresence('habilitado', 'create')
            ->notEmpty('habilitado');

        $validator
            ->add('creado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('creado');

        $validator
            ->add('modificado', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('modificado');

        $validator
            ->add('tiene_imagen', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('tiene_imagen');

        $validator
            ->add('tiene_video', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('tiene_video');

        $validator
            ->add('visitas', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('visitas');

        $validator
            ->allowEmpty('localizacion');

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
        $rules->add($rules->existsIn(['categoria_id'], 'Categorias'));
        $rules->add($rules->existsIn(['portal_id'], 'Portales'));
        return $rules;
    }

    public function findUltima(){
        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Portales.nombre'])
                ->where([
                    'Articulos.habilitado',
                    'Categorias.nombre' => 'NACIONALES'
                    ])
                ->orWhere([
                    'Categorias.nombre' => 'PROVINCIALES',
                    'Categorias.nombre' => 'POLITICA',
                    'Categorias.nombre' => 'ECONOMIA'
                    ])
                ->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order(['Articulos.creado' => 'DESC'])
                ->first();

        return $query;
    }

    public function findCarousel(array $not_include = [], $parent = null, $limit = 0){
        $this->parent = $parent;
        $this->limite = $limit;
        $this->not_include = $not_include;

        $not_in = '';
        $limite = '';

        if(count($not_include) > 0){
            $not_in = ' AND articulos.id NOT IN (' . implode(", ", $this->not_include) . ')';
        }
        if($this->limite > 0){
            $limite = 'LIMIT ' . $this->limite;
        }

        $connection = ConnectionManager::get('default');
        $query = $connection->execute(
                    'SELECT * FROM (
                        SELECT articulos.id AS `id`, articulos.titulo AS `titulo`, articulos.publicado AS `publicado`, articulos.habilitado AS `habilitado`, Categorias.nombre AS `Categoria__nombre`, Categorias.codigo AS `Categoria__codigo`, Portales.codigo AS `Portal__codigo`, Portales.nombre AS `Portal__nombre`, Imagenes.filename AS `Imagen__filename`, Imagenes.file_url AS `Imagen__file_url`
                        FROM articulos
                        INNER JOIN portales Portales ON Portales.id = (articulos.portal_id)
                        INNER JOIN categorias Categorias ON Categorias.id = (articulos.categoria_id)
                        LEFT JOIN articulo_imagen ArticuloImagen ON ArticuloImagen.articulo_id = (articulos.id)
                        LEFT JOIN imagenes Imagenes ON Imagenes.id = (ArticuloImagen.imagen_id)
                        WHERE articulos.id > (SELECT MAX(articulos.id) - 600 FROM articulos) AND habilitado = 1 AND Categorias.categoria_id = ' . $this->parent . $not_in . '
                        ORDER BY articulos.publicado DESC) AS t1
                     GROUP BY t1.Portal__codigo
                     ORDER BY t1.publicado DESC ' . $limite
                )
                ->fetchAll('assoc');

        return $query;
    }

    public function findSeccion($categoria, $limit, array $not_include = []){
        $this->limite = $limit;
        $this->not_include = $not_include;
        $this->categoria = $categoria;

        $not_in = '';
        $limite = '';

        if(count($not_include) > 0){
            $not_in = ' AND articulos.id NOT IN (' . implode(", ", $this->not_include) . ')';
        }
        if($this->limite > 0){
            $limite = 'LIMIT ' . $this->limite;
        }

        $connection = ConnectionManager::get('default');
        $query = $connection->execute(
                    'SELECT articulos.id AS `id`, articulos.titulo AS `titulo`, articulos.descripcion AS `descripcion`, articulos.publicado AS `publicado`, articulos.habilitado AS `habilitado`, Categorias.nombre AS `Categoria__nombre`, Categorias.codigo AS `Categoria__codigo`, Portales.codigo AS `Portal__codigo`, Portales.nombre AS `Portal__nombre`, MIN(Imagenes.filename) AS `Imagen__filename`, MIN(Imagenes.file_url) AS `Imagen__file_url`
                     FROM articulos
                     LEFT JOIN articulo_imagen ArticuloImagen ON ArticuloImagen.articulo_id = (articulos.id)
                     LEFT JOIN imagenes Imagenes ON Imagenes.id = (ArticuloImagen.imagen_id)
                     LEFT JOIN portales Portales ON Portales.id = articulos.portal_id
                     LEFT JOIN categorias Categorias ON Categorias.id = articulos.categoria_id
                     WHERE articulos.id > (SELECT MAX(articulos.id) - 3000 FROM articulos) AND Categorias.codigo = "' . $this->categoria . '"' . $not_in . '
                     GROUP BY `id`, `titulo`, `publicado`, `habilitado`, `Categoria__nombre`, `Categoria__codigo`, `Portal__codigo`, `Portal__nombre`
                     ORDER BY articulos.publicado DESC ' . $limite
                )
                ->fetchAll('assoc');

        return $query;

        /*$query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Portales.nombre',
                    'Portales.codigo'])
                ->where([
                    'Articulos.habilitado',
                    'Categorias.categoria_id' => $this->parent
                    ]);

        if($this->categoria != null){
            $query = $query->where(['Categorias.codigo' => $this->categoria]);
        }

        if(count ($this->not_include) > 0){
            $query = $query->where(function ($exp, $q) {
                    return $exp->notIn('Articulos.id', $this->not_include);
                });
        }

        $query = $query->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order(['Articulos.publicado' => 'DESC'])
                ->limit($this->limite);

        return $query;*/
    }

    public function findParte1(array $not_include){
        $this->not_include = $not_include;

        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Portales.nombre',
                    'Portales.codigo'])
                ->where([
                    'Articulos.habilitado',
                    'OR' => [['Categorias.codigo' => 'PROVINCIALES'], ['Categorias.codigo' => 'NACIONALES']]
                    ])
                ->where(function ($exp, $q) {
                    return $exp->notIn('Articulos.id', $this->not_include);
                })
                ->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order(['Articulos.publicado' => 'DESC'])
                ->limit(6);

        return $query;
    }

    public function findDestacadasPorPortal(array $not_include = [],array $categorias = [], $parent = null, $limit = 1){
        $this->parent = $parent;
        $this->limite = $limit;
        $this->not_include = $not_include;
        $this->categorias = $categorias;

        $not_in = '';
        $categoria_in = '';

        if(count ($this->not_include) > 0){
            $not_in = " AND Articulos.id NOT IN (".implode(',', $this->not_include).")";
        }
        if(count ($this->categorias) > 0){
            $categoria_in = " AND Articulos.categoria_id IN (".implode(',', $this->categorias).")";
        }

        $connection = ConnectionManager::get('default');
        $query = $connection
                ->execute(
                        "SELECT * FROM ("
                        . "SELECT Articulos.id, Articulos.titulo AS `titulo`, Articulos.publicado AS `publicado`, Articulos.descripcion AS `descripcion`, Categorias.nombre AS `Categoria__nombre`, Categorias.codigo AS `Categoria__codigo`, Portales.codigo AS `Portal__codigo`, Portales.nombre AS `Portal__nombre`, Imagenes.filename AS `Imagen__filename`, Imagenes.file_url AS `Imagen__file_url` "
                        . "FROM articulos Articulos "
                        . "INNER JOIN portales Portales ON Portales.id = (Articulos.portal_id) "
                        . "INNER JOIN categorias Categorias ON Categorias.id = (Articulos.categoria_id) "
                        . "LEFT JOIN articulo_imagen ArticuloImagen ON ArticuloImagen.articulo_id = (Articulos.id) "
                        . "LEFT JOIN imagenes Imagenes ON Imagenes.id = (ArticuloImagen.imagen_id) "
                        . "WHERE (Articulos.habilitado = 1 AND Categorias.categoria_id = ".$this->parent." ".$not_in . $categoria_in. ") "
                        . "ORDER BY Articulos.publicado DESC) as t1 "
                        . "GROUP BY t1.Portal__codigo "
                        . "ORDER BY t1.publicado DESC "
                        . "LIMIT ".$this->limite
                )
                ->fetchAll('assoc');

        return $query;
    }

    /*public function filterCantidadMaximaPorPortal(array $noticias, $limite_noticias, $limite_repetidas){
        //armamos arreglo con el portal de cada noticia y su repeticion
        $portales_parte = [];
        $parte_final = [];
        foreach($noticias as $key => $parte_noticia) {
            if(count($parte_final) == $limite_noticias){
                break;
            }
            if($key > 0) {
                //verificamos si se encuentra en el arreglo de noticias temporal
                $portal_codigo_noticia_a_validar = "";
                $portal_codigo_noticia_en_arreglo = "";
                $indice_noticia_en_arreglo = 0;
                foreach ($portales_parte as $index => $portal) {
                    if(in_array($parte_noticia->portal->codigo, $portales_parte[$index]) == true) {
                        $indice_noticia_en_arreglo = $index;
                    }
                }
                //si esta
                if(isset($indice_noticia_en_arreglo) && $indice_noticia_en_arreglo >= 0) {
                    $portal_codigo_noticia_en_arreglo = $portales_parte[$indice_noticia_en_arreglo]['portal'];                     
                }
                //sino existe
                else {
                    $portal_codigo_noticia_en_arreglo = "";
                }

                $portal_codigo_noticia_a_validar = $parte_noticia->portal->codigo;
                if(!isset($portales_parte[$indice_noticia_en_arreglo]['cantidad'])){
                    $portales_parte[$indice_noticia_en_arreglo]['cantidad'] = 0;
                }
                $cantidad_noticia_a_validar = $portales_parte[$indice_noticia_en_arreglo]['cantidad'];
                //Si esta, aumentamos el contador y lo agregamos en el arreglo final
                if($portal_codigo_noticia_a_validar == $portal_codigo_noticia_en_arreglo && $cantidad_noticia_a_validar < $limite_repetidas) {
                    $portales_parte[$indice_noticia_en_arreglo]['cantidad'] += 1;
                    $parte_final[$key] = $parte_noticia;
                }
                //sino, solo aumentamos el contador
                elseif($portal_codigo_noticia_a_validar == $portal_codigo_noticia_en_arreglo && $cantidad_noticia_a_validar >= $limite_repetidas) {
                    if(!isset($indice_noticia_en_arreglo)){
                        $portales_parte[$indice_noticia_en_arreglo]['cantidad'] = 0;
                        $portales_parte[$indice_noticia_en_arreglo]['cantidad'] += 1;
                    }
                    else {
                        $portales_parte[$indice_noticia_en_arreglo]['cantidad'] += 1;
                    }
                }
                //sino esta, lo agregamos como uno nuevo
                else {
                    $portales_parte[$key]['portal'] = $parte_noticia->portal->codigo;
                    $portales_parte[$key]['cantidad'] = 0;
                    $portales_parte[$key]['cantidad'] += 1;
                    $parte_final[$key] = $parte_noticia;
                }
            }
            //agregamos el primero al arreglo
            else {
                $portales_parte[$key]['portal'] = $parte_noticia->portal->codigo;
                $portales_parte[$key]['cantidad'] = 0;
                $portales_parte[$key]['cantidad'] += 1;
                $parte_final[$key] = $parte_noticia;
            }
        }
        return $parte_final;
    }*/

    public function findArticuloPorTag(array $not_include = [], array $palabras = [], $parent = null, $limit = 1){
        $this->parent = $parent;
        $this->limite = $limit;
        $this->not_include = $not_include;
        $this->palabras = $palabras;

        $not_in = '';
        $categoria_in = '';

        if(count ($this->not_include) > 0){
            $not_in = " AND Articulos.id NOT IN (".implode(',', $this->not_include).")";
        }
        if(count ($this->palabras) > 0){
            $palabras_in = " AND PalabrasClaves.texto IN ('".implode(',', $this->palabras)."')";
        }

        $query = $this->find()
                ->select([
                    'DISTINCT Articulos.id',
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Categorias.color',
                    'Portales.nombre'
                ])
                ->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order(['Articulos.publicado' => 'DESC'])
                ->matching('PalabrasClaves', function($q) {
                    return $q->where(['PalabrasClaves.texto IN' => $this->palabras]);
            });

        return $query;
    }

    public function findMasLeidosPorCategoria($categoria, $limite){
        $this->limit = $limite;
        $this->parent = $categoria;

        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Portales.nombre',
                    'Portales.codigo'
                    ])
                ->where([
                    'Articulos.habilitado',
                    'Articulos.publicado >' => new \DateTime('-5 days'),
                    'Categorias.codigo' => $this->parent])
                ->contain(['Categorias', 'Imagenes', 'Portales'
                    ])
                ->order([
                    'Articulos.visitas' => 'DESC'
                    ])
                ->limit($this->limit);

        return $query;
    }

    public function findMasLeidos(array $not_include, $parent, $limit){
        $this->not_include = $not_include;
        $this->limit = $limit;
        $this->parent = $parent; 

        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Portales.nombre',
                    'Portales.codigo'])
                ->where([
                    'Articulos.habilitado',
                    'Articulos.publicado >' => new \DateTime('-5 days'),
                    'Categorias.categoria_id' => $this->parent
                    ])
                ->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order([
                    'Articulos.visitas' => 'DESC',
                    'Articulos.publicado' => 'DESC'
                    ])
                ->limit($this->limit);
        return $query;
    }

    public function findArticuloPorPortal(array $not_include = [], $portal = null, $parent = null, $limit = 1){
        $this->not_include = $not_include;
        $this->limit = $limit;
        $this->parent = $parent;
        $this->portal = $portal;

        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Portales.nombre',
                    'Portales.codigo'])
                ->where([
                    'Articulos.habilitado',
                    'Portales.id' => $this->portal,
                    ])
                ->contain(['Imagenes', 'Portales', 'Categorias'])
                ->order([
                    'Articulos.publicado' => 'DESC'
                    ])
                ->limit($this->limit);

        if(count ($this->not_include) > 0){
            $query = $query->where(function ($exp, $q) {
                        return $exp->notIn('Articulos.id', $this->not_include);
                    });
        }

        return $query;
    }

    public function findArticulosAPublicar(array $not_include, $fecha, $parent = null, $limit = 1){
        $this->not_include = $not_include;
        $this->parent = $parent;
        $this->limit = $limit;

        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.publicado_fb'
                    ])
                ->where([
                    'Articulos.habilitado',
                    'Articulos.publicado_fb' => 0,
                    'Articulos.publicado >' => $fecha,
                    'Categorias.categoria_id' => $this->parent
                    ])
                ->contain(['Categorias'])
                ->order([
                    'Articulos.publicado' => 'DESC'
                    ])
                ->limit($this->limit);

        return $query;
    }

    public function findUltimaPorCategoria($categoria_id){
        $query = $this->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Categorias.color'
                    ])
                ->where([
                    'Articulos.habilitado',
                    'Categorias.id' => $categoria_id
                    ])
                ->contain(['Categorias'])
                ->order([
                    'Articulos.publicado' => 'DESC'
                    ])
                ->first();

        return $query;
    }

    public function FindById($articulo_id){
        $query = $this->find()
                ->where([
                    'Articulos.habilitado',
                    'Articulos.id' => $articulo_id
                    ])
                ->contain(['Categorias', 'Portales', 'Imagenes'])
                ->first();

        return $query;
    }

    public function borrarAsociados($articulo_id){
        $connection = ConnectionManager::get('default');
        $query = $connection->execute(
                    'SELECT * FROM articulo_imagen WHERE articulo_id = ' . $articulo_id
                )
                ->fetchAll('assoc');

        //borramos cada una de las imagenes de la noticia
        foreach($query as $imagen){
            $connection = ConnectionManager::get('default');
            $query = $connection->execute(
                        'DELETE FROM imagenes WHERE id = ' . $imagen['imagen_id']
                    );
        }

        //borramos la referencia de las imagenes de la noticia
        $query = $connection->execute(
                    'DELETE FROM articulo_imagen WHERE articulo_id = ' . $articulo_id
                );

        //borramos la referencia de las palabras claves de la noticia
        $query = $connection->execute(
                    'DELETE FROM articulo_palabra_clave WHERE articulo_id = ' . $articulo_id
                );
    }
}