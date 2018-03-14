<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Palabras Clave'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Articulos'), ['controller' => 'Articulos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Articulo'), ['controller' => 'Articulos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="palabrasClaves index large-9 medium-8 columns content">
    <h3><?= __('Palabras Claves') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('texto') ?></th>
                <th><?= $this->Paginator->sort('creado') ?></th>
                <th><?= $this->Paginator->sort('modificado') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($palabrasClaves as $palabrasClave): ?>
            <tr>
                <td><?= $this->Number->format($palabrasClave->id) ?></td>
                <td><?= h($palabrasClave->texto) ?></td>
                <td><?= h($palabrasClave->creado) ?></td>
                <td><?= h($palabrasClave->modificado) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $palabrasClave->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $palabrasClave->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $palabrasClave->id], ['confirm' => __('Are you sure you want to delete # {0}?', $palabrasClave->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
