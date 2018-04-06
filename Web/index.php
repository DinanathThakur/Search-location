<?php include_once dirname(__FILE__) . '/layout/header.php'; ?>
    <link href="<?php echo BASE_URL ?>/assets/home-style.css" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-login">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="javascript:;" class="active" id="login-form-link">Login</a>
                            </div>
                            <div class="col-xs-6">
                                <a href="javascript:;" id="register-form-link">Register</a>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="login-form" action="http://phpoll.com/login/process" method="post" role="form"
                                      style="display: block;">
                                    <div class="form-group">
                                        <input type="text" name="email" tabindex="1"
                                               class="form-control" placeholder="Dmail-ID" value="" required="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" tabindex="2"
                                               class="form-control" placeholder="Password" required="">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="login-submit" id="login-submit" tabindex="4"
                                                       class="form-control btn btn-login" value="Log In">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form id="register-form" action="/" method="post"
                                      role="form" style="display: none;">
                                    <div class="form-group">
                                        <input type="text" name="firstName" tabindex="1"
                                               class="form-control" placeholder="FirstName" required="">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="lastName" tabindex="1"
                                               class="form-control" placeholder="LastName" required="">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" tabindex="1" class="form-control"
                                               placeholder="Email Address" required="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" tabindex="2"
                                               class="form-control" placeholder="Password" required="">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="register-submit" id="register-submit"
                                                       tabindex="4" class="form-control btn btn-register"
                                                       value="Register Now">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {

            $('#login-form-link').click(function (e) {
                $("#login-form").delay(100).fadeIn(100);
                $("#register-form").fadeOut(100);
                $('#register-form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });
            $('#register-form-link').click(function (e) {
                $("#register-form").delay(100).fadeIn(100);
                $("#login-form").fadeOut(100);
                $('#login-form-link').removeClass('active');
                $(this).addClass('active');
                e.preventDefault();
            });

        });

        $(document).ready(function () {

            $(document.body).on('submit', '#login-form', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: API_BASE_URL + '?apiName=Login',
                    type: 'POST',
                    data: $(this).serializeArray(),
                    dataType: 'JSON',
                    encode: true,
                    beforeSend: function () {
                        blockUI({msg: "Logging in, please wait..."});
                    },
                    complete: function () {
                        unblockUI();
                    },
                    success: function (response, status, xhr) {
                        unblockUI();
                        if (response.result=='success') {
                            setCookie('jwt', xhr.getResponseHeader('jwt'), 1);
                            window.location.href = BASE_URL + 'locations.php';
                        } else {
                            toastr["error"](response.message);
                        }
                    },
                    error: function (result) {
                        var error = JSON.parse(result.responseText);
                        toastr["error"](error.message);
                    }
                });
            });

            $(document.body).on('submit', '#register-form', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: API_BASE_URL + '?apiName=Register',
                    type: 'POST',
                    data: $(this).serializeArray(),
                    dataType: 'JSON',
                    encode: true,
                    beforeSend: function () {
                        blockUI({msg: "Registering, please wait..."});
                    },
                    complete: function () {
                        unblockUI();
                    },
                    success: function (response, status, xhr) {
                        unblockUI();
                        if (response.result=='success') {
                            setCookie('jwt', xhr.getResponseHeader('jwt'), 1);
                            window.location.href = BASE_URL + 'locations.php';
                        } else {
                            toastr["error"](response.message);
                        }
                    },
                    error: function (result) {
                        var error = JSON.parse(result.responseText);
                        toastr["error"](error.message);
                    }
                });
            });

        });

    </script>

<?php include_once dirname(__FILE__) . '/layout/footer.php'; ?>