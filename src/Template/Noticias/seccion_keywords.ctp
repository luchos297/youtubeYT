<!-- BEGIN .wrapper -->
<div class="wrapper">	

    <!-- <div class="main-content has-sidebar"> -->
    <div class="main-content has-double-sidebar">

        <!-- BEGIN .left-content -->
        <div class="left-content">
            <div class="main-title" style="border-left: 4px solid #276197;">
                <h2><?= $clave ?></h2>
            </div>

            <div class="article-list">
                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion);?>
                    <?php if($noticia->has('imagenes') && !empty($noticia->imagenes)): ?>
                        <div class="item-header">
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover">
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <div style="clear:both"></div>
                    <?php endif; ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- BEGIN .home-block -->
                <div class="home-block">
                    <div class="banner" id="banner">
                        <?php $banner = array_pop($banners_728x90);
                        $width = $banner->banner_tipo->ancho * 0.82;
                        $height = $banner->banner_tipo->alto * 0.82; ?>

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

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <?php if($noticia->has('imagenes') && !empty($noticia->imagenes)): ?>
                        <div class="item-header">  
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover">
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div> 
                    <?php else: ?>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <div style="clear:both"></div>
                    <?php endif; ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <?php if($noticia->has('imagenes') && !empty($noticia->imagenes)): ?>
                        <div class="item-header"> 
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover" >
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <div style="clear:both"></div>
                    <?php endif; ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item image-left">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <?php if($noticia->has('imagenes') && !empty($noticia->imagenes)): ?>
                        <div class="item-header">  
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota seccion-background"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover">
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="item-header">
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover">
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->                                            
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->                                            
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item image-left">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <?php if($noticia->has('imagenes') && !empty($noticia->imagenes)): ?>
                        <div class="item-header">    
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota seccion-background"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover" >
                                <figure>
                                    <?php echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . reset($noticia->imagenes)->file_url . '/' . reset($noticia->imagenes)->filename); ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="item-header">
                            <a href="#<?= $noticia->portal->codigo ?>">
                                <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            </a>
                            <i class="seccion-nota" style="background-color: #F03030; color: #fff; right: -2px; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                            <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>" class="image-hover" >
                                <figure>
                                    <?php foreach($portales as $portal){
                                        if($portal->codigo == $noticia->portal->codigo && $portal->has('imagen')){
                                            echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . $portal->imagen->file_url.'/'.$portal->imagen->filename); 
                                            break;
                                        }
                                    } ?>
                                    <svg viewBox="0 0 100 100" preserveAspectRatio="none"><path d="" fill="#276197" /></svg>
                                    <figcaption>
                                        <!--<i class="seccion-nota"><?= $noticia->portal->nombre ?></i>-->
                                        <!--<span class="hover-text"><i class="fa fa-camera"></i><span></span></span>-->
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->                                            
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="item">
                    <?php if(count($noticias_seccion) > 0): ?>
                    <?php $noticia = array_pop($noticias_seccion); ?>
                    <div class="item-content">
                        <h3><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h3>
                        <p><?= $noticia->descripcion ?></p>
                        <a href="#<?= $noticia->portal->codigo ?>">
                            <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                            <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                        </a>
                        <!--<i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>-->                                            
                        <!--<a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id]); ?>" class="read-more-link">ver<i class="fa fa-angle-double-right"></i></a>-->
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pagination">
                <ul class="pagination">
                    <?= $this->Paginator->prev('<i class="fa fa-caret-left"></i>Anterior',['escape' => false, 'class' => 'prev page-numbers']) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next('Seguir<i class="fa fa-caret-right"></i>',['escape' => false, 'class' => 'next page-numbers']) ?>
                </ul>
            </div>

        <!-- END .left-content -->
        </div>

        <!-- BEGIN .small-sidebar -->
        <div class="small-sidebar">

            <div class="widget">
                <h3 style="color: #e14420; border-bottom: 3px solid #e14420;">&Uacute;ltimas</h3>
                <div class="article-block">
                    <div class="item no-image">
                        <?php foreach(array_reverse($noticias_seccion) as $noticia):?>
                        <div class="item-content">
                            <div style="overflow: hidden;">
                                <a href="#<?= $noticia->portal->codigo ?>">
                                    <i class="seccion-nota seccion-no-absolute seccion-background"><?= $noticia->portal->nombre ?></i>
                                    <i class="seccion-nota seccion-no-absolute seccion-background" style="background-color: #F03030; color: #fff; opacity: 1;"><?php echo $noticia->publicado->i18nFormat('dd/MM HH:mm'); ?></i>
                                </a>
                            </div>
                            <h4><a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h4>
                            <p><?= $this->Texto->limitarTexto($noticia->descripcion, 0, 150) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="widget">
                <div class="banner">
                    <?= $this->element('Front/banners-dinamicos/banner-160x600',['banner' => array_pop($banners_160x600)]) ?>
                </div>
            </div>
            <div class="widget">
                <div class="banner">
                    <?= $this->element('Front/adsense/primer-160x600') ?>
                </div>
            </div>

        <!-- END .small-sidebar -->
        </div>

        <!-- BEGIN #sidebar -->
        <aside id="sidebar">

            <?= $this->element('Front/temas-mas-destacados'); ?>

            <!-- BEGIN .home-block -->
            <div class="home-block">
                <?= $this->element('Front/adsense/primer-300x250') ?>
            </div>

            <?= $this->element('Front/noticias-mas-leidas'); ?>

            <!-- BEGIN .home-block -->
            <div class="home-block">
                <?= $this->element('Front/adsense/primer-300x250') ?>
            </div>

            <?= $this->element('Front/redes-fixed'); ?> 

        <!-- END #sidebar -->
        </aside>

    </div>

<!-- END .wrapper -->
</div>
<?= $this->Html->scriptStart(['block' => true]) ?>
    jQuery(document).ready(function() {

        var banner = <?php echo $banner; ?>;
        if(banner.mobile == true && banner.filename_mobile.indexOf("png") >= 0 && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {                    
            $('#banner').html('');
            $('#banner').html('<?php if($banner->href != ""): ?><a href="<?= $banner->href ?>" target="_blank"><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?></a><!-- END .home-block --><?php else: ?><?= $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . '/' . $banner->filename_mobile) ?><!-- END .home-block --><?php endif; ?>');
        }
    });
<?= $this->Html->scriptEnd() ?>