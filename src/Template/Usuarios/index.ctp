<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Usuarios</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de usuarios</h1>
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
                            <th><?= $this->Paginator->sort('email') ?></th>
                            <th><?= $this->Paginator->sort('creado') ?></th>
                            <th><?= $this->Paginator->sort('modificado') ?></th>
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
                                <?php echo $this->Form->input('email', ['label'=>false,'class' => 'form-control search','value'=>isset($_GET['email'])?$_GET['email']:'']); ?>
                            </th>
                            <th></th>
                            <th></th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $this->Number->format($usuario->id) ?></td>
                            <td><?= h($usuario->email) ?></td>
                            <td>
                                <?= $this->Time->format(
                                    $usuario->creado,
                                    'dd-MM-Y HH:mm',
                                    null,
                                    null
                                );?>
                            </td>
                            <td>
                                <?= $this->Time->format(
                                    $usuario->modificado,
                                    'dd-MM-Y HH:mm',
                                    null,
                                    null
                                );?>
                            </td>
                            <td>
                                <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $usuario->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $usuario->id],['escape'=>false]) ?> | 
                                <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal'.$usuario->id,'escape'=>false]) ?>
                                <div class="modal fade" id="basicModal<?= $usuario->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $usuario->id ?>?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <?= $this->Form->postLink('Borrar', ['action' => 'delete', $usuario->id], ['class' => 'btn btn-primary']) ?>
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