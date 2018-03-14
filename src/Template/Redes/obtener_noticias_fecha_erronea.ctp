<nav class="large-3 medium-4 columns" id="actions-sidebar">
    
</nav>
<div class="portales form large-9 medium-8 columns content">
    <b>Verification OK!</b>
    <?php
    echo '<pre>';
    if(count($noticias) > 0 ){
        echo 'Las siguientes noticias tienen la fecha incorrecta:';
    }
    else{
        echo 'No se encontraron noticias con la fecha incorrecta.';
    }
    echo var_dump($noticias);
    echo '</pre>';
    ?>
</div>