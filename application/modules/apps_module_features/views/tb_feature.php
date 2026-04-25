<?php for ($i = 0; $i < @$utilitys['loop']; $i++) : ?>
    <tr data-numrow="<?= @$utilitys['numrow']; ?>" id="row-<?= @$utilitys['numrow']; ?>">
        <td class="text-center">
            <?= @$utilitys['num']; ?>
            <input type="hidden" name="ft_delete[]" value="">
            <input type="hidden" name="ft_id[]" value="<?= encryptcst(@$utilitys['data'][$utilitys['numrow']]['id']); ?>">
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm bg-transparent" minlength="3" maxlength="3" style="text-transform: uppercase;" name="ft_feature_code[]" title="<?= @$utilitys['data'][$utilitys['numrow']]['feature_full_code'] ?>" value="<?= @$utilitys['data'][$utilitys['numrow']]['feature_code'] ?>" required="true">
                <small class="text-danger pl-3" id="err-ft_feature_code[]" style="display: none;"></small>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm bg-transparent" minlength="3" maxlength="50" name="ft_feature_title[]" title="<?= @$utilitys['data'][$utilitys['numrow']]['feature_title'] ?>" value="<?= @$utilitys['data'][$utilitys['numrow']]['feature_title'] ?>" required="true">
                <small class="text-danger pl-3" id="err-ft_feature_title[]" style="display: none;"></small>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm bg-transparent" name="ft_description[]" title="<?= @$utilitys['data'][$utilitys['numrow']]['description'] ?>" value="<?= @$utilitys['data'][$utilitys['numrow']]['description'] ?>">
                <small class="text-danger pl-3" id="err-ft_description[]" style="display: none;"></small>
            </div>
        </td>
        <td class="text-center">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input custom-control-input-default" name="ft_status[<?= @$utilitys['numrow']; ?>]" id="ft_status<?= @$utilitys['numrow']; ?>" <?= ((empty(@$utilitys['data'][$utilitys['numrow']]['status'])) ? 'checked' : ((@$utilitys['data'][$utilitys['numrow']]['status'] === 'ACTIVE') ? 'checked' : '')) ?>>
                    <label for="ft_status<?= @$utilitys['numrow']; ?>" class="custom-control-label"> Active</label>
                </div>
                <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
            </div>
        </td>
        <td class="text-center">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input custom-control-input-default" name="ft_sys_lock[<?= @$utilitys['numrow']; ?>]" id="ft_sys_lock<?= @$utilitys['numrow']; ?>" <?= ((@$utilitys['data'][$utilitys['numrow']]['sys_lock']) ? 'checked' : '') ?>>
                    <label for="ft_sys_lock<?= @$utilitys['numrow']; ?>" class="custom-control-label"><i class="fa-solid fa-lock text-primary"></i></label>
                </div>
                <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
            </div>
        </td>
        <td class="text-center action-column">
            <button type="button" class="btn btn-link btnDeleteFeature" title="Delete"><i class="fa-solid fa-trash"></i></button>
        </td>
    </tr>

    <?php
    $utilitys['numrow']++;
    $utilitys['num']++;
    ?>
<?php endfor; ?>