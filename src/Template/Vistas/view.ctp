<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Vistas'), ['controller'=>'Vistas', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Vista</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Vista</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($vista->descripcion) ?></h3>
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
                                <h4><?= __('Descripción') ?></h4>
                            </td>
                            <td class=""><h4><?= h($vista->codigo) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Ruta de la imagen') ?></h4>
                            </td>
                            <td class=""><h4><?= h($vista->descripcion) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $vista->creado,
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
                                        $vista->modificado,
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