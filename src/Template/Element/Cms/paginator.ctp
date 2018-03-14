<div class="paginator">
    <div class="col-xs-6" style="padding:0;">
        <div class="dataTables_info"><?= $this->Paginator->counter('Página {{page}} de {{pages}} de {{count}} registros.' ) ?></div>
    </div>
    <div class="col-xs-6" style="padding:0;">
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination">
                <?= $this->Paginator->prev('<i class="fa fa-arrow-left"></i>',['escape' => false]) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('<i class="fa fa-arrow-right"></i>',['escape' => false]) ?>
            </ul>
        </div>
    </div>
</div>