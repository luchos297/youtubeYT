<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Categorías'), ['controller'=>'categorias', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Categoría</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Categoría</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($categoria->nombre) ?></h3>
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
                                <h4><?= __('Nombre') ?></h4>
                            </td>
                            <td class=""><h4><?= h($categoria->nombre) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Código') ?></h4>
                            </td>
                            <td class=""><h4><?= h($categoria->codigo) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Palabras Claves') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                <?php 
                                $palabras = [];
                                foreach($categoria->palabras_claves as $palabra_clave){
                                    $palabras[] = $palabra_clave->texto;
                                }
                                echo implode(',', $palabras);
                                ?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Supercategoría') ?></h4>
                            </td>
                            <td class="type-info"><h4><?= $categoria->has('parent') ? $this->Html->link($categoria->parent->nombre, ['controller' => 'Categorias', 'action' => 'view', $categoria->parent->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $categoria->creado,
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
                                    <?= h($this->Time->format(
                                        $categoria->modificado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        ))?>
                                </h4>
                            </td>
                        </tr>                        
                         
                    </tbody>
                </table>          
                
            </div>
        </div>
    </div>
</div>