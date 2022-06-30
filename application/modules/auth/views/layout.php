<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/plugins/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/plugins/notyf/notyf.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?php echo base_url(); ?>" class="h1"><b>Face</b>Absensi</a>
            </div>
            <div class="card-body login-card-body">
                <?php require_once $page . '.php'; ?>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/backend/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/notyf/notyf.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/adminlte.min.js') ?>"></script>
    <script>
    $("form").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirm: {
                required: true,
                equalTo: "#password"
            },
            terms: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            password_confirm: {
                required: "Please provide a confirm password",
                equalTo: "Your confirm password does not match with password"
            },
            terms: "Please accept our terms"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    </script>
    <?php echo $message; ?>
</body>
</html>