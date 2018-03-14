<style type="text/css">
    .bootstrap-tagsinput {
        width: 100%;
    }
    .label {
        line-height: 2 !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Categorías'), ['controller'=>'categorias', 'action' => 'index']) ?>
            </li>
            <li class="active">Editar Categorías</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Categoría</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title">_</h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <?= $this->Form->create($categoria,['class' => '', 'id' => 'bootstrapTagsInputForm', 'onkeypress'=>'return event.keyCode != 13;']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';                   
                        
                        echo $this->Form->input('categoria_id', ['label' => 'Supercategoría','empty' => [NULL => ''],'options' => $categorias, 'class'=> $class_input]);
                        echo $this->Form->input('nombre',['class'=> $class_input]);
                        echo $this->Form->input('codigo',['label' => 'Código', 'class'=> $class_input]);
                        echo $this->Form->checkbox('en_menu');
                        echo $this->Form->label('en_menu', 'En menu');?> <br> <?php
                        echo $this->Form->checkbox('en_cartelera');
                        echo $this->Form->label('en_cartelera', 'En_cartelera');?> <br> <?php
                        echo $this->Form->checkbox('en_especial');
                        echo $this->Form->label('en_especial', 'En especial');
                    ?>
                    <div class="alert alert-warning alert-dismissable">
                        <p>Si configura su categoría mediante la lectura de palabras claves use el siguiente campo.</p>
                        <br/>
                        <?php
                        $palabras = [];
                        foreach($categoria->palabras_claves as $palabra_clave){
                            $palabras[] = $palabra_clave->texto;
                        }
                        echo $this->Form->input('palabras_claves_',[
                            'label' => 'Palabras claves', 
                            'name' => 'palabras_claves_',
                            'class' => $class_input,
                            'data-role' => 'tagsinput',
                            'value' => implode(',',$palabras)]);
                        ?>
                    </div>
                <div class="form-group">
                    <?= $this->Form->button(__('Guardar'),['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type'=>'button']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
<?= $this->Html->script(['//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js']) ?>
<script>
$(document).ready(function () {
    $('#bootstrapTagsInputForm')
        .find('[name="palabras_claves_"]')
            // Revalidate the cities field when it is changed
            .end();
});
</script>