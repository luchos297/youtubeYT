<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Imágenes'), ['controller'=>'imagenes', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Imágen</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Imágen</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($imagen->nombre) ?></h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><h4><?= __('') ?></h4></td>
                            </td><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . $imagen->file_url.'/'.$imagen->filename); ?> </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Filename') ?></h4>
                            </td>
                            <td class=""><h4><?= h($imagen->filename) ?></h4></td>    
                        </tr>                        
                        <tr>
                            <td>
                                <h4><?= __('Descripción') ?></h4>
                            </td>
                            <td class=""><h4><?= h($imagen->descripcion); ?></h4></td>        
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Id') ?></h4>
                            </td>
                            <td class=""><h4><?= h($imagen->id) ?></h4></td>
                        </tr>   
                        <tr>
                            <td>
                                <h4><?= __('Comentario') ?></h4>
                            </td>
                            <td class=""><h4><?= h($imagen->comentario) ?></h4></td>
                        </tr>
                         <tr>
                            <td>
                                <h4><?= __('Url') ?></h4>
                            </td>
                            <td class=""><h4><?= h($imagen->url) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $imagen->creado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                    );?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Modificado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $imagen->modificado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                    );?>
                                </h4>
                            </td>
                        </tr> 
                        <tr>
                            <td>
                                <h4><?= __('Artículo relacionado') ?></h4>
                            </td>
                            <td>
                                <table id="data-table-cms" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                                    <thead>
                                        <tr>
                                            <th><?= $this->Paginator->sort('id', '#') ?></th>
                                            <th><?= $this->Paginator->sort('titulo', 'Título') ?></th>
                                            <th><?= $this->Paginator->sort('publicado') ?></th>
                                            <th><?= $this->Paginator->sort('habilitado') ?></th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($imagen->articulos as $articulo): ?>
                                        <tr>
                                            <td><?= $this->Number->format($articulo->id) ?></td>
                                            <td><?= h($articulo->titulo) ?></td>
                                            <td><?= h($articulo->publicado) ?></td>
                                            <td><?= $articulo->habilitado? 'Si' : 'No' ?></td>                                            
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>                    
                                </table>
                            </td>
                        </tr>                         
                    </tbody>
                </table>          
                
            </div>
        </div>
    </div>
</div>