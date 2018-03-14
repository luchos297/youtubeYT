<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Imágenes</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de imágenes</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title">Artículos</h3>
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
                            <th><?= $this->Paginator->sort('filename', 'Imagen') ?></th>
                            <th><?= $this->Paginator->sort('file_url', 'Carpeta') ?></th>
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
                                <?php echo $this->Form->input('nombre', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['nombre'])?$_GET['nombre']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('path', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['path'])?$_GET['path']:'']); ?>
                            </th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($imagenes as $imagen): ?>
                        <tr>
                            <td><?= $this->Number->format($imagen->id) ?></td>
                            <td><?= h($imagen->filename) ?></td>
                            <td><?= h($imagen->file_url) ?></td>
<!--                            <td>
                                <?= $this->Time->format(
                                    $imagen->creado,
                                    'd-M-Y H:m',
                                    null,
                                    null
                                );?>
                            </td>
                            <td>
                                <?= $this->Time->format(
                                    $imagen->modificado,
                                    'd-M-Y H:m',
                                    null,
                                    null
                                );?>
                            </td>-->
                            <td>
                                <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $imagen->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $imagen->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal'.$imagen->id,'escape'=>false]) ?>
                                <div class="modal fade" id="basicModal<?= $imagen->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $imagen->id ?>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <?= $this->Form->postLink('Borrar', ['action' => 'delete', $imagen->id], ['class' => 'btn btn-primary']) ?>
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