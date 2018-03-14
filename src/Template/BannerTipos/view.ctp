<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Tipos de Publicidad'), ['controller'=>'BannerTipos', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Tipo de Publicidad</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Tipo de Publicidad</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($bannerTipo->nombre) ?></h3>
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
                            <td class=""><h4><?= h($bannerTipo->nombre) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Ancho') ?></h4>
                            </td>
                            <td class=""><h4><?= h($bannerTipo->ancho) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Alto') ?></h4>
                            </td>
                            <td class=""><h4><?= h($bannerTipo->alto) ?></h4></td>
                        </tr>                        
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $bannerTipo->creado,
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
                                        $bannerTipo->modificado,
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