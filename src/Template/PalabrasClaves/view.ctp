<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Palabras Clave'), ['action' => 'edit', $palabrasClave->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Palabras Clave'), ['action' => 'delete', $palabrasClave->id], ['confirm' => __('Are you sure you want to delete # {0}?', $palabrasClave->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Palabras Claves'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Palabras Clave'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Articulos'), ['controller' => 'Articulos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Articulo'), ['controller' => 'Articulos', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="palabrasClaves view large-9 medium-8 columns content">
    <h3><?= h($palabrasClave->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Texto') ?></th>
            <td><?= h($palabrasClave->texto) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($palabrasClave->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Creado') ?></th>
            <td><?= h($palabrasClave->creado) ?></td>
        </tr>
        <tr>
            <th><?= __('Modificado') ?></th>
            <td><?= h($palabrasClave->modificado) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Articulo Palabra Clave') ?></h4>
        <?php if (!empty($palabrasClave->articulo_palabra_clave)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Articulo Id') ?></th>
                <th><?= __('Palabra Clave Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($palabrasClave->articulo_palabra_clave as $articuloPalabraClave): ?>
            <tr>
                <td><?= h($articuloPalabraClave->id) ?></td>
                <td><?= h($articuloPalabraClave->articulo_id) ?></td>
                <td><?= h($articuloPalabraClave->palabra_clave_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ArticuloPalabraClave', 'action' => 'view', $articuloPalabraClave->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'ArticuloPalabraClave', 'action' => 'edit', $articuloPalabraClave->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ArticuloPalabraClave', 'action' => 'delete', $articuloPalabraClave->id], ['confirm' => __('Are you sure you want to delete # {0}?', $articuloPalabraClave->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Articulos') ?></h4>
        <?php if (!empty($palabrasClave->articulos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Categoria Id') ?></th>
                <th><?= __('Portal Id') ?></th>
                <th><?= __('Url') ?></th>
                <th><?= __('Url Rss') ?></th>
                <th><?= __('Titulo') ?></th>
                <th><?= __('Descripcion') ?></th>
                <th><?= __('Texto') ?></th>
                <th><?= __('Palabras Claves') ?></th>
                <th><?= __('Publicado') ?></th>
                <th><?= __('Habilitado') ?></th>
                <th><?= __('Creado') ?></th>
                <th><?= __('Modificado') ?></th>
                <th><?= __('Tiene Imagen') ?></th>
                <th><?= __('Tiene Video') ?></th>
                <th><?= __('Visitas') ?></th>
                <th><?= __('Localizacion') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($palabrasClave->articulos as $articulos): ?>
            <tr>
                <td><?= h($articulos->id) ?></td>
                <td><?= h($articulos->categoria_id) ?></td>
                <td><?= h($articulos->portal_id) ?></td>
                <td><?= h($articulos->url) ?></td>
                <td><?= h($articulos->url_rss) ?></td>
                <td><?= h($articulos->titulo) ?></td>
                <td><?= h($articulos->descripcion) ?></td>
                <td><?= h($articulos->texto) ?></td>
                <td><?= h($articulos->palabras_claves) ?></td>
                <td><?= h($articulos->publicado) ?></td>
                <td><?= h($articulos->habilitado) ?></td>
                <td><?= h($articulos->creado) ?></td>
                <td><?= h($articulos->modificado) ?></td>
                <td><?= h($articulos->tiene_imagen) ?></td>
                <td><?= h($articulos->tiene_video) ?></td>
                <td><?= h($articulos->visitas) ?></td>
                <td><?= h($articulos->localizacion) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Articulos', 'action' => 'view', $articulos->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Articulos', 'action' => 'edit', $articulos->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Articulos', 'action' => 'delete', $articulos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $articulos->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
