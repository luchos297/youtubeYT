<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Usuarios'), ['controller'=>'usuarios', 'action' => 'index']) ?>
            </li>
            <li class="active">Ver Usuario</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Usuario</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!--<div class="panel-heading">
                <h3 class="panel-title"><?= h($usuario->nombre) ?></h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>-->
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <h4><?= __('Email') ?></h4>
                            </td>
                            <td class=""><h4><?= h($usuario->email) ?></h4></td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Id') ?></h4>
                            </td>
                            <td class=""><h4><?= h($usuario->id) ?></h4></td>
                        </tr>   
                        <tr>
                            <td>
                                <h4><?= __('Creado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                        $usuario->creado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                    );?>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4><?= __('Modificado') ?></h4>
                            </td>
                            <td class="">
                                <h4>
                                    <?= $this->Time->format(
                                    $usuario->creado,
                                        'dd-MM-Y HH:mm',
                                        null,
                                        null
                                    );?>
                                </h4>
                            </td>
                        </tr>                        
                         
                    </tbody>
                </table>          
                
            </div>
        </div>
    </div>
</div>
