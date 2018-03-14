<div class="breaking-news">
    <!-- BEGIN .breaking-news -->
    <div class="breaking-title">
        <h3>ÚLTIMAS</h3>
    </div>
    <div class="breaking-block" style="margin-left: 120px;">
        <ul style="left: -566px;">
            <?php foreach($cartelera as $noticia): ?>
                <?php if($noticia->categoria->color != "#c0c0c0"): ?>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia->categoria->codigo]); ?>" class="break-category" style="background-color: <?= $noticia->categoria->color ?>; color: #fff;"><strong style="font-family: 'Montserrat',sans-serif;"><?= Cake\Utility\Inflector::slug(strtoupper($noticia->categoria->nombre)) ?></strong></a><h4><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h4>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $noticia->categoria->codigo]); ?>" class="break-category" style="background-color: <?= $noticia->categoria->color ?>; color: #fff;"><strong style="font-family: 'Montserrat',sans-serif;"><?= Cake\Utility\Inflector::slug(strtoupper($noticia->categoria->nombre)) ?></strong></a><h4><a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'articulo', $noticia->id, Cake\Utility\Inflector::slug(strtolower($noticia->titulo))]); ?>"><?= $noticia->titulo ?></a></h4>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- END .breaking-news -->
</div>