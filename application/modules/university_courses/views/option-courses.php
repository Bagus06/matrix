<?php if (!empty($utilitys['courses']['data']['data'])) : ?>
    <option value=""><span class="text-muted text-italic">Select an Option</span></option>
    <?php foreach ($utilitys['courses']['data']['data'] as $key => $value) : ?>
        <?php
        $selected = '';
        if ($course_id == $value['id']) {
            $selected = 'selected';
        }
        ?>

        <option value="<?= $value['id'] ?>" <?= $selected; ?>><?= $value['course_name'] . ' ( ' . $value['course_code'] . ' )' ?></option>
    <?php endforeach; ?>
<?php else : ?>
    <option value=""><span class="text-muted text-italic">Select an Option</span></option>
<?php endif; ?>