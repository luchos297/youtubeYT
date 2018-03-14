<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Rss</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de rss</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= $this->element('Cms/paginator-top') ?>
                <table id="data-table-cms" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', '#') ?></th>
                            <th><?= $this->Paginator->sort('nombre') ?></th>
                            <th><?= $this->Paginator->sort('categoria_id', 'Categoría') ?></th>
                            <th><?= $this->Paginator->sort('portal_id') ?></th>
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
                                <?php echo $this->Form->input('nombre', ['label'=>false,'class' => 'form-control search', 'type' => 'text','value'=>isset($_GET['nombre'])?$_GET['nombre']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('categoria', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $categorias,'value'=>isset($_GET['categoria'])?$_GET['categoria']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('portal', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $portales,'value'=>isset($_GET['portal'])?$_GET['portal']:'']); ?>
                            </th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($rsses as $rss): ?>
                        <tr>
                            <td><?= $this->Number->format($rss->id) ?></td>
                            <td><?= h($rss->nombre) ?></td>
                            <td><?= $rss->has('categoria') ? $this->Html->link($rss->categoria->nombre, ['controller' => 'Categorias', 'action' => 'view', $rss->categoria->id]) : '' ?></td>
                            <td><?= $rss->has('portal') ? $this->Html->link($rss->portal->nombre, ['controller' => 'Portales', 'action' => 'view', $rss->portal->id]) : '' ?></td>

                            <td>
                                <?php if($rss->habilitado): ?>                                    
                                    <span class="badge badge-success ver-elemento">habilitado</span> |
                                <?php else:?>
                                    <span class="badge ver-elemento">habilitado</span> |
                                <?php endif; ?>
                                <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $rss->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $rss->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal'.$rss->id,'escape'=>false]) ?>
                                <div class="modal fade" id="basicModal<?= $rss->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $rss->id ?>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <?= $this->Form->postLink('Borrar', ['action' => 'delete', $rss->id], ['class' => 'btn btn-primary']) ?>
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
            $('select.search').val('');
            $('.apply-filters').click();
        });
    });
</script>
