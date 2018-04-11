<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Canciones'), ['controller' => 'canciones', 'action' => 'index']) ?>
            </li>
            <li class="active">Editar Canción</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Canción</h1>
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
                <?= $this->Form->create($cancion, ['type' => 'file', 'class' => '', 'id' => 'bootstrapTagsInputForm', 'onkeypress' => 'return event.keyCode != 13;']) ?>
                    <?php $myTemplates = [
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
                        echo $this->Form->input('url', ['label' => 'Youtube', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('title', ['label' => 'Title', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('artist', ['label' => 'Artist', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('album', ['label' => 'Album', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('genre', ['label' => 'Genre', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('filesize', ['label' => 'Filesize', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('duration', ['label' => 'Duration', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('sample_rate', ['label' => 'Sample Rate', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('bitrate', ['label' => 'Bitrate', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('dataformat', ['label' => 'Format', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('quality', ['label' => 'Quality', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('url_yt_download', ['label' => 'Download URL', ['class' => $class_label], 'class'=> $class_input]);                        
                        echo $this->Form->input('filename', ['label' => 'Filename', ['class' => $class_label], 'class'=> $class_input]);
                        echo $this->Form->input('downloaded', ['label' => 'Downloaded', ['class' => $class_label], 'class'=> 'icheck']);                        
                        echo $this->Form->input('fecha_publish', ['type' => 'datetime', 'interval' => 2, 'class'=> $class_input, 'monthNames' => false, 
                            'day' => ['class' => $class_input,], 
                            'year' => [ 'class' => $class_input,],
                            'month' => ['class' => $class_input,],
                            'hour' => ['class' => $class_input,],
                            'minute' => ['class' => $class_input,]
                        ]);
                        echo $this->Form->input('creado', ['type' => 'datetime', 'interval' => 2, 'class'=> $class_input, 'monthNames' => false, 
                            'day' => ['class' => $class_input,], 
                            'year' => [ 'class' => $class_input,],
                            'month' => ['class' => $class_input,],
                            'hour' => ['class' => $class_input,],
                            'minute' => ['class' => $class_input,]
                        ]);
                        echo $this->Form->input('modificado', ['type' => 'datetime', 'interval' => 2, 'class'=> $class_input, 'monthNames' => false, 
                            'day' => ['class' => $class_input,], 
                            'year' => [ 'class' => $class_input,],
                            'month' => ['class' => $class_input,],
                            'hour' => ['class' => $class_input,],
                            'minute' => ['class' => $class_input,]
                        ]);
                    ?>
                    
                    <div class="form-group"><label>Cover</label>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <?php if($cancion->image_path){
                                    echo $this->Html->image(Cake\Core\Configure::read('path_imagen_covers') . $cancion->video_id . '.jpg');
                                } ?>
                            </div>
                        </div>
                    </div>                           
                <output id="list"></output>
                <div class="form-group">
                    <?= $this->Form->button(__('Guardar'),['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type' => 'button']) ?>
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
        $('#imagen-cancion').empty();
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
                    $("#imagen-cancion").append('<img src="'+e.target.result+'" class="form-group"/>');
                    
                };
          })(f);

          reader.readAsDataURL(f);
        }        
    }
    
    document.getElementById('filename').addEventListener('change', archivo, false);
</script>