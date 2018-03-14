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
            <li><?= $this->Html->link(__('Artículos'), ['controller'=>'articulos', 'action' => 'index']) ?>
            </li>
            <li class="active">Nuevo Artículo</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Artículo</h1>
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
                <?= $this->Form->create($articulo,['type' => 'file', 'class' => '', 'id'=>'bootstrapTagsInputForm', 'onkeypress'=>'return event.keyCode != 13;']) ?>
                    <!--<legend><?= __('Add Articulo') ?></legend>-->
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>',
                            'dateWidget' => '
                                <div class="form-group row"><div class="col-sm-8"><div class="col-sm-2">{{day}}</div>
                                <div class="col-sm-2">{{month}}</div>
                                <div class="col-sm-2">{{year}}</div>
                                <div class="col-sm-2">{{hour}}</div>
                                <div class="col-sm-2">{{minute}}</div></div></div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';
                        echo $this->Form->input('categoria_id', ['label' => 'Categoría', ['class' => $class_label], 'options' => $categorias, 'class'=> $class_input]);
                        echo $this->Form->input('portal_id', ['options' => $portales, 'class'=> $class_input,'label' => ['class' => $class_label]]);
                        echo $this->Form->input('url',['label' => ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('url_rss',['label' => ['class' => $class_label], 'class'=> $class_input]);
                         echo $this->Form->input('titulo',['label' => 'Título', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('descripcion',['label' => 'Descripción', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('texto',['label' => ['class' => $class_label], 'class'=> $class_input.' html-editor']);
                        echo $this->Form->input('palabras_claves',[
                            'label' => 'Palabras claves', 
                            'name' => 'palabras_claves',
                            'class' => $class_input,
                            'data-role' => 'tagsinput',
                            'value' => '']);
                        echo $this->Form->input('visitas',['label' => ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('filename[]', ['type' => 'file', 'label'=>'Imagen/es', 'multiple', 'accept'=>'.gif, .jpg, .jpeg, .png']);
                        
                        ?>
                        <div id ="imagen-articulo">                        
                        </div>
                        <?php
                        
                        echo '<div id ="imagen-articulo"></div>';
                        
                        echo $this->Form->input('publicado', [
                            'type' => 'datetime', 
                            'interval' => 2, 
                            'class'=> $class_input,
                            'monthNames' => false,
                            'day' => [
                                'class' => $class_input,
                            ],
                            'year' => [
                                'class' => $class_input,
                            ],
                            'month' => [
                                'class' => $class_input,
                            ],
                            'hour' => [
                                'class' => $class_input,
                            ],
                            'minute' => [
                                'class' => $class_input,
                            ]
                        ]);
                        echo $this->Form->input('habilitado', ['label' => ['class' => $class_label], 'class'=> 'icheck']);
                        //echo $this->Form->input('creado');
                        //echo $this->Form->input('modificado');
                        //echo $this->Form->input('tiene_imagen');
                        //echo $this->Form->input('tiene_video');
                        //echo $this->Form->input('localizacion');
                    ?>
                <div class="form-group">
                    <?= $this->Form->button(__('Guardar'),['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type'=>'button']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script(['/assets/plugins/ckeditor/ckeditor.js']) ?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
<?= $this->Html->script(['//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js']) ?>
<script>
    $(document).ready(function() {        
        CKEDITOR.replace('texto');
        
        $('#bootstrapTagsInputForm')
        .find('[name="palabras_claves_"]')
            // Revalidate the cities field when it is changed
            .end();
    });
    
    function archivo(evt) {
        $('#imagen-articulo').empty();
        var files = evt.target.files; // FileList object

        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) {           
            //Solo admitimos imágenes.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    // Insertamos la/s imagen/es
                    $("#imagen-articulo").append('<img src="'+e.target.result+'" class="form-group"/>');
                    
                };
          })(f);

          reader.readAsDataURL(f);
        }        
    }
    
    document.getElementById('filename').addEventListener('change', archivo, false);
</script>