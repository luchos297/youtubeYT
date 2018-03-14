<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Publicidades'), ['controller'=>'banners', 'action' => 'index']) ?>
            </li>
            <li class="active">Nueva Publicidad</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Publicidad</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">            
            <div class="panel-body">
                <?= $this->Form->create($banner,['type' => 'file', 'class' => '']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';

                    echo $this->Form->input('filename', ['type' => 'file', 'label' => 'Imagen', 'accept' => '.gif, .jpg, .jpeg, .png, .swf']);
                    echo '<center><div id ="imagen-banner"></div></center>';
                                        
                    echo $this->Form->input('filename_mobile', ['type' => 'file', 'label' => 'Imagen Mobile', 'required' => 'false', 'accept' => '.png']);
                    echo '<center><div id ="imagen-banner-miniatura"></div></center>';                    
                      
                    echo $this->Form->input('banner_tipos_id', ['options' => $banners_tipo, 'class'=> $class_input,'label' => 'Tipo']); 
                    echo $this->Form->input('descripcion', ['label' => 'Descripción', 'class'=> $class_input]);                        
                    echo $this->Form->input('href', ['type' => 'url', 'label' => 'Hipervinculo', 'class'=> $class_input]);                    
                    echo $this->Form->checkbox('mobile');
                    echo $this->Form->label('mobile', 'Mobile');
                ?>                
                <div class="form-group">
                    <?= $this->Form->button(__('Guardar'),['id' => 'confirm', 'class' => 'btn btn-primary']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type'=>'button']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    function archivo(evt) {        
        var files = evt.target.files; // FileList object

        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    //Insertamos la imagen                      
                    $("#imagen-banner").html('<object><param name="banner" value="'+e.target.result+'"><embed src="'+e.target.result+'"></embed></object>');                    
                };
          })(f);

          reader.readAsDataURL(f);
        }        
    }    
    document.getElementById('filename').addEventListener('change', archivo, false);
    
    function mobile(evt) {        
        var files = evt.target.files; // FileList object

        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    //Insertamos la imagen
                    $("#imagen-banner-miniatura").html('<img src="'+e.target.result+'" class="form-group"/>');
                };
          })(f);

          reader.readAsDataURL(f);
        }        
    }    
    document.getElementById('filename_mobile').addEventListener('change', mobile, false);    
</script>