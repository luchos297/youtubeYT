<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Usuarios'), ['controller'=>'usuarios', 'action' => 'index']) ?>
            </li>
            <li class="active">Nuevo Usuario</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Usuario</h1>
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
                <?= $this->Form->create($usuario,['class' => '']) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = '';
                        echo $this->Form->input('email',['class'=> $class_input,'autocomplete' => 'off']);
                        echo $this->Form->input('password',['class'=> $class_input,'autocomplete' => 'off', 'label'=>'Contraseña']);
                        echo $this->Form->input('password2',['class'=> $class_input,'autocomplete' => 'off','type' => 'password' , 'label'=>'Repetir contraseña'])
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