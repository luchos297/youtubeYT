<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Artículos'), ['controller'=>'articulos', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Artículo</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Artículo</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($articulo->titulo) ?></h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <h4><?= __('Título') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->titulo) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Categoría') ?></h4>
                            </td>
                            <td class="type-info"><h4><?= $articulo->has('categoria') ? $this->Html->link($articulo->categoria->nombre, ['controller' => 'Categorias', 'action' => 'view', $articulo->categoria->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Portal') ?></h4>
                            </td>
                            <td class="type-info"><h4><?= $articulo->has('portal') ? $this->Html->link($articulo->portal->nombre, ['controller' => 'Portales', 'action' => 'view', $articulo->portal->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Id') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->id) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Palabras Claves') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                <?php 
                                $palabras = [];
                                foreach($articulo->palabras_claves as $palabra_clave){
                                    $palabras[] = $palabra_clave->texto;
                                }
                                echo implode(',', $palabras);
                                ?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Visitas') ?></h4>
                            </td>
                            <td class=""><h4><?= $this->Number->format($articulo->visitas) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Habilitado') ?></h4>
                            </td>
                            <td class=""><h4><?= $articulo->habilitado ? __('Si') : __('No'); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Publicado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= h($this->Time->format(
                                        $articulo->publicado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        ))?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
<!--                            <td>
                                <h4><?= __('Palabras Claves') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->palabras_claves) ?></h4></td>-->
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Url') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->url); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Url Rss') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->url_rss); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Descripción') ?></h4>
                            </td>
                            <td class=""><h4><?= h($articulo->descripcion); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Texto') ?></h4>
                            </td>
                            <td class=""><h4><?= $articulo->texto; ?></h4></td>
                        </tr>                        
                        <tr>
                            <td>
                                <h4><?= __('Imagen/es') ?></h4>
                            </td>
                            <td class="">
                                <h4><?php 
                                if($articulo->has('imagenes')){
                                    foreach($articulo->imagenes as $imagen){
                                        echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . $imagen->file_url.'/'.$imagen->filename);
                                        echo "<p></p>";
                                        echo "<p></p>";
                                    }
                                }
                                ?>
                                </h4>
                            </td>
                        </tr>
                    </tbody>
                </table>          
                
            </div>
        </div>
    </div>
</div>
