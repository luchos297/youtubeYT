<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Canciones</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Lista de canciones</h1>
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
                            <th><?= $this->Paginator->sort('id', 'ID') ?></th>                    
                            <th><?= $this->Paginator->sort('title', 'Title') ?></th>
                            <th><?= $this->Paginator->sort('artist', 'Artist') ?></th>
                            <th><?= $this->Paginator->sort('url', 'Youtube') ?></th>
                            <th><?= $this->Paginator->sort('duration', 'Duration') ?></th>
                            <th><?= $this->Paginator->sort('year', 'Year') ?></th>
                            <th><?= $this->Paginator->sort('filesize', 'Filesize') ?></th>
                            <th><?= $this->Paginator->sort('dataformat', 'Format') ?></th>
                            <th><?= $this->Paginator->sort('downloaded', 'Donwloaded') ?></th>
                            <th class="column-creado"><?= $this->Paginator->sort('creado') ?></th>
                            <th class="column-acciones"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($canciones as $cancion): ?>
                            <tr>
                                <td>
                                    <?php  echo $this->Form->checkbox($cancion->id, ['hiddenField' => false, 'id' => $cancion->id, 'name' => 'borra']); ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle;"><?= $this->Number->format($cancion->id) ?></td>                                
                                <td><?= $cancion->title ?></td>
                                <td><?= $cancion->artist ?></td>
                                <td><a href="<?= $cancion->url ?>" target="_blank"><?= $cancion->url ?></a></td>
                                <td style="text-align: center; vertical-align: middle;"><?= $cancion->duration ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?= $this->Number->format($cancion->year) ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?= $cancion->filesize . ' MB' ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?= strtoupper($cancion->dataformat) ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?= ($cancion->downloaded) ? 'Yes' : 'No' ?></td>
                                <td><?= $this->Time->format($cancion->creado, 'dd/MM/Y HH:mm:ss', null, null); ?></td>
                                <td style="text-align: center; vertical-align: middle;">                                    
                                    <?= $this->Html->link('<i class="fa fa-eye" title="Ver"></i>', ['action' => 'view', $cancion->id], ['escape' => false]) ?> | 
                                    <?= $this->Html->link('<i class="fa fa-edit" title="Editar"></i>', ['action' => 'edit', $cancion->id], ['escape' => false]) ?> | 
                                    <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle' => 'modal', 'data-target' => '#basicModal' . $cancion->id, 'escape' => false]) ?>
                                    <div class="modal fade" id="basicModal<?= $cancion->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Está seguro de borrar el registro #<?= $cancion->id ?>?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                    <?= $this->Form->postLink('Borrar', ['action' => 'delete', $cancion->id], ['class' => 'btn btn-primary']) ?>
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