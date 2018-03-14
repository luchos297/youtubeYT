<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Usuarios'), ['controller'=>'usuarios', 'action' => 'index']) ?>
            </li>
            <li class="active">Cambiar Contraseña</li>
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
                <div class="users form large-9 medium-9 columns">
                <?= $this->Form->create($usuario) ?>
                    <?php
                        $myTemplates = [
                            'inputContainer' => '<div class="form-group">{{content}}</div>'
                            ];
                        $this->Form->templates($myTemplates);
                        $class_input = 'form-control';
                        $class_label = ''; 
                    ?>
                    <?= $this->Form->input('old_password',['class'=> $class_input,'autocomplete' => 'off','type' => 'password' , 'label'=>'Contraseña anterior'])?>
                    <?= $this->Form->input('password1',['class'=> $class_input,'autocomplete' => 'off','type'=>'password' ,'label'=>'Contraseña']) ?>
                    <?= $this->Form->input('password2',['class'=> $class_input,'autocomplete' => 'off','type' => 'password' , 'label'=>'Repetir contraseña'])?>
                    <div class="form-group">
                        <?= $this->Form->button(__('Guardar'),['class' => 'btn btn-primary']) ?>
                        <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default', 'type'=>'button']) ?>
                    </div>
                <?= $this->Form->end() ?>                    
                
            </div>
        </div>
    </div>
</div>