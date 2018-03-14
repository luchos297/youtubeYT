<div class="row">
    <div class="col-md-12">
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Configuraciones'), ['controller'=>'categorias', 'action' => 'ordenar_categorias']) ?>
            </li>
            <li class="active">Ordenar Categorías</li>
        </ul>
        <!--breadcrumbs end -->
        <h1 class="h1">Categorías</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Orden de menú</h3>
                <div class="actions pull-right">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="panel-body">
                <div class="dd" id="nestable">
                    <ol class="dd-list menu-list">
                        <?php foreach($categorias as $categoria): ?>
                            <li class="dd-item" data-id="<?= $categoria->id ?>" data-parent="<?= $categoria->categoria_id ?>">
                                <div class="dd-handle"><?= $categoria->nombre ?></div>
                                <?php if(!empty($categoria->childs)): ?>
                                    <ol class="dd-list">
                                        <?php foreach($categoria->childs as $child): ?>
                                        <li class="dd-item" data-id="<?= $child->id ?>" data-parent="<?= $child->categoria_id ?>">
                                            <div class="dd-handle"><?= $child->nombre ?></div>
                                        </li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>                         
                    </ol>
                </div>
                <br>
                <div class="form-group">
                    <?= $this->Form->button(__('Actualizar'),['class' => 'btn btn-primary send-update-ajax']) ?>
                    <?= $this->Form->button(__('Volver'),['class' => 'btn btn-default volver', 'type'=>'button']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->scriptStart(['block' => true]) ?>

    var updateOutput = function(e) {
        
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            //output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        } else {
            console.log('JSON browser support required for this demo.');
        }
        return window.JSON.stringify(list.nestable('serialize'));
    };

    var updateParent = function(){
        jQuery.each(jQuery('.menu-list > li'), function(index, value){
            jQuery(value).attr('data-parent','');
            jQuery.each(jQuery(value).find('ol > li'), function(index2, value2){
                jQuery(value2).attr('data-parent',jQuery(value).attr('data-id'));
            });
        });
    };
    
    jQuery('#nestable').nestable({
        maxDepth : 2,
    }).on('change', function(){
        updateParent();
        updateOutput(jQuery('#nestable').data('output', jQuery('#nestable-output')));
    }).on('dragStop', function(){
        //console.log('algo');
    });
    
    // output initial serialised data
    updateOutput(jQuery('#nestable').data('output', jQuery('#nestable-output')));
    
    jQuery('.send-update-ajax').click(function(){
        data = updateOutput(jQuery('#nestable').data('output', jQuery('#nestable-output')));
        //console.log(data);
        jQuery.ajax({
            method: "POST",
            dataType: "json",
            url: "ordenar_categorias",
            data: {'menu': data}
        })
        .done(function( msg ) {
            alert( "Data Saved: " + msg );
        });
    });
    
    jQuery(document).ajaxStart(function () {
        jQuery("body").addClass("loading");
    }).ajaxStop(function () {
        jQuery("body").removeClass("loading");
    });
    
<?= $this->Html->scriptEnd() ?>