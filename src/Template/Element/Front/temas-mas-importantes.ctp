<?php if(count($especial) > 0): ?>
    <div class="tag-cloud-body" style="padding-top: 0px; margin-bottom: 30px; margin-top: -20px; border-top: 0px;" align="center">
        <h3 style="display: inline; margin-right: 10px; vertical-align: middle;">HOY</h3>
        <?php foreach($especial as $seccion) : ?>
            <a href="<?= $this->Url->build(['controller' => 'noticias', 'action' => 'secciones', $seccion->codigo]); ?>"><strong style="display: inline; font-family: 'Montserrat',sans-serif;"><?= preg_replace('/-/', ' ', Cake\Utility\Inflector::slug(strtolower($seccion->nombre))) ?></strong></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>