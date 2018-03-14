<div class="user-nav">
    <ul>
        <li class="dropdown messages">
        </li>
        <li class="profile-photo">
            <?= $this->Html->image('/img/template-cms/avatar.gif', ['alt' => '','class'=>'img-circle']); ?>
        </li>
        <li class="dropdown settings">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <?= $userlogged['email']  ?> <i class="fa fa-angle-down"></i>
        </a>
            <ul class="dropdown-menu animated fadeInDown">
                <li>
                    <a href="<?= $this->Url->build(['controller' =>'Usuarios','action' =>'cambiar_password']); ?>"><i class="fa fa-key"></i> Cambiar contraseña </a>
                    <a href="<?= $this->Url->build(['controller' =>'Usuarios','action' =>'logout']); ?>"><i class="fa fa-power-off"></i> Salir </a>
                </li>
            </ul>
        </li>

    </ul>
</div>