<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= apps_conf('apps -favlogo') ?>">
    <title>
        <?= ((empty($page["title"])) ? apps_conf('default_page_title') : $page["title"]) ?>
    </title>

    <?php
    $filename = './application/modules/' . $this->uri->rsegments[1] . '/cssload.php';

    if (file_exists($filename)) {
        require($filename);
    }
    ?>
</head>

<body class="hold-transition login-page" style="background-image: url('<?= base_url() . 'assets/img/background/background1.jpg' ?>');">
    <script>
        window.AppData = Object.freeze({
            alert: <?= json_encode(@$alert ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
            toastr: <?= json_encode(@$toastr ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
        });
    </script>

    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url(); ?>">
                <img src="<?= apps_conf('apps -logo') ?>" alt="Apps Logo" class="brand-image">
            </a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username or Email" name="user" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa-solid fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <!-- <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div> -->
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                <!-- <p class="mb-1">
                    <a href="#">I forgot my password</a>
                </p> -->
            </div>
        </div>
    </div>


    <?php
    $filename = './application/modules/' . $this->uri->rsegments[1] . '/jsload.php';

    if (file_exists($filename)) {
        require($filename);
    }
    ?>
</body>

</html>