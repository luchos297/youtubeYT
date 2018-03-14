<div class="ot-slider owl-carousel">
    <!-- BEGIN .ot-slide -->
    <?php 
    $silder_position = [
        ['position' => 'first', 'background-color' => '#ed2d00;', 'color' => '#fff;', 'imagen'=>'img/images/photos/image-6.jpg', 'w' => '471px', 'h' => '368px'],
        ['position' => 'second', 'background-color' => '#FFa70C;', 'color' => '#fff;', 'imagen'=>'img/images/photos/image-7.jpg', 'w' => '397px', 'h' => '368px'],
        ['position' => 'third', 'background-color' => '#1985e1;', 'color' => '#fff;', 'imagen'=>'img/images/photos/image-8.jpg', 'w' => '356px', 'h' => '180px'],
        ['position' => 'fourth', 'background-color' => '#429d4a;', 'color' => '#fff;', 'imagen'=>'img/images/photos/image-9.jpg', 'w' => '356px', 'h' => '180px']
    ];
    ?>
    <?php if (count($carousel_principal) > 0) : ?>
        <?php for($i = 1; $i <= 2; $i++) : ?>
            <div class="ot-slide">
            <?php foreach($silder_position as $position): ?>
                <?php $noticia = array_pop($carousel_principal); ?>
                <div class="ot-slider-layer <?= $position['position'] ?>">
                    <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>">
                        <strong>
                            <div>
                                <i style="background-color: <?= $position['background-color'] ?> color: <?= $position['color'] ?>"><?= $noticia['Categoria__nombre'] ?></i>
                                <i style="color: <?= $position['color'] ?>" onclick="openRemodal('<?= $noticia['Portal__codigo'] ?>');return false;"><?= $noticia['Portal__nombre'] ?></i>
                                <i style="background-color: #F03030;"><?php echo substr($noticia['publicado'], 8, -9) . "/" . substr($noticia['publicado'], 5, -12) . substr($noticia['publicado'], 10, -3) ?></i>
                            </div>
                            <div style="clear: both"><?= $noticia['titulo'] ?></div>
                        </strong>
                        <?php if($noticia['Imagen__filename'] != null): ?>
                        <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                            <img style="width: <?= $position['w'] ?>; height: <?= $position['h'] ?>;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'].'/'.$noticia['Imagen__filename'] ?>"/>
                        </a>
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')) : ?>
                                    <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                                        <img style="width: <?= $position['w'] ?>; height: <?= $position['h'] ?>;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url.'/'.$portal->imagen->filename ?>"/>
                                    </a>
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endfor; ?>
    <?php endif; ?>
    <!-- END .ot-slide -->
</div>