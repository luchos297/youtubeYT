<div class="left-content">

    <!-- BEGIN BANNER 3 -->
    <div class="home-block">        
        <div class="banner" id="banner3">
            <?php $banner3 = array_pop($banners_728x90);
            $width = $banner3->banner_tipo->ancho;
            $height = $banner3->banner_tipo->alto; ?> 

            <?php if(strpos($banner3->filename, "swf") !== false): ?>
                <?php if($banner3->href != ""): ?>
                    <a href="<?= $banner3->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner3->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner3->file_url . '/' . $banner3->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner3->file_url . '/' . $banner3->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner3->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner3->file_url . '/' . $banner3->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner3->file_url . '/' . $banner3->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner3->href != ""): ?>
                    <a href="<?= $banner3->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner3]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner3]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN PART 9 (PROVINCIALES) (BLOQUE 6) -->
    <div class="home-block">
        <?php if(count($parte9) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #FF0040">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte9)['Categoria__codigo']]); ?>" class="right button" style="background: #FF0040; color: #FF0040;">Ver m&aacute;s</a>
                <h2><?= reset($parte9)['Categoria__nombre'] ?></h2>
            </div>
            <!-- BEGIN .article-list-block -->
            <div class="article-list-block">

            <?php foreach($parte9 as $noticia): ?>
                <div class="item">
                    <div class="item-header">
                        <a href="#<?= $noticia['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $noticia['Portal__nombre'] ?></i>
                        </a>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?= $fecha; ?></i>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                    <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </figure>
                        </a>
                    </div>
                    <div class="item-content">
                        <div class="content-category">
                            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia['Categoria__codigo']]); ?>" style="color: #276197;"><?= $noticia['Categoria__nombre'] ?></a>
                            <div class="social-icon">
                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id']]); ?>&amp;t=<?= $noticia['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id']]); ?>&amp;text=<?= $noticia['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                            </div>
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($noticia['descripcion'], 0, 180) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN BANNER 2 -->
    <div class="home-block">
        <div class="banner" id="banner2">
            <?php $banner2 = array_pop($banners_728x90);
            $width = $banner2->banner_tipo->ancho;
            $height = $banner2->banner_tipo->alto; ?> 

            <?php if(strpos($banner2->filename, "swf") !== false): ?>
                <?php if($banner2->href != ""): ?>
                    <a href="<?= $banner2->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner2->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner2->file_url . '/' . $banner2->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner2->file_url . '/' . $banner2->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner2->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner2->file_url . '/' . $banner2->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner2->file_url . '/' . $banner2->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner2->href != ""): ?>
                    <a href="<?= $banner2->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner2]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner2]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN PART 1 (NACIONALES) (BLOQUE 5) -->
    <div class="home-block">
        <?php if(count($parte1) > 0): 
            $parte1_nota1 = array_pop($parte1); ?>
            <div class="main-title" style="border-left: 4px solid #0404B4">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $parte1_nota1['Categoria__codigo']]); ?>" class="right button" style="background: #0404B4; color: #0404B4;">Ver m&aacute;s</a>
                <h2><?= $parte1_nota1['Categoria__nombre'] ?></h2>
            </div>

            <!-- BEGIN .category-default-block -->
            <div class="category-default-block paragraph-row">

                <!-- BEGIN .column8 -->
                <div class="column8">
                    <div class="item-main">
                        <div class="item-header">
                            <a href="#<?= $parte1_nota1['Portal__codigo'] ?>">
                                <i class="seccion-nota"><?= $parte1_nota1['Portal__nombre'] ?></i>
                            </a>
                            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte1_nota1['id'], Cake\Utility\Inflector::slug(strtolower($parte1_nota1['titulo']))]); ?>" class="image-hover">
                                <figure>
                                <?php if(!is_null($parte1_nota1['Imagen__filename']) && !is_null($parte1_nota1['Imagen__file_url'])): ?>
                                    <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $parte1_nota1['Imagen__file_url'] . '/' . $parte1_nota1['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $parte1_nota1['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php $fecha = substr($parte1_nota1['publicado'], -11, -9) . "/" . substr($parte1_nota1['publicado'], -14, -12) . " " . substr($parte1_nota1['publicado'], -8, -3); ?>
                                <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                            </figure>
                            </a>
                        </div>
                        <div class="item-content">
                            <div class="social-icon">
                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte1_nota1['id']]); ?>&amp;t=<?= $parte1_nota1['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias' ,'action' => 'articulo', $parte1_nota1['id']]); ?>&amp;text=<?= $parte1_nota1['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte1_nota1['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>                            
                            </div>
                            <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte1_nota1['id']]); ?>"><?= $parte1_nota1['titulo'] ?></a></h3>
                            <p><?= $this->Texto->limitarTexto($parte1_nota1['descripcion'], 0, 200) ?></p>
                        </div>
                    </div>
                </div>
                <!-- END .column8 -->

                <!-- BEGIN .column4 -->
                <div class="column4 smaller-articles">
                    <?php foreach(array_reverse($parte1) as $noticia): ?>
                        <div class="item">
                            <div class="item-header">
                                <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                    <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="item-content">
                                <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                                <a href="#<?= $noticia['Portal__codigo'] ?>">
                                    <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                    <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                                    <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $fecha; ?></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- END .column4 -->
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 10 (POLÍTICA) (SLIDER) -->
    <div class="home-block">
        <?php if(count($parte10) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #FF4000">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte10)['Categoria__codigo']]); ?>" class="right button" style="background: #FF4000; color: #FF4000;">Ver m&aacute;s</a>
                <h2><?= h(reset($parte10)['Categoria__nombre']) ?></h2>
            </div>
            <div class="home-featured-article">
                <?php $state_item = "active"; ?>
                <?php foreach($parte10 as $noticia): ?>
                    <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="home-featured-item <?= $state_item ?>">
                        <i class="seccion-nota <?= $state_item ?>"><?= $noticia['Portal__nombre'] ?></i>
                        <span class="feature-text">
                            <strong><?= $noticia['titulo'] ?></strong>
                            <span><?= $noticia['descripcion'] ?></span>
                        </span>
                        <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                            <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                    <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota <?= $state_item ?>" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                    </a>
                    <?php $state_item = ""; ?>
                <?php endforeach; ?>
                <div class="home-featured-menu">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                </div>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN BANNER 1 -->
    <div class="home-block">
        <div class="banner" id="banner">
            <?php $banner = array_pop($banners_728x90);
            $width = $banner->banner_tipo->ancho;
            $height = $banner->banner_tipo->alto; ?> 

            <?php if(strpos($banner->filename, "swf") !== false): ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                            <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                        </object> 
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <object height="<?= $height ?>" width="<?= $width ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                        <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height ?>" width="<?= $width ?>"></embed>
                    </object>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php else: ?>
                <?php if($banner->href != ""): ?>
                    <a href="<?= $banner->href ?>" target="_blank">
                        <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner]) ?>
                    </a>
                    <!-- END .home-block -->
                <?php else: ?>
                    <?= $this->element('Front/banners-dinamicos/banner-728x90', ['banner' => $banner]) ?>
                    <!-- END .home-block -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BEGIN PART 11 (ECONOMÍA) (BLOQUE 5) -->
    <div class="home-block">
        <?php if(count($parte11) > 0):
            $parte11_nota1 = array_pop($parte11);
        ?>
        <div class="main-title" style="border-left: 4px solid #BF00FF">
            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $parte11_nota1['Categoria__codigo']]); ?>" class="right button" style="background: #BF00FF; color: #BF00FF;">Ver m&aacute;s</a>
            <h2><?= $parte11_nota1['Categoria__nombre'] ?></h2>
        </div>

        <!-- BEGIN .category-default-block -->
        <div class="category-default-block paragraph-row">

            <!-- BEGIN .column8 -->
            <div class="column8">
                <div class="item-main">
                    <div class="item-header">
                        <a href="#<?= $parte11_nota1['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $parte11_nota1['Portal__nombre'] ?></i>
                        </a>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte11_nota1['id'], Cake\Utility\Inflector::slug(strtolower($parte11_nota1['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($parte11_nota1['Imagen__filename']) && !is_null($parte11_nota1['Imagen__file_url'])): ?>
                                    <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $parte11_nota1['Imagen__file_url'] . '/' . $parte11_nota1['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $parte11_nota1['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php $fecha = substr($parte11_nota1['publicado'], -11, -9) . "/" . substr($parte11_nota1['publicado'], -14, -12) . " " . substr($parte11_nota1['publicado'], -8, -3); ?>
                                <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                                <!--<svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>-->
                                <figcaption>
                                    <!--<i class="seccion-nota"><?= $parte11_nota1['Portal__nombre'] ?></i>-->
                                    <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                </figcaption>
                            </figure>
                        </a>
                    </div>
                    <div class="item-content">
                        <div class="social-icon">
                            <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte11_nota1['id']]); ?>&amp;t=<?= $parte11_nota1['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte11_nota1['id']]); ?>&amp;text=<?= $parte11_nota1['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte11_nota1['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>                            
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte11_nota1['id']]); ?>"><?= $parte11_nota1['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($parte11_nota1['descripcion'], 0, 200) ?></p>
                    </div>
                </div>
            </div>
            <!-- END .column8 -->

            <!-- BEGIN .column4 -->
            <div class="column4 smaller-articles">
                <?php foreach(array_reverse($parte11) as $noticia): ?>
                    <div class="item">
                        <div class="item-header">
                            <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                            <?php else: ?>
                                <?php foreach($portales as $portal): ?>
                                    <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                        <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="item-content">
                            <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                                <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $fecha; ?></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- END .column4 -->
        </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 3 (INTERNACIONALES) (SLIDER) -->
    <div class="home-block">
        <?php if(count($parte3) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #0404B4">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte3)['Categoria__codigo']]); ?>" class="right button" style="background: #0404B4; color: #0404B4;">Ver m&aacute;s</a>
                <h2><?= h(reset($parte3)['Categoria__nombre']) ?></h2>
            </div>
            <div class="home-featured-article">
                <?php $state_item = "active"; ?>
                <?php foreach($parte3 as $noticia): ?>
                    <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="home-featured-item <?= $state_item ?>">
                        <i class="seccion-nota <?= $state_item ?>"><?= $noticia['Portal__nombre'] ?></i>
                        <span class="feature-text">
                            <strong><?= $noticia['titulo'] ?></strong>
                            <span><?= $noticia['descripcion'] ?></span>
                        </span>
                        <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                            <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                    <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                    </a>
                    <?php $state_item = ""; ?>
                <?php endforeach; ?>
                <div class="home-featured-menu">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                </div>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN STATIC BANNER -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/banners/banner-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 8 (POLICIALES) (BLOQUE 6) -->
    <div class="home-block">
        <?php if(count($parte8) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #966A38">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte8)['Categoria__codigo']]); ?>" class="right button" style="background: #966A38; color: #966A38;">Ver m&aacute;s</a>
                <h2><?= reset($parte8)['Categoria__nombre'] ?></h2>
            </div>
            <!-- BEGIN .article-list-block -->
            <div class="article-list-block">

            <?php foreach($parte8 as $noticia): ?>
                <div class="item">
                    <div class="item-header">
                        <a href="#<?= $noticia['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $noticia['Portal__nombre'] ?></i>
                        </a>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?= $fecha; ?></i>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                    <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </figure>
                        </a>
                    </div>
                    <div class="item-content">
                        <div class="content-category">
                            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia['Categoria__codigo']]); ?>" style="color: #276197;"><?= $noticia['Categoria__nombre'] ?></a>
                            <div class="social-icon">
                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;t=<?= $noticia['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;text=<?= $noticia['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                            </div>
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($noticia['descripcion'], 0, 180) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 2 (DEPORTES) (SLIDER) -->
    <div class="home-block">
        <?php if(count($parte2)>0): ?>
            <div class="main-title" style="border-left: 4px solid #44BBE3">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte2)['Categoria__codigo']]); ?>" class="right button" style="background: #44BBE3; color: #44BBE3;">Ver m&aacute;s</a>
                <h2><?= h(reset($parte2)['Categoria__nombre']) ?></h2>
            </div>
            <div class="home-featured-article">
                <?php $state_item = "active"; ?>
                <?php foreach($parte2 as $noticia): ?>
                    <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="home-featured-item <?= $state_item ?>">
                        <i class="seccion-nota <?= $state_item ?>"><?= $noticia['Portal__nombre'] ?></i>
                        <span class="feature-text">
                            <strong><?= $noticia['titulo'] ?></strong>
                            <span><?= $noticia['descripcion'] ?></span>
                        </span>
                        <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                            <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                    <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                    </a>
                    <?php $state_item = ""; ?>
                <?php endforeach; ?>
                <div class="home-featured-menu">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                </div>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN STATIC BANNER -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/banners/banner-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 7 (SOCIALES) (BLOQUE 6) -->
    <div class="home-block">
        <?php if(count($parte7) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #BDBA17">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte7)['Categoria__codigo']]); ?>" class="right button" style="background: #BDBA17; color: #BDBA17;">Ver m&aacute;s</a>
                <h2><?= reset($parte7)['Categoria__nombre'] ?></h2>
            </div>
            <!-- BEGIN .article-list-block -->
            <div class="article-list-block">

            <?php foreach($parte7 as $noticia): ?>
                <div class="item">
                    <div class="item-header">
                        <a href="#<?= $noticia['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $noticia['Portal__nombre'] ?></i>
                        </a>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?= $fecha; ?></i>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                    <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </figure>
                        </a>
                    </div>
                    <div class="item-content">
                        <div class="content-category">
                            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia['Categoria__codigo']]); ?>" style="color: #276197;"><?= $noticia['Categoria__nombre'] ?></a>
                            <div class="social-icon">
                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;t=<?= $noticia['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;text=<?= $noticia['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                            </div>
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($noticia['descripcion'], 0, 180) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 4 (SOCIEDAD) (BLOQUE 5) -->
    <div class="home-block">
        <?php if(count($parte4) > 0):
            $parte4_nota1 = array_pop($parte4);
        ?>
        <div class="main-title" style="border-left: 4px solid #429D4A">
            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $parte4_nota1['Categoria__codigo']]); ?>" class="right button" style="background: #429D4A; color: #429D4A;">Ver m&aacute;s</a>
            <h2><?= $parte4_nota1['Categoria__nombre'] ?></h2>
        </div>

        <!-- BEGIN .category-default-block -->
        <div class="category-default-block paragraph-row">

            <!-- BEGIN .column8 -->
            <div class="column8">
                <div class="item-main">
                    <div class="item-header">
                        <a href="#<?= $parte4_nota1['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $parte4_nota1['Portal__nombre'] ?></i>
                        </a>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte4_nota1['id'], Cake\Utility\Inflector::slug(strtolower($parte4_nota1['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($parte4_nota1['Imagen__filename']) && !is_null($parte4_nota1['Imagen__file_url'])): ?>
                                    <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $parte4_nota1['Imagen__file_url'] . '/' . $parte4_nota1['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $parte4_nota1['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 547px; height: 340px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <!--<svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>-->
                                <?php $fecha = substr($parte4_nota1['publicado'], -11, -9) . "/" . substr($parte4_nota1['publicado'], -14, -12) . " " . substr($parte4_nota1['publicado'], -8, -3); ?>
                                <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                                <figcaption>
                                    <!--<i class="seccion-nota"><?= $parte4_nota1['Portal__nombre'] ?></i>-->
                                    <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                </figcaption>
                            </figure>
                        </a>
                    </div>

                    <div class="item-content">
                        <div class="social-icon">
                            <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte4_nota1['id']]); ?>&amp;t=<?= $parte4_nota1['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte4_nota1['id']]); ?>&amp;text=<?= $parte4_nota1['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $parte4_nota1['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>                            
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $parte4_nota1['id'], Cake\Utility\Inflector::slug(strtolower($parte4_nota1['titulo']))]); ?>"><?= $parte4_nota1['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($parte4_nota1['descripcion'], 0, 200) ?></p>
                    </div>
                </div>
            <!-- END .column8 -->
            </div>

            <!-- BEGIN .column4 -->
            <div class="column4 smaller-articles">
                <?php foreach(array_reverse($parte4) as $noticia): ?>
                    <div class="item">
                        <div class="item-header">
                            <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                            <?php else: ?>
                                <?php foreach($portales as $portal): ?>
                                    <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                        <img style="width: 100px; height: 75px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                        <?php break; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="item-content">
                            <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                            <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>-->
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                                <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $fecha; ?></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- END .column4 -->
        </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN STATIC BANNER -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/banners/banner-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 6 (ESPECTACULO) (BLOQUE 6) -->
    <div class="home-block">
        <?php if(count($parte6) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #BDBA17">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte6)['Categoria__codigo']]); ?>" class="right button" style="background: #BDBA17; color: #BDBA17;">Ver m&aacute;s</a>
                <h2><?= reset($parte6)['Categoria__nombre'] ?></h2>
            </div>
            <!-- BEGIN .article-list-block -->
            <div class="article-list-block">

            <?php foreach($parte6 as $noticia): ?>
                <div class="item">
                    <div class="item-header">
                        <a href="#<?= $noticia['Portal__codigo'] ?>">
                            <i class="seccion-nota"><?= $noticia['Portal__nombre'] ?></i>
                        </a>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?= $fecha; ?></i>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="image-hover">
                            <figure>
                                <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                                    <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                                <?php else: ?>
                                    <?php foreach($portales as $portal): ?>
                                        <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                            <img style="width: 265px; height: 181px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </figure>
                        </a>
                    </div>
                    <div class="item-content">
                        <div class="content-category">
                            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia['Categoria__codigo']]); ?>" style="color: #276197;"><?= $noticia['Categoria__nombre'] ?></a>
                            <div class="social-icon">
                                <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;t=<?= $noticia['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                                <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>&amp;text=<?= $noticia['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                                <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                            </div>
                        </div>
                        <h3><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <p><?= $this->Texto->limitarTexto($noticia['descripcion'], 0, 180) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN ADSENSE -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/adsense/primer-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 5 (TECNOLOGÍA) (SLIDER) -->
    <div class="home-block">
        <?php if(count($parte5) > 0): ?>
            <div class="main-title" style="border-left: 4px solid #A5DF00">
                <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', reset($parte5)['Categoria__codigo']]); ?>" class="right button" style="background: #A5DF00; color: #A5DF00;">Ver m&aacute;s</a>
                <h2><?= h(reset($parte5)['Categoria__nombre']) ?></h2>
            </div>
            <div class="home-featured-article">
                <?php $state_item = "active"; ?>
                <?php foreach($parte5 as $noticia): ?>
                    <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>" class="home-featured-item <?= $state_item ?>">
                        <i class="seccion-nota <?= $state_item ?>"><?= $noticia['Portal__nombre'] ?></i>
                        <span class="feature-text">
                            <strong><?= $noticia['titulo'] ?></strong>
                            <span><?= $noticia['descripcion'] ?></span>
                        </span>
                        <?php if(!is_null($noticia['Imagen__filename']) && !is_null($noticia['Imagen__file_url'])): ?>
                            <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $noticia['Imagen__file_url'] . '/' . $noticia['Imagen__filename'] ?>">
                        <?php else: ?>
                            <?php foreach($portales as $portal): ?>
                                <?php if($portal->codigo == $noticia['Portal__codigo'] && $portal->has('imagen')): ?>
                                    <img style="width: 829px; height: 448px;" src="<?= Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url . '/' . $portal->imagen->filename ?>">
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php $fecha = substr($noticia['publicado'], -11, -9) . "/" . substr($noticia['publicado'], -14, -12) . " " . substr($noticia['publicado'], -8, -3); ?>
                        <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $fecha; ?></i>
                    </a>
                    <?php $state_item = ""; ?>
                <?php endforeach; ?>
                <div class="home-featured-menu">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                </div>
            </div>
        <?php endif; ?>
        <!-- END .category-default-block -->
    </div>
    <!-- END .home-block -->

    <!-- BEGIN STATIC BANNER -->
    <div class="home-block">
        <div class="banner">
            <?= $this->element('Front/banners/banner-728x90') ?>
        </div>
        <!-- END .home-block -->
    </div>

    <!-- BEGIN PART 9 (PROVINCIALES), PART 10 (POLITICA), PART 11 (ECONOMIA)
    <div class="home-block">

        <!-- BEGIN .article-links-block -->
        <!-- <div class="article-links-block">
            <?php 
            /*$color_section_pre_footer = [
                ['color' => '#EF8722;'],
                ['color' => '#276197;'],
                ['color' => '#6BAB32;']
            ];
            ?>
            <?php if(count($parte9)>0): ?>
            <?php $color = array_pop($color_section_pre_footer); ?>
            <?php $nota_9 = array_pop($parte9); ?>
            <div class="item">
                <h3 style="color: <?= $color['color'] ?> border-bottom: 3px solid <?= $color['color'] ?>"><?= $nota_9['Categoria__nombre'] ?></h3>
                <div class="post-item">
                    <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_9['id'], Cake\Utility\Inflector::slug(strtolower($nota_9['titulo']))]); ?>"><?= $nota_9['titulo'] ?></a></h3>
                    <div class="item-details">
                        <div class="item-head">
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota"><?= $nota_9['Portal__nombre'] ?></i>
                            </a>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_9['id'], Cake\Utility\Inflector::slug(strtolower($nota_9['titulo']))]); ?>" class="image-hover">
                                <figure>
                                    <?php if($nota_9->has('imagenes') && !empty($nota_9->imagenes)): ?>
                                        <?php foreach($nota_9->imagenes as $imagen): ?>
                                            <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url.'/'.$imagen->filename ?>&h=195&w=265" />
                                            <?php break; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach($portales as $portal): ?>
                                            <?php if($portal->codigo == $nota_9['Portal__codigo'] && $portal->has('imagen')): ?>
                                                <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url.'/'.$portal->imagen->filename ?>&h=195&w=265" />
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!--<svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>-->
                                    <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $nota_9->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                                    <figcaption>
                                        <i class="seccion-nota"><?= $nota_9['Portal__nombre'] ?></i>
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="social-icon">
                            <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_9['id']]); ?>&amp;t=<?= $nota_9['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_9['id']]); ?>&amp;text=<?= $nota_9['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_9['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                        </div>
                        <div class="clear-float"></div>
                    </div>
                </div>
                <?php foreach(array_reverse($parte9) as $noticia):?>
                    <div class="post-item">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <div>
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'seccion', $nota_9['Categoria__codigo']]); ?>" class="archive-button" style="background-color: <?= $color['color'] ?>">M&aacute;s art&iacute;culos</a>
            </div>
            <?php endif; ?>
            <?php if(count($parte10)>0): ?>
            <?php $color = array_pop($color_section_pre_footer); ?>
            <?php $nota_10 = array_pop($parte10); ?>
            <div class="item">
                <h3 style="color: <?= $color['color'] ?> border-bottom: 3px solid <?= $color['color'] ?>"><?= $nota_10['Categoria__nombre'] ?></h3>
                <div class="post-item">
                    <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_10['id'], Cake\Utility\Inflector::slug(strtolower($nota_10['titulo']))]); ?>"><?= $nota_10['titulo'] ?></a></h3>
                    <div class="item-details">
                        <div class="item-head">
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota"><?= $nota_10['Portal__nombre'] ?></i>
                            </a>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_10['id'], Cake\Utility\Inflector::slug(strtolower($nota_10['titulo']))]); ?>" class="image-hover">
                                <figure>
                                    <?php if($nota_10->has('imagenes') && !empty($nota_10->imagenes)): ?>
                                        <?php foreach($nota_10->imagenes as $imagen): ?>
                                            <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url.'/'.$imagen->filename ?>&h=195&w=265" />
                                            <?php break; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach($portales as $portal): ?>
                                            <?php if($portal->codigo == $nota_10['Portal__codigo'] && $portal->has('imagen')): ?>
                                                <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url.'/'.$portal->imagen->filename ?>&h=195&w=265" />
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!--<svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>-->
                                    <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $nota_10->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                                    <figcaption>
                                        <i class="seccion-nota"><?= $nota_10['Portal__nombre'] ?></i>
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="social-icon">
                            <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_10['id']]); ?>&amp;t=<?= $nota_10['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_10['id']]); ?>&amp;text=<?= $nota_10['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_10['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                        </div>
                        <div class="clear-float"></div>
                    </div>
                </div>
                <?php foreach(array_reverse($parte10) as $noticia):?>
                    <div class="post-item">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <div>
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'seccion', $nota_10['Categoria__codigo']]); ?>" class="archive-button" style="background-color: <?= $color['color'] ?>">M&aacute;s art&iacute;culos</a>
            </div>
            <?php endif; ?>
           <?php if(count($parte11)>0): ?>
            <?php $color = array_pop($color_section_pre_footer); ?>
            <?php $nota_11 = array_pop($parte11); ?>
            <div class="item">
                <h3 style="color: <?= $color['color'] ?> border-bottom: 3px solid <?= $color['color'] ?>"><?= $nota_11['Categoria__nombre'] ?></h3>
                <div class="post-item">
                    <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_11['id'], Cake\Utility\Inflector::slug(strtolower($nota_11['titulo']))]); ?>"><?= $nota_11['titulo'] ?></a></h3>
                    <div class="item-details">
                        <div class="item-head">
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota"><?= $nota_11['Portal__nombre'] ?></i>
                            </a>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_11['id'], Cake\Utility\Inflector::slug(strtolower($nota_11['titulo']))]); ?>" class="image-hover">
                                <figure>
                                    <?php if($nota_11->has('imagenes') && !empty($nota_11->imagenes)): ?>
                                        <?php foreach($nota_11->imagenes as $imagen): ?>
                                            <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $imagen->file_url.'/'.$imagen->filename ?>&h=195&w=265" />
                                            <?php break; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach($portales as $portal): ?>
                                            <?php if($portal->codigo == $nota_11['Portal__codigo'] && $portal->has('imagen')): ?>
                                                <img src="/thumb.php?img=<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_notas') . $portal->imagen->file_url.'/'.$portal->imagen->filename ?>&h=195&w=265" />
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <!--<svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>-->
                                    <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: 0; position: absolute; margin-right: 0px !important; opacity: 1;"><?php echo $nota_11->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                                    <figcaption>
                                        <i class="seccion-nota"><?= $nota_11['Portal__nombre'] ?></i>
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                        <div class="social-icon">
                            <a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_11['id']]); ?>&amp;t=<?= $nota_11['titulo'] ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-facebook facebook"></i></a>
                            <a rel="nofollow" href="https://twitter.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_11['id']]); ?>&amp;text=<?= $nota_11['titulo'] ?>&amp;via=<?= Cake\Core\Configure::read('usuario_twitter') ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=300,width=600');return false;" target="_blank"><i class="fa fa-twitter twitter"></i></a>
                            <a rel="nofollow" href="https://plus.google.com/share?url=<?= Cake\Core\Configure::read('dominio').$this->Url->build(['controller' =>'noticias','action' =>'articulo', $nota_11['id']]); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=350,width=480');return false;" target="_blank"><i class="fa fa-google-plus gplus"></i></a>
                        </div>
                        <div class="clear-float"></div>
                    </div>
                </div>
                <?php foreach(array_reverse($parte11) as $noticia):?>
                    <div class="post-item">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia['id'], Cake\Utility\Inflector::slug(strtolower($noticia['titulo']))]); ?>"><?= $noticia['titulo'] ?></a></h3>
                        <div>
                            <a href="#<?= $noticia['Portal__codigo'] ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia['Portal__nombre'] ?></i>
                                <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'seccion', $nota_11['Categoria__codigo']]); ?>" class="archive-button" style="background-color: <?= $color['color'] ?>">M&aacute;s art&iacute;culos</a>
            </div>
            <?php endif; */?>
        <!-- END .article-links-block -->
        <!--  </div>
    </div>  -->
    <!-- END .home-block -->

<!-- END .left-content -->
</div>
<?= $this->Html->scriptStart(['block' => true]) ?>
    jQuery(document).ready(function() {

        var banner3 = <?php echo $banner3; ?>;
        var banner2 = <?php echo $banner2; ?>;
        var banner = <?php echo $banner; ?>;
        if(banner3.mobile == true && banner3.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('#banner3').html('');
            $('#banner3').html('<?php if($banner3->href != ""): ?><a href="<?= $banner3->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner3->file_mobile_url . '/' . $banner3->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner3->file_mobile_url . '/' . $banner3->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');
        }
        if(banner2.mobile == true && banner2.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('#banner2').html('');
            $('#banner2').html('<?php if($banner2->href != ""): ?><a href="<?= $banner2->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner2->file_mobile_url . '/' . $banner2->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner2->file_mobile_url . '/' . $banner2->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');
        }
        if(banner.mobile == true && banner.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('#banner').html('');
            $('#banner').html('<?php if($banner->href != ""): ?><a href="<?= $banner->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');
        }
    });
<?= $this->Html->scriptEnd() ?>