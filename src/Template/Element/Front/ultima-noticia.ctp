<?php if(!is_null($ultima)): ?>
    <div class="wp-caption aligncenter" style="width: 88%; margin-bottom: 35px!important; padding-bottom: 15px!important;">
        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $ultima->id, Cake\Utility\Inflector::slug(strtolower($ultima->titulo))]); ?>">
            <center><h1 style="color: black; font-size: 300%; padding-left: 5%; padding-right: 5%;"><?= $ultima->titulo ?></h1></center>
        </a>
        <br>
        <?php if(count($ultima->imagenes) > 0): ?>
            <div class="article-content">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $ultima->id, Cake\Utility\Inflector::slug(strtolower($ultima->titulo))]); ?>">
                    <figure>
                        <i class="seccion-nota" style="position: absolute; opacity: 1;"><?= $ultima->portal->nombre ?></i>
                        <img width="100%" height="100%" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . reset($ultima->imagenes)->file_url . '/' . reset($ultima->imagenes)->filename ?>" style="display: block; margin: 0 auto;"/>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 38px; position: absolute; opacity: 1;"><?php echo $ultima->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                    </figure>
                </a>
                <br>
                <?php if(!is_null($ultima->descripcion)): ?>
                    <p class="wp-caption-text"><h4 style="margin-top: -20px; color: black; font-size: 150%; padding-left: 5%; padding-right: 5%;"><?= $ultima->descripcion ?></h4></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="article-content">
                <?php if(!is_null($ultima->descripcion)): ?>
                    <p class="wp-caption-text"><h4 style="margin-top: -20px; color: black; font-size: 150%; padding-left: 5%; padding-right: 5%;"><?= $ultima->descripcion ?></h4></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="item-content" style="padding-top: 25px;">
            <div class="social-icon">
                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $ultima->id]); ?>&amp;t=<?= $ultima->titulo ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $ultima->id]); ?>&amp;text=<?= $ultima->titulo ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio') . $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $ultima->id]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
            </div>
        </div>
    </div>
<?php endif; ?>