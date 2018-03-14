<nav class="sidebar sidebar-left">
    <!--<h5 class="sidebar-header">Navigation</h5>-->
    <ul class="nav nav-pills nav-stacked">
        <!--li>
            <?php //$this->Html->link('<i class="fa fa-home"></i> Inicio', ['controller'=>'administrador', 'action' => 'index'],['escape'=>false]) ?>
        </li-->
        <li class="nav-dropdown">
            <a href="#" title="Artículos">
                <i class="fa fa-list-ul"></i> Art&iacute;culos
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver artículos'), ['controller'=>'articulos', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nuevo artículo'), ['controller'=>'articulos', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-dropdown">
            <a href="#" title="Categorías">
                <i class="fa fa-sort-amount-desc"></i> Categor&iacute;as
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver categorías'), ['controller'=>'categorias', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nueva categoria'), ['controller'=>'categorias', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-dropdown">
            <a href="#" title="Portales">
                <i class="icon-paper-plane"></i> Portales
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver portales'), ['controller'=>'portales', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nuevo portal'), ['controller'=>'portales', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-dropdown">
            <a href="#" title="Rss">
                <i class="fa fa-rss"></i> Rss
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver RSS'), ['controller'=>'rsses', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nuevo RSS'), ['controller'=>'rsses', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-dropdown">
            <a href="#" title="Imágenes">
                <i class="icon-picture"></i> Im&aacute;genes
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver imágenes'), ['controller'=>'imagenes', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nueva imagen'), ['controller'=>'imagenes', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
        <li class="nav-dropdown">
            <a href="#" title="Publicidad / Tipos">
                <i class="fa fa-bullhorn"></i> Publicidad / Tipos
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver publicidades'), ['controller'=>'banners', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nueva publicidad'), ['controller'=>'banners', 'action' => 'add']) ?>
                </li>
                <li><?= $this->Html->link(__('Ver tipos de publicidad'), ['controller'=>'bannerTipos', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nuevo tipo de publicidad'), ['controller'=>'bannerTipos', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>  
        <li class="nav-dropdown">
            <a href="#" title="Configuraciones">
                <i class="fa fa-cogs"></i> Configuraciones
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Menú'), ['controller'=>'categorias', 'action' => 'ordenar_categorias']) ?>
                </li>
<!--                <li><?= $this->Html->link(__('Nuevo usuario'), ['controller'=>'usuarios', 'action' => 'add']) ?>
                </li>-->
            </ul>
        </li>        
        <li class="nav-dropdown">
            <a href="#" title="Usuarios">
                <i class="fa fa-users"></i> Usuarios
            </a>
            <ul class="nav-sub">
                <li><?= $this->Html->link(__('Ver usuarios'), ['controller'=>'usuarios', 'action' => 'index']) ?>
                </li>
                <li><?= $this->Html->link(__('Nuevo usuario'), ['controller'=>'usuarios', 'action' => 'add']) ?>
                </li>
            </ul>
        </li>
    </ul>
</nav>