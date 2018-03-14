<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Vista'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Banner Vista'), ['controller' => 'BannerVista', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Banner Vistum'), ['controller' => 'BannerVista', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Banners'), ['controller' => 'Banners', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Banner'), ['controller' => 'Banners', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vistas index large-9 medium-8 columns content">
    <h3><?= __('Vistas') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('codigo') ?></th>
                <th><?= $this->Paginator->sort('creado') ?></th>
                <th><?= $this->Paginator->sort('modificado') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vistas as $vista): ?>
            <tr>
                <td><?= $this->Number->format($vista->id) ?></td>
                <td><?= h($vista->codigo) ?></td>
                <td><?= h($vista->creado) ?></td>
                <td><?= h($vista->modificado) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $vista->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $vista->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $vista->id], ['confirm' => __('Are you sure you want to delete # {0}?', $vista->id)]) ?>
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
