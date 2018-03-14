<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Publicidades</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de publicidades</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title">Publicidad</h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <?= $this->element('Cms/paginator-top') ?>
                <table id="data-table-cms" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', '#') ?></th>
                            <th><?= $this->Paginator->sort('descripcion', 'Descripción') ?></th>
                            <th><?= $this->Paginator->sort('banner_tipos_id', 'Tipo') ?></th>
                            <th>Vista(prioridad)</th>
                            <th><?= __('') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tablesorter-filter-row">
                            <?= $this->Form->create(null, ['type' => 'get', 'id' => 'form-filter']); ?>
                            <th>
                                <?php echo $this->Form->input('id', ['label'=>false,'class' => 'form-control search', 'type' => 'integer','value'=>isset($_GET['id'])?$_GET['id']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('descripcion', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['descripcion'])?$_GET['descripcion']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('tipo', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $banners_tipo,'value'=>isset($_GET['tipo'])?$_GET['tipo']:'']); ?>                            
                            </th>
                            <th>
                                <?php echo $this->Form->input('vista_id', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $vistas,'value'=>isset($_GET['vista_id'])?$_GET['vista_id']:'']); ?>                            
                            </th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($banners as $banner): ?>
                        <tr>
                            <td><?= $this->Number->format($banner->id) ?></td>
                            <td><?= h($banner->descripcion) ?></td>
                            <td><?= $banner->has('banner_tipo')  ? $this->Html->link($banner->banner_tipo->nombre, ['controller' => 'BannerTipos', 'action' => 'view', $banner->banner_tipo->id]) : '' ?></td>                            
                            <td>                                
                                <?php foreach ($banner->banner_vista as $banner_vista): ?>                                    
                                    <?= (sizeof($banner_vista) > 0) ? $banner_vista->vista->codigo . " (" . $banner_vista->posicion . ")" : '' ?> <br/>
                                <?php endforeach; ?>                                
                            </td>
                            <td>                                
                                <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $banner->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $banner->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal'.$banner->id,'escape'=>false]) ?>
                                <div class="modal fade" id="basicModal<?= $banner->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $banner->id ?>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <?= $this->Form->postLink('Borrar', ['action' => 'delete', $banner->id], ['class' => 'btn btn-primary']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>                    
                </table>
                <?= $this->element('Cms/paginator') ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {  
        
        $('.search').keypress(function (e) {
            if (e.which == 13) {
                $('.tablesorter-filter-row form').submit();
                return false;
            }
        });
        $('.apply-filters').click(function (e) {
            $('.tablesorter-filter-row form').submit();
        });
        
        $(".clear-filters").click(function() {            
            $('input.search').val('');
            $('.apply-filters').click();
        });
    });
</script>