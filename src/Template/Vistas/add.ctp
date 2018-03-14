<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Vistas'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Banner Vista'), ['controller' => 'BannerVista', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Banner Vistum'), ['controller' => 'BannerVista', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Banners'), ['controller' => 'Banners', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Banner'), ['controller' => 'Banners', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vistas form large-9 medium-8 columns content">
    <?= $this->Form->create($vista) ?>
    <fieldset>
        <legend><?= __('Add Vista') ?></legend>
        <?php
            echo $this->Form->input('codigo');
            echo $this->Form->input('descripcion');
            echo $this->Form->input('creado');
            echo $this->Form->input('modificado');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
