<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Publicidades'), ['controller'=>'Banners', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Publicidad</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Publicidad</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($banner->descripcion) ?></h3>
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
                                <h4><?= __('Descripción') ?></h4>
                            </td>
                            <td class=""><h4><?= h($banner->descripcion) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Tipos de Publicidad') ?></h4>
                            </td>
                            <td class="type-info"><h4><?= $banner->has('banner_tipo') ? $this->Html->link($banner->banner_tipo->nombre, ['controller' => 'BannerTipos', 'action' => 'view', $banner->banner_tipo->id]) : '' ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Vistas') ?></h4>
                            </td>
                            <td>
                            <?php foreach ($banner->banner_vista as $banner_vista): ?>
                                <?= (sizeof($banner_vista) > 0) ? $this->Html->link($banner_vista->vista->codigo, ['controller' => 'Vistas', 'action' => 'view', $banner_vista->vista->id]) . " (" . h($banner_vista->posicion) . ")" : '' ?><br/>
                            <?php endforeach; ?>
                            </td>
                        </tr>                        
                        <tr>
                            <td>
                                <h4><?= __('Hipervinculo') ?></h4>
                            </td>
                            <td class=""><h4><?= h($banner->href) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $banner->creado,
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
                                    <?= h($this->Time->format(
                                        $banner->modificado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                        ))?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Imagen') ?></h4>
                            </td>
                            <td class="">
                                <h4>                                    
                                    <?php if(strpos($banner->filename, "swf") !== false ): ?>
                                        <object><param name="banner" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="86%" width="86%"></embed>
                                        </object>                                        
                                    <?php else: ?>
                                        <?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url.'/'.$banner->filename) ?>                                                            
                                    <?php endif; ?>
                                </h4>
                            </td>
                        </tr>
                        <?php if($banner->mobile == "1"): ?>
                            <tr>
                                <td>
                                    <h4><?= __('Imagen Mobile') ?></h4>
                                </td>
                                <td class="">
                                    <h4>
                                        <?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url.'/'.$banner->filename_mobile) ?>
                                    </h4>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>