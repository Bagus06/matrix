<div class="card mb-0">
    <div class="card-body p-1">
        <div class="row col-12 align-items-center d-flex justify-content-center" style="height: 100%">
            <div class="col-sm-12 col-md-3">
                <text class="text-muted"><i>Controll Bar <i class="fa-solid fa-bars-progress"></i></i></text>
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="d-flex justify-content-end">
                    <div class="p-0">
                        <a href="<?= @$internal['module_main_url']; ?>" title="Main Page" class="btn btn-link btn-recycle <?= ((empty(@$internal['module_main_url'])) ? 'disabled' : ''); ?>" <?= ((empty(@$internal['module_main_url'])) ? 'disabled' : ''); ?>><i class="fa-solid fa-gauge"></i></a>
                    </div>
                    <div class="p-0">
                        <a href="<?= @$internal['create_url']; ?>" title="<?= ((empty(@$internal['create_title'])) ? 'Create' : @$internal['create_title']); ?>" class="btn btn-link btn-create <?= ((empty(@$internal['create_url'])) ? 'disabled' : ''); ?>" data-modalid="<?= @$internal['create_modal']; ?>" data-formname="<?= @$internal['create_form']; ?>" data-formtype="<?= @$internal['create_formtype']; ?>" <?= ((empty(@$internal['create_url'])) ? 'disabled' : ''); ?>><i class="fas fa-circle-plus"></i></a>
                    </div>
                    <div class="p-0">
                        <a href="<?= @$internal['recycle_url']; ?>" title="RecycleBin" class="btn btn-link btn-recycle <?= ((empty(@$internal['recycle_url'])) ? 'disabled' : ''); ?>" <?= ((empty(@$internal['recycle_url'])) ? 'disabled' : ''); ?>><i class="fa-solid fa-recycle"></i></a>
                    </div>
                    <div class="vertical-line"></div>
                    <div class="p-0">
                        <a href="<?= @$internal['save_form_url']; ?>" title="Save" class="btn btn-link btn-save <?= ((empty(@$internal['save_form_url'])) ? 'disabled' : ''); ?>" data-formname="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" <?= ((empty(@$internal['save_form_url'])) ? 'disabled' : ''); ?>><i class="fa-solid fa-floppy-disk"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>