<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Categorías</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de categorías</h1>
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
                            <th><?= $this->Paginator->sort('codigo', 'Código') ?></th>
                            <th><?= $this->Paginator->sort('categoria_id', 'Supercategoría') ?></th>
                            <th><?= __('') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tablesorter-filter-row">
                            <?= $this->Form->create(null, ['type' => 'get', 'id' => 'form-filter']); ?>
                            <th style="width: 70px;">
                                <?php echo $this->Form->input('id', ['label'=>false,'class' => 'form-control search', 'type' => 'integer','value'=>isset($_GET['id'])?$_GET['id']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('nombre', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['nombre'])?$_GET['nombre']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('codigo', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['codigo'])?$_GET['codigo']:'']); ?>
                            </th>
                            <th style="width: 20px;">
                                <?php echo $this->Form->input('supercategoria', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $categorias_list,'value'=>isset($_GET['supercategoria'])?$_GET['supercategoria']:'']); ?>
                            </th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= $this->Number->format($categoria->id) ?></td>
                            <td><?= h($categoria->nombre) ?></td>
                            <td><?= h($categoria->codigo) ?></td>
                            <td><?= $categoria->has('parent')  ? $this->Html->link($categoria->parent->nombre, ['controller' => 'Categorias', 'action' => 'view', $categoria->parent->id]) : '' ?></td>
                            
                            <td>
                                <?php if ($categoria->en_menu): ?>                                    
                                    <span class="badge badge-success ver-elemento">en menú</span> |
                                <?php else: ?>
                                    <span class="badge ver-elemento">en menú</span> |
                                <?php endif; ?>
                                    
                                <?php if ($categoria->en_cartelera): ?>                                    
                                    <span class="badge badge-success ver-elemento">en cartelera</span> |
                                <?php else: ?>
                                    <span class="badge ver-elemento">en cartelera</span> |
                                <?php endif; ?>
                                    
                                <?php if ($categoria->en_especial): ?>                                    
                                    <span class="badge badge-success ver-elemento">en especial</span> |
                                <?php else: ?>
                                    <span class="badge ver-elemento">en especial</span> |
                                <?php endif; ?>
                                    
                                <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $categoria->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $categoria->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal'.$categoria->id,'escape'=>false]) ?>
                                <div class="modal fade" id="basicModal<?= $categoria->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $categoria->id ?>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <?= $this->Form->postLink('Borrar', ['action' => 'delete', $categoria->id], ['class' => 'btn btn-primary']) ?>
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