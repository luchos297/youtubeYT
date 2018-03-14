<div class="widget">
    <h3>Ahora</h3>
    <div class="article-block">
        <?php foreach($destacadas as $destacada): ?>
            <div class="item">
                <div class="content-category">
                    <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'seccionKeywords', $destacada->clave]); ?>" class="break-category" style="color: #276197; font-size: 16px;"><?= "#" . $destacada->clave ?></a></h3>
		</div>
                <div class="item-header">
                    <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $destacada->id, $destacada->id, Cake\Utility\Inflector::slug(strtolower($destacada->titulo))]); ?>" class="image-hover">
                        <?php if($destacada->imagenes && !empty($destacada->imagenes)): ?>
                            <img style="width: 130px; height: 100px;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($destacada->imagenes)->file_url.'/'.reset($destacada->imagenes)->filename ?>" class="lazy"/>
                        <?php else: ?>
                            <?php if($destacada->portal->has('imagen')): ?>
                                <img style="width: 130px; height: 100px;" src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $destacada->portal->imagen->file_url . '/' . $destacada->portal->imagen->filename ?>" class="lazy"/>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="item-content">
                    <h4><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $destacada->id, $destacada->id, Cake\Utility\Inflector::slug(strtolower($destacada->titulo))]); ?>"><?= $destacada->titulo ?></a></h4>
                    <a href="#<?= $destacada->portal->codigo ?>">
                        <i class="seccion-nota seccion-no-absolute seccion-background"><?= $destacada->portal->nombre ?></i>
                        <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $destacada->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>