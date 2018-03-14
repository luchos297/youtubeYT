<div class="widget">
    <h3>Portadas</h3>
    <div class="article-block">
        <?php foreach($portadas as $portada): ?>
            <div class="item">
                <div class="content-category">
                    <h3><?= $portada['portal']; ?></h3>
		</div>
                <div class="item-header">
                    <a href="<?= $portada['url']; ?>" target="_blank">
                        <img src="<?= Cake\Core\Configure::read('dominio') . $portada['imagen']; ?>" style="width: 300px;"/>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>