<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Rss'), ['controller'=>'rsses', 'action' => 'index']) ?>
            </li>
            <li class="active">Nuevo Rss</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Rss</h1>
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
                <?= $this->Form->create($rss,['class' => '']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';
                        
                        echo $this->Form->input('nombre',['class' => $class_input]);
                        echo $this->Form->input('categoria_id', ['label'=>'Categoría','options' => $categorias, 'class' => $class_input]);
                        echo $this->Form->input('portal_id', ['options' => $portales, 'class' => $class_input]);
                        echo $this->Form->input('url',['class' => $class_input]);
                        //echo $this->Form->input('habilitado');
                        echo $this->Form->input('habilitado', ['label' => ['class' => $class_label], 'class'=> 'icheck']);
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