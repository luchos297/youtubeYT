<style>
    .radio-inline, .checkbox-inline {
        padding-left: 0;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Publicidades'), ['controller'=>'banners', 'action' => 'index']) ?>
            </li>
            <li class="active">Editar Publicidad</li>
        </ul>
        <!--breadcrumbs end -->
        <div class="col-md-6"><h1 class="h1">Publicidad</h1></div>
        <div class="col-md-6"><h1 class="h1" class="col-md-6">Asignación de vistas</h1></div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= $this->Form->create($banner, ['type' => 'file', 'class' => '']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';                                                  
                        
                        //File Input para la imagen SWF
                        echo $this->Form->input('filename', ['type' => 'file', 'label'=>'Imagen','accept'=>'.gif, .jpg, .jpeg, .png, .swf']);
                                                
                        echo '&nbsp';
                        echo '<center><div id ="imagen-banner">';                           
                        $width_swf = ($banner->banner_tipo->ancho) * 0.65;
                        $height_swf = ($banner->banner_tipo->alto) * 0.65;                        
                        if(strpos($banner->filename, "swf") !== false ): ?>
                            <object height="<?= $height_swf ?>" width="<?= $width_swf ?>"><param name="<?=$banner->filename ?>" value="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>">
                                <embed src="<?=Cake\Core\Configure::read('dominio') . Cake\Core\Configure::read('path_imagen_banners') . $banner->file_url . '/' . $banner->filename ?>" height="<?= $height_swf ?>" width="<?= $width_swf ?>"></embed>
                            </object> 
                                                                   
                        <?php else:
                            echo $this->Html->image(Cake\Core\Configure::read('path_imagen_banner') . $banner->file_url . "/" . $banner->filename, ['class'=> 'form-group', 'style' => 'max-height:200px']);
                        endif;
                        echo '</div></center>';
                        echo '&nbsp';
                        
                        if($banner->filename_mobile != ""):
                        
                            //File Input para la imagen
                            echo $this->Form->input('filename_mobile', ['type' => 'file', 'label'=>'Imagen Mobile', 'required' => 'false', 'accept'=>'.png']);

                            echo '&nbsp';
                            echo '<center><div id ="imagen-banner_mobile">';
                            $width_img = ($banner->banner_tipo->ancho) * 0.65;
                            $height_img = ($banner->banner_tipo->alto) * 0.65;
                            echo $this->Html->image(Cake\Core\Configure::read('path_imagen_banner_mobile') . $banner->file_mobile_url . "/" . $banner->filename_mobile, ['class'=> 'form-group', 'style' => 'max-height:200px']);
                            echo '</div></center>';
                        endif;                        
                        
                        echo '&nbsp';
                        echo $this->Form->input('banner_tipos_id', ['options' => $banners_tipo, 'class'=> $class_input,'label' => 'Tipo']);
                        echo $this->Form->input('descripcion',['label' => 'Descripción', 'class'=> $class_input]);       
                        echo $this->Form->input('href', ['type' => 'url', 'label' => 'Hipervinculo', 'class'=> $class_input]);
                    ?>                        
                <div class="form-group">
                    <?= $this->Form->button(__('Guardar'),['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type'=>'button']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default panel-vista-posicion">
            <div class="panel-body">
                <div class="col-md-12 alert alert-success">
                    <div class="col-md-4">
                    <?= $this->Form->input('vista_id[]', ['options' => $vistas, 'class'=> $class_input,'label' => 'Vista', 'onchange' => 'agregarChechboxes()']); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vista-id">Prioridad</label>  
                            <!--<label class="radio-inline">-->                            
                            <div class="checkbox-posicion" style="display: flex; align-items: center; justify-content: center; vertical-align: center;">                            
                            </div>
                        </div >
                    </div>
                    <div class="col-md-2">
                        <br>
                        <?= $this->Form->button('<i class="fa fa-plus"></i>',['id' => 'agregar', 'type'=>'button', 'class' => 'agregar plus btn btn-primary', 'escape' => false]);?>
                    </div>
                    <div id="error-log"></div>
                </div>
                <?php 
                $vista_posicion_array = []; 
                if($banner->has('banner_vista')){
                    foreach($banner->banner_vista as $banner_vista){
                        if (array_key_exists($banner_vista->vista->codigo,$vista_posicion_array)){
                            array_push($vista_posicion_array[$banner_vista->vista->codigo], $banner_vista->posicion);
                        }
                        else{
                            $vista_posicion_array[$banner_vista->vista->codigo] = [];
                            $vista_posicion_array[$banner_vista->vista->codigo][] = $banner_vista->posicion;
                        }
                    }
                }
                ?>
                <div class="col-md-12 vista-posicion-tabla">
                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vista</th>
                                    <th>Prioridad</th>
                                    <th></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php if(count($vista_posicion_array) > 0): ?>
                                    <?php foreach($vista_posicion_array as $key => $posicion): ?>
                                    <tr>
                                    <td class="vista">    
                                        <?= $key ?>
                                    </td>
                                    <td class='posicion'>
                                        <?php asort($posicion) ?>
                                        <?= implode(',', $posicion) ?>
                                    </td>
                                    <td>
                                        <?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal','escape'=>false, 'onClick' => 'borrarParent(this);']) ?>
                                    </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>

<?= $this->Html->scriptStart(['block' => true]) ?>

    $("#vista-id").prepend("<option value=''>Seleccione</option>").val('');
    
    function agregarChechboxes(){
        $(".checkbox-posicion").empty(); 
        var checkboxes = {'HOME': '5', 'SECCION': '1', 'RADIOS': '2', 'TV': '2', 'NOTA': '2', 'REVISTAS': '2'};                           
        var vista_seleccionada = $("#vista-id option:selected").text();
        var cantidad = checkboxes[vista_seleccionada];        
        $(".checkbox-posicion").append('<fieldset data-role="controlgroup" data-type="horizontal">');
        for(i = 0; i < cantidad; i++) { 
            var value = (i + 1);    
            var value_string = value.toString();
            var name = "=checkbox-rad" + value_string;
            var label = "0" + (i+1);
            $(".checkbox-posicion").append('<label class="checkbox-inline"><input class="icheck" type="checkbox" name'+name+' value="">'+value+'</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            <!--$(".checkbox-posicion").append('<label class="checkbox-inline"><input class="icheck" type="checkbox" name'+name+'>'+label+'</label>');-->
        }
        $(".checkbox-posicion").append('</fieldset>');
    };
    
    function agregarItem(){
        var vista_seleccionada = $("#vista-id option:selected" ).text();
        var orden_seleccionado = ordenSeleccionado();
        var elemento_borrar = '<?= $this->Html->link('<i class="fa fa-trash-o" title="Borrar"></i>', ['action' => '#'], ['data-toggle'=>'modal', 'data-target'=>'#basicModal','escape'=>false, 'onClick' => 'borrarParent(this);']) ?>';
        $(".vista-posicion-tabla tbody").append('<tr><td>'+vista_seleccionada+'</td><td>'+orden_seleccionado.join(',')+'</td><td>'+elemento_borrar+'</td></tr>');
    };
    
    function crearDivError() {        
        $("#error-log").append('<div class="col-md-12"> \
            <div class="alert alert-danger fade in" role="alert">  \
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">  \
                    <span aria-hidden="true">&times;</span>  \
                </button>  \
            </div>   \
        </div>');
    }
    
    function logArrayElements(element, index, array) {
        $("#error-log .alert-danger").append('<label>'+element+'</label></br>');
    }

    jQuery(document).ajaxStart(function () {
        jQuery("body").addClass("loading");
    }).ajaxStop(function () {
        jQuery("body").removeClass("loading");
    });
    
    jQuery('.agregar').click(function(){
        $('.alert-danger').alert('close');
        var vista_seleccionada = $("#vista-id option:selected" ).attr('value');
        var orden_seleccionado = ordenSeleccionado();
        request = {id: <?= $banner->id ?>, vista : vista_seleccionada, posiciones : orden_seleccionado.join(','), banner_tipo_id: <?= $banner->banner_tipos_id ?> };
        
        if(orden_seleccionado.length > 0 ){            
            jQuery.ajax({
                method: "post",
                dataType: "json",
                url: "/banners/actualizar_vista_posicion",
                data: request,
                success: function(response) {
                    if(typeof response.status.error != 'undefined'){
                        crearDivError();
                        response.status.error.forEach(logArrayElements);
                    }
                    else if(typeof response.status.success != 'undefined'){
                        location.reload();
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    });
    
    function ordenSeleccionado(){
        var selected = [];
        $('.checkbox-posicion input:checked').each(function() {
            selected.push($(this).attr('name').replace("checkbox-rad", ""));
        });
        return selected;
    }
    
    function borrarParent(objeto){
        var orden_seleccionado = $(objeto).parent().parent().find('td.posicion').text();
        var vista_seleccionada = $(objeto).parent().parent().find('td.vista').text();
        request = {id: <?= $banner->id ?>, vista : vista_seleccionada, posiciones : orden_seleccionado/*.join(',')*/ };
        
        jQuery.ajax({
            method: "post",
            dataType: "json",
            url: "/banners/borrar_vista_posicion",
            data: request,
            success: function(response) {
                if(typeof response.status.error != 'undefined'){
                }
                else if(typeof response.status.success != 'undefined'){
                    $(objeto).parent().parent().fadeTo(400, 0, function () { 
                        $(objeto).parent().parent().remove().fadeTo();
                    });
                }
            },
            error: function(e) {
                //console.log(e);
            }
        });
        return false;
    };
    
    function archivo(evt) {
        var files = evt.target.files; // FileList object

        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                  return function(e) {
                      // Insertamos la imagen
                      $("#imagen-banner").remove();                      
                      $("#imagen-banner").html('<object><param name="banner" value="'+e.target.result+'"><embed src="'+e.target.result+'" height="100%" width="100%"></embed></object>');                      

                  };
            })(f);

            reader.readAsDataURL(f);
        }
    }
    document.getElementById('filename').addEventListener('change', archivo, false);
<?= $this->Html->scriptEnd() ?>