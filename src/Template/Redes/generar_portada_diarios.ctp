<nav class="large-2 medium-4 columns" style="margin-bottom: 0px; padding-bottom: 0px">
    
</nav>
<div class="large-10 medium-8 columns content" style="margin-bottom: 0px; padding-bottom: 0px">
    <b>Recovered OK!</b>
    <?php
    echo '<pre>';
    if(count($portadas) > 0 ){
        foreach($portadas as $portada){
            echo "<u>Diario: <strong>" . $portada['portal'] . "</strong></u>";
            echo "<p></p>";
            echo "<img src='" . $portada['url'] . "' style='height: 75%;'>";
            echo "<p>&nbsp;</p>";
        }
    }
    echo '</pre>';
    ?>
</div>