<header class="header willfix">
				
    <!-- BEGIN .wrapper -->
    <div class="wrapper">

        <div class="header-right">
            <!--<nav class="main-menu">-->
            <nav class="in-header">
                <div style="float: left; width: 40%" class="header-marca">
                    <div id="fecha" style="top: 57%;"></div>
                    <a href="<?= $this->Url->build(['controller' =>'noticias','action' =>'index']); ?>" class="otanimation" data-anim-object=".header-logo a.otanimation img, .header-logo a.otanimation h1" data-anim-in="flipOutX" data-anim-out="bounceIn">
                        <?= $this->Html->image('/img/images/marca2.jpg'); ?>
                    </a>
                </div>
                <div style="float: left; width: 25%">.</div>
                <div style="float: left; width: 20%"><?= $this->element('Front/widget-clima', [], ['cache' => true]); ?></div>
                <div style="float: left;width: 15%;">
                    <nav style="padding:30% 0 0">
                        <a href="http://www.facebook.com/885480398208688" target="_blank" rel="nofollow"><i class="fa fa-facebook-square fa-3x facebook" alt="Facebook" title="Facebook"></i></a>
                        <a href="https://twitter.com/vista_medios" target="_blank" rel="nofollow"><i class="fa fa-twitter-square fa-3x twitter" alt="Twitter" title="Twitter"></i></a>
                        <a href="https://plus.google.com/u/0/107350038594612955632" target="_blank" rel="nofollow"><i class="fa fa-google-plus-square fa-3x gplus" alt="Google+" title="Google+"></i></a>
                    </nav>
                </div>
            </nav>

            <nav class="under-menu">
                <ul class="load-responsive" rel="Menú">
                    <?php if(!empty($portales_menu)): ?>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'index']); ?>"><span><strong style="font-family: 'Montserrat',sans-serif;"><?= 'Diarios' ?></strong></span>
                            <ul>
                            <?php foreach($portales_menu as $portal): ?>
                                <li><strong style="font-family: 'Montserrat',sans-serif;"><?= $this->Html->link($portal->nombre, ['controller' => 'noticias', 'action' => 'portales', $portal->codigo], ['escape' => false]); ?></strong></li>
                            <?php endforeach; ?>
                            </ul>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php foreach($menu as $categoria): ?>
                    <li>
                        <?php if(!empty($categoria->childs)): ?>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'index']); ?>"><span><strong style="font-family: 'Montserrat',sans-serif;"><?= $categoria->nombre ?></strong></span>
                            <ul>
                            <?php foreach($categoria->childs as $subcategoria): ?>
                                <li><strong style="font-family: 'Montserrat',sans-serif;"><?= $this->Html->link($subcategoria->nombre, ['controller' => 'noticias', 'action' => strtolower($categoria->codigo), $subcategoria->codigo], ['escape' => false]); ?></strong></li>
                            <?php endforeach; ?>
                            </ul>
                        </a>
                        <?php elseif(!empty($categoria->palabras_claves)): ?>
                            <strong style="font-family: 'Montserrat',sans-serif;"><?= $this->Html->link($categoria->nombre, ['controller' => 'noticias', 'action' => 'seccion', $categoria->codigo], ['escape' => false]); ?></strong>
                        <?php else: ?>
                            <strong style="font-family: 'Montserrat',sans-serif;"><?= $this->Html->link($categoria->nombre, ['controller' => 'noticias', 'action' => strtolower($categoria->codigo)], ['escape' => false]); ?></strong>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

        </div>
        <div class="clear-float"></div>

    <!-- END .wrapper -->
    </div>

<!-- END .header -->
</header>