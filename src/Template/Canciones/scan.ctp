<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li class="active">Canciones</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Escanear canciones</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title">_</h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="form-group" style="padding-top: 10px; margin-left: 15px">
                <div>
                    <h4 class="h4">Recuerde que la ruta de lectura es <b><u>"<?= $path ?>"</u></b>.</h4>
                </div>                        
            </div>
            <div class="panel-body">
                
                    <?php if (!$resultadoDTO['error']): ?>
                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('title', 'Title') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('artist', 'Artist') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('album', 'Album') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('year', 'Year') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('genre', 'Genre') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('duration', 'Duration') ?></th>                            
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('filesize', 'Filesize') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('sample_rate', 'Sample Rate') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('bitrate', 'Bitrate') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('dataformat', 'Format') ?></th>
                                    <th style="vertical-align: middle;"><?= $this->Paginator->sort('read', 'Read') ?></th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 13px;">
                                <?= $this->Form->create(null, ['type' => 'post', 'id' => 'data']); ?>
                                    <?php foreach ($resultadoDTO['listado'] as $cancion): ?>
                                        <tr>
                                            <th style="text-align: center; vertical-align: middle;"><input type="checkbox" id="toogle_all" value="0"></th>
                                            <td style="vertical-align: middle;"><?= $cancion['title'] ?></td>
                                            <td style="vertical-align: middle;"><?= $cancion['artist'] ?></td>
                                            <td style="vertical-align: middle;"><?= $cancion['album'] ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?= $this->Number->format($cancion['year']) ?></td>
                                            <td style="vertical-align: middle;"><?= $cancion['genre'] ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?= $cancion['duration'] ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?= $cancion['filesize'] ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?= $cancion['sample_rate'] ?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?= $cancion['bitrate'] ?></td>                                
                                            <td style="text-align: center; vertical-align: middle;"><?= strtoupper($cancion['dataformat']) ?></td>
                                            <td style="text-align: center; vertical-align: middle;">                                    
                                                <?php if ($cancion['read'] == 1):
                                                    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_icons') . 'tick.png');
                                                else: 
                                                    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_icons') . 'error.png');
                                                endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>                            
                            <?= $this->Form->end(); ?>
                        </table>                        
                    <?php else: ?>
                        <div class="form-group">
                            <div style="font-color: red;">
                                <?= $resultadoDTO['message'] ?>
                            </div>                        
                        </div>
                    <?php endif; ?>
                    </div>
                    <div class="form-group" align="right">
                        <?= $this->Form->button(__('Get'), ['class' => 'btn btn-success', 'id' => 'get']) ?>
                        <?= $this->Form->button(__('Cancel'), ['class' => 'btn btn-default', ['controller' => 'canciones', 'action' => 'index']]) ?>
                    </div>
                
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script(['/assets/plugins/ckeditor/ckeditor.js']) ?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
<?= $this->Html->script(['//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js']) ?>
<script>
    $('#get').click(function() {
        var canciones = [];

        //Por cada cancion seleccionada
        $("input:checkbox:checked").each(function(){

            console.log($(this).attr('title'));
            console.log($(this).attr('artist'));


            canciones.push([$(this).attr('title') :$(this).attr('artist')];
        });

        console.log(canciones);
        debugger;

        if(canciones.length > 0){
            $('#data').val(canciones);
            $('#get').submit();
        }
    });
</script>