<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Portales'), ['controller'=>'portales', 'action' => 'index']) ?>
            </li>
            <li class="active">Editar Portal</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Portal</h1>
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
                <?= $this->Form->create($portal,['type' => 'file', 'class' => '']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';                    
                        
                        echo $this->Form->input('nombre',['class' => $class_input]);
                        echo $this->Form->input('codigo',['label' => 'Código','class' => $class_input]);
                        echo $this->Form->input('url',['class' => $class_input]);
                        echo $this->Form->input('filename', ['type' => 'file', 'label'=>'Imagen','accept'=>'.gif, .jpg, .jpeg, .png']);
                        echo $this->Form->checkbox('en_portada');
                        echo $this->Form->label('en_portada', 'En portada');?> <br> <?php
                        echo '<div id ="imagen-portal">';
                        if($portal->has('imagen')){
                            echo $this->Html->image(Cake\Core\Configure::read('path_imagen_rss') . $portal->imagen['file_url'] .'/'.$portal->imagen['filename'],['class'=> 'form-group']);
                        }
                        echo '</div>';
                        //echo $this->Form->input('creado');
                        //echo $this->Form->input('modificado');
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
<script>    
    function archivo(evt) {
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
                    // Insertamos la imagen
                    $("#imagen-portal").html('<img src="'+e.target.result+'" class="form-group"/>');
                    
                };
          })(f);

          reader.readAsDataURL(f);
        }     
    }
    document.getElementById('filename').addEventListener('change', archivo, false);
</script>