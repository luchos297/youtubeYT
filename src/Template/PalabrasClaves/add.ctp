<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Palabras Claves'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Articulo Palabra Clave'), ['controller' => 'ArticuloPalabraClave', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Articulos'), ['controller' => 'Articulos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Articulo'), ['controller' => 'Articulos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="palabrasClaves form large-9 medium-8 columns content">
    <?= $this->Form->create($palabrasClave) ?>
    <fieldset>
        <legend><?= __('Add Palabras Clave') ?></legend>
        <?php
            echo $this->Form->input('texto');
            echo $this->Form->input('creado');
            echo $this->Form->input('modificado');
            echo $this->Form->input('articulos._ids', ['options' => $articulos]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
