<div class="sidebar" style="padding-left: .10rem;">
    <a href="<?= base_url() ?>" class="brand-link">
        <img src="<?= apps_conf('apps -logo') ?>" alt="Apps Logo" class="brand-image">
        <span class="brand-text font-weight-light"></span>
    </a>
    <div class="user-panel mt-4 pb-3 mb-3 d-flex">
        <div class="image">
            <?php
            $profile = FCPATH . 'assets/img/profile/' .  @get_user()['photo'];
            if (is_file($profile)) {
                $profile =  @get_user()['photo'];
            } else {
                $profile = 'default_profile.png';
            }
            ?>
            <img src="<?= base_url() ?>assets/img/profile/<?= @$profile; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block"><span class="text-primary text-md font-weight-bold">Hai, <?= @get_user()['name']; ?></span></a>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column mb-2" data-widget="treeview" role="menu" data-accordion="false" id="sidebar-menu">
            <?php
            $external_url = base_url() . 'apps_menus/sidebar_menu?menu_open=' . bin2hex($this->uri->rsegments[1] . '/' . $this->uri->rsegments[2]) . '&user_id=' . encryptcst(get_user()['id']);
            echo file_get_contents($external_url);
            ?>
            <li class="nav-item">
                <a href="<?= base_url() . 'users/edit/' . encryptcst(get_user()['id']); ?>" class="nav-link <?= ((($this->uri->rsegments['1'] . '/' . $this->uri->rsegments['2'] == 'users/edit') && (decryptcst($this->uri->rsegments['3']) == get_user()['id'])) ? 'active' : '') ?>">
                    <i class="nav-icon fa-solid fa-circle-user"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() . 'logout'; ?>" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
</div>