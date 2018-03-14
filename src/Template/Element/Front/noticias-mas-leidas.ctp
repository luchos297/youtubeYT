<div class="widget">
    <h3>M&aacute;s le&iacute;dos</h3>
    <div class="article-block">
        <?php foreach($mas_leidos as $nota): ?>
            <div class="item">
                <div class="item-header">
                    <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota->id, $nota->id, Cake\Utility\Inflector::slug(strtolower($nota->titulo))]); ?>" class="image-hover">
                        <?php if($nota->has('imagenes') && !empty($nota->imagenes)): ?>
                            <?php foreach($nota->imagenes as $imagen): ?>
                                <img style="width: 130px; height: 100px;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url.'/'.$imagen->filename ?>" class="lazy"/>
                                <?php break; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $nota->portal->codigo && $portal->has('imagen')): ?>
                                    <img style="width: 130px; height: 100px;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url.'/'.$portal->imagen->filename ?>" class="lazy"/>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="item-content">
                    <h4><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota->id, $nota->id, Cake\Utility\Inflector::slug(strtolower($nota->titulo))]); ?>"><?= $nota->titulo ?></a></h4>
                    <a href="#<?= $nota->portal->codigo ?>">
                        <i class="seccion-nota seccion-no-absolute seccion-background"><?= $nota->portal->nombre ?></i>
                        <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $nota->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>