<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Rss'), ['controller'=>'rsses', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Rss</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Rss</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($rss->nombre) ?></h3>
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
                            <td class=""><h4><?= h($rss->nombre) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Categoria') ?></h4>
                            </td>
                            <td class=""><h4><?= $rss->has('categoria') ? $this->Html->link($rss->categoria->nombre, ['controller' => 'Categorias', 'action' => 'view', $rss->categoria->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Portal') ?></h4>
                            </td>
                            <td class=""><h4><?= $rss->has('portal') ? $this->Html->link($rss->portal->nombre, ['controller' => 'Portales', 'action' => 'view', $rss->portal->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Id') ?></h4>
                            </td>
                            <td class=""><h4><?= h($rss->id) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Url') ?></h4>
                            </td>
                            <td class=""><h4><?= h($rss->url) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Habilitado') ?></h4>
                            </td>
                            <td class=""><h4><?= $rss->habilitado ? __('Si') : __('No'); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= h($this->Time->format(
                                        $rss->creado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        ))?>
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
                                        $rss->modificado,
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