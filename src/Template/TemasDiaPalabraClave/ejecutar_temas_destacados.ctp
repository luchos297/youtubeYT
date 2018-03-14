<nav class="large-3 medium-4 columns" id="actions-sidebar">
    
</nav>
<div class="portales form large-9 medium-8 columns content">
    <b>Update OK!</b>
    <?php
    echo '<pre>';    
    echo 'Se han recuperado ' . count($titulos) . ' títulos de noticias: ';
    echo var_dump($titulos);
    echo '</pre>';
    echo 'Las palabras clave más utilizadas fueron: ';
    echo '<pre>';
    echo var_dump($claves);
    echo '</pre>';
    ?>
</div>