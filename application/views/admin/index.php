<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= apps_conf('apps -favlogo') ?>">
    <title>
        <?= ((empty($page["title"])) ? apps_conf('default_page_title') : $page["title"]) ?>
    </title>

    <?php $this->load->view("admin/css") ?>
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    <script>
        window.AppData = Object.freeze({
            alert: <?= json_encode(@$alert ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
            toastr: <?= json_encode(@$toastr ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
        });
    </script>

    <?php
    $filename = './application/modules/' . $this->uri->rsegments[1] . '/views/modal-load.php';

    if (file_exists($filename)) {
        require($filename);
    }
    ?>

    <?php $this->load->view('loading-page') ?>

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <?php if (user_ag() === 'mobile') : ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
            <?php endif; ?>
            <ul class="navbar-nav mx-auto">
                <span class="text-center brand-title"><strong class="brand-title prefix">MODWAY</strong>ACADEMY</span>
            </ul>

            <?php $this->load->view("admin/navbar_menu") ?>
        </nav>

        <aside class="main-sidebar sidebar-light-primary">

            <?php $this->load->view("admin/sidebar_menu") ?>

        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <?php $this->load->view("admin/controllbar") ?>
                </div>
            </section>

            <section class="content pl-0 pr-0 ml-0 mr-0">
                <div class="container-fluid">
                    <?php $this->load->view($this->uri->rsegments[1] . '/' . $this->uri->rsegments[2]); ?>
                </div>
            </section>
        </div>

        <footer class="main-footer">

            <div class="float-right d-none d-sm-inline">
                <?= apps_conf('apps -v') ?>
            </div>

            <strong>Copyright &copy; 2025-<?= date('Y') ?> <a href="<?= apps_conf('company -blog') ?>"><?= apps_conf('company -title') ?></a>.</strong> All rights reserved.
        </footer>
        <aside class="control-sidebar control-sidebar-dark">

        </aside>

    </div>
    <?php $this->load->view("admin/js") ?>
</body>

</html>