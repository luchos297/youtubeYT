<div class="paginator">
    <div class="col-xs-6" style="padding:0;">
        <button type="button" class="btn btn-success apply-filters"><i class="fa fa-filter"></i></button>
        <button type="button" class="btn btn-primary clear-filters"><i class="fa fa-times"></i></button>        
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