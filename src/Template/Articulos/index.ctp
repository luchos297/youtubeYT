<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Artículos</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de artículos</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="button-massive-delete">
                <p><button class="btn btn-warning btn-block">Eliminaci&oacute;n masiva</button></p>                
            </div>
            <div class="panel-body">
                <?= $this->element('Cms/paginator-top') ?>
                <table id="data-table-cms" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                    <thead>
                        <tr>
                            <th></th>
                            <th><?= $this->Paginator->sort('id', '#') ?></th>
                            <th><?= $this->Paginator->sort('categoria_id', 'Categoría') ?></th>
                            <th><?= $this->Paginator->sort('portal_id') ?></th>
                            <th><?= $this->Paginator->sort('titulo', 'Título') ?></th>
                            <!--<th><?= $this->Paginator->sort('palabras_claves') ?></th>-->
                            <th class="column-publicado"><?= $this->Paginator->sort('publicado') ?></th>
                            <th class="column-acciones"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tablesorter-filter-row">
                            <?= $this->Form->create(null, ['type' => 'get', 'id' => 'form-filter']); ?>
                            <th><input type="checkbox" id="toogle_all" value="0"></th>
                            <th>
                                <?php echo $this->Form->input('id', ['label'=>false,'class' => 'form-control search', 'type' => 'integer','value'=>isset($_GET['id'])?$_GET['id']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('categoria', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $categorias,'value'=>isset($_GET['categoria'])?$_GET['categoria']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('portal', ['label'=>false,'class' => 'form-control search','empty' => [NULL => ''], 'options' => $portales,'value'=>isset($_GET['portal'])?$_GET['portal']:'']); ?>
                            </th>
                            <th>
                                <?php echo $this->Form->input('titulo', ['label'=>false,'class' => 'form-control search', 'type' => 'text','value'=>isset($_GET['titulo'])?$_GET['titulo']:'']); ?>
                            </th>
                            <!--<th></th>-->
                            <th class="column-publicado">
                                <?php echo $this->Form->input('fecha', ['label'=>false,'class' => 'form-control search date-picker', 'id'=>'fecha', 'value'=>isset($_GET['fecha'])?$_GET['fecha']:'']); ?>
                            </th>
                            <th class="column-acciones"></th>
                            <?= $this->Form->end(); ?>
                        </tr>
                        <?php foreach ($articulos as $articulo): ?>
                            <tr>
                                <td>
                                    <?php  echo $this->Form->checkbox($articulo->id, ['hiddenField' => false, 'id' =>$articulo->id, 'name'=>'borra']);?>
                                </td>
                                <td><?= $this->Number->format($articulo->id) ?></td>
                                <td><?= $articulo->has('categoria') ? $this->Html->link($articulo->categoria->nombre, ['controller' => 'Categorias', 'action' => 'view', $articulo->categoria->id]) : '' ?></td>
                                <td><?= $articulo->has('portal') ? $this->Html->link($articulo->portal->nombre, ['controller' => 'Portales', 'action' => 'view', $articulo->portal->id]) : '' ?></td>
                                <td><?= h($articulo->titulo) ?></td>
                                <!--<td><?= h($articulo->palabras_claves) ?></td>-->
                                <td><?=
                                    $this->Time->format(
                                            $articulo->publicado, 'dd-MM-Y HH:mm', null, null
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php if ($articulo->habilitado): ?>                                    
                                        <span class="badge badge-success ver-elemento">visible</span> |
                                    <?php else: ?>
                                        <span class="badge ver-elemento">visible</span> |
                                    <?php endif; ?>
                                    <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $articulo->id], ['escape' => false]) ?> | 
                                    <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $articulo->id], ['escape' => false]) ?> | 
                                    <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle' => 'modal', 'data-target' => '#basicModal' . $articulo->id, 'escape' => false]) ?>
                                    <div class="modal fade" id="basicModal<?= $articulo->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $articulo->id ?>?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    <?= $this->Form->postLink('Borrar', ['action' => 'delete', $articulo->id], ['class' => 'btn btn-primary']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?= $this->Form->create(null, ['id' => 'massive-delete', 'url' => ['action'=>'borrado_masivo?page:1']]); ?>
                <?= $this->Form->hidden('',['id' => 'idsNoticiasDelete', 'name'=>'idsNoticiasDelete']); ?>
                <?= $this->Form->end(); ?>
                <?= $this->element('Cms/paginator') ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#fecha').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            language: "es"
        });

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

        $('.button-massive-delete').click(function() {

            var idNoticias = '';
            $("input:checkbox:checked").each(function(){
                //cada elemento seleccionado
                if(idNoticias != '')
                    idNoticias += '-';
                idNoticias += $(this).attr('id');
            });
            //console.debug(idNoticias);
            if(idNoticias != ''){
                confirmar = confirm("Est\u00e1 por eliminar los articulos seleccionados.\n \u00BFDesea continuar?");
                if(confirmar == true){
                    $('#idsNoticiasDelete').val(idNoticias);
                    $('#massive-delete').submit();
                }
            }
        });

        $('#toogle_all').click(function(){
            checkboxes = document.getElementsByName('borra');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = $(this).prop('checked');
            }
        });
    });
</script>