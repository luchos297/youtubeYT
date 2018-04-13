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
            <div class="form-group">
                <div>
                    <h4 class="h4" style="margin-top: 10px; margin-left: 10px;">Recuerde que la ruta de lectura es "<b><?= $path ?>"</b></h4>
                </div>                        
            </div>
            <div class="panel-body">
                <?php if (!$resultadoDTO['error']): ?>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">                    
                        <thead>
                            <tr>
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
                            <?php foreach ($resultadoDTO['listado'] as $cancion): ?>
                                <tr>
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
                    </table>
                <?php else: ?>
                    <div class="form-group">
                        <div style="font-color: red;">
                            <?= $resultadoDTO['message'] ?>
                        </div>                        
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script(['/assets/plugins/ckeditor/ckeditor.js']) ?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
<?= $this->Html->script(['//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js']) ?>
<script></script>