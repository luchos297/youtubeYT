<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Canciones'), ['controller' => 'Canciones', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Canción</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Canción</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('ID') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->id ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('URL') ?></h4>
                            </td>
                            <td class="" style="align: center; vertical-align: middle;"><a href="<?= $cancion->url ?>" target="_blank"><?= $cancion->url ?></a></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Title') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->title ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Artist') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->artist ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Album') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->album ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Genre') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->genre ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Filesize') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->filesize ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Duration') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->duration ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Sample rate') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->sample_rate ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Bitrate') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->bitrate ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Format') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->dataformat ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Quality') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->quality ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Download URL') ?></h4>
                            </td>
                            <td class=""><h4><a href="<?= $cancion->url_yt_download ?>" target="_blank">Download</h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Filename') ?></h4>
                            </td>
                            <td class=""><h4><?= $cancion->filename ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Downloaded') ?></h4>
                            </td>
                            <td class=""><h4><?= ($cancion->downloaded) ? 'Yes' : 'No' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Publish date') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format($cancion->fecha_publish, 'dd/MM/Y HH:mm:ss', null, null);?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Created') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format($cancion->creado, 'dd/MM/Y HH:mm:ss', null, null);?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Modified') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $cancion->modificado ?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4 style="font-weight: bold;"><?= __('Cover') ?></h4>
                            </td>
                            <td class="">
                                <h4><?php if($cancion->image_path){
                                    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_covers') . $cancion->video_id . '.jpg');
                                } ?>
                                </h4>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>