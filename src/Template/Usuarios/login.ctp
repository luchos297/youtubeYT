<!--<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->input('email') ?>
<?= $this->Form->input('password') ?>
<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>-->

<div class="row">
    <div class="col-md-3" id="login-wrapper">
        <div class="panel panel-primary animated flipInY">
            <div class="panel-heading">
                <h3 class="panel-title">     
                   Acceso
                </h3>      
            </div>
            <div class="panel-body">
               <p> Ingese sus datos</p>
                <?= $this->Form->create(null,['class' => 'form-horizontal']) ?>
                    <div class="form-group">
                        <div class="col-md-12">
                            <?= $this->Form->input('email',['class'=>'form-control', 'placeholder'=>'Email', 'label'=>false]) ?>
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="col-md-12">
                           <?= $this->Form->input('password',['class'=>'form-control', 'placeholder'=>'Contraseña', 'label'=>false]) ?>
                            <i class="fa fa-lock"></i>
                            <!--<a href="javascript:void(0)" class="help-block">Forgot Your Password?</a>-->
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="col-md-12">
                            <?= $this->Form->button('Ingresar',['class'=>'btn btn-primary btn-block']) ?>
                            <!--<hr />-->
                            <!--<a href="pages-sign-up.html" class="btn btn-default btn-block">Not a member? Sign Up</a>-->
                        </div>
                    </div>
                <?= $this->Form->end() ?>
                <!--</form>-->
            </div>
        </div>
    </div>
</div>