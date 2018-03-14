<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Portales'), ['controller'=>'portales', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Portal</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Portal</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($portal->nombre) ?></h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <h4><?= __('Nombre') ?></h4>
                            </td>
                            <td class=""><h4><?= h($portal->nombre) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Código') ?></h4>
                            </td>
                            <td class=""><h4><?= h($portal->codigo) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Id') ?></h4>
                            </td>
                            <td class=""><h4><?= h($portal->id) ?></h4></td>
                        </tr>   
                        <tr>
                            <td>
                                <h4><?= __('Url') ?></h4>
                            </td>
                            <td class=""><h4><?= h($portal->url); ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $portal->creado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        );?>
                                </h4>
                            </td>
                        </tr>                        
                        <tr>
                            <td>
                                <h4><?= __('Modificado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $portal->modificado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        );?>
                                </h4>
                            </td>
                        </tr> 
                        <tr>
                            <td>
                                <h4><?= __('Imágen') ?></h4>
                            </td>
                            <td class="">
                                <h4><?php 
                                if($portal->has('imagen')){
                                    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . $portal->imagen->file_url.'/'.$portal->imagen->filename);
                                }
                                else{
                                    echo 'No';
                                }
                                ?>
                                </h4>
                            </td>
                            
                            
                        </tr>
                    </tbody>
                </table>          
                
            </div>
        </div>
    </div>
</div>