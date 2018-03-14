<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;


if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?= Debugger::dump($error->params) ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
else:
    $this->layout = 'error';
?>
    <body class="animated fadeIn">
        <section id="error-container">
            <div class="block-error">
                <header>
                    <h1 class="error"><?= $code ?></h1>
                    <p class="text-center"><?= $message ?> Internal server error</p>
                </header>
                <p class="text-center">Ha ocurrido un error interno, estamos trabajando para solucionarlo.</p>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-info btn-block btn-3d" onclick="history.back();">Página previa</button>
                    </div>
                </div>
            </div>
        </section>
    </body>
<?php
endif;
?>
<!--<h2><?= __d('cake', 'An Internal Error Has Occurred') ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= h($message) ?>
</p>-->


