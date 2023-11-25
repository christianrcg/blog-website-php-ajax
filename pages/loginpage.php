<?php
require_once('../functions/databaseConnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link rel="stylesheet" href="../css/customStyle.css">

    <!-- For browsers -->
    <link rel="icon" type="image/png" href="../images/webico/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../images/webico/favicon-16x16.png" sizes="16x16">
    <!-- For iOS -->
    <link rel="apple-touch-icon" href="../images/webico/apple-touch-icon.png">

    <!-- Additional CSS-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="spacer bg2">
    <section class="hidden">
        <div class="contentbox cnt">
            <div class="mb-3">
                <i class="fa-solid fa-circle-arrow-left fa-2xl mb-4 back-icon"></i>
                <h1>Login</h1>
            </div>

            <form id="loginUserForm">
                <div class="mb-3">
                    <label for="usernameInput" class="form-label">Username: </label>
                    <input type="text" class="form-control" id="usernameInput" name="username">
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password: </label>
                    <input type="password" class="form-control" id="passwordInput" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="showPass">
                    <label class="form-check-label" for="showPass">Show Password</label>
                </div>
                <button type="submit" class="btn btn-success">Login</button>
            </form>
        </div>



    </section>

    <script defer src="../scripts/bootstrap.bundle.min.js"></script>
    <script defer src="../scripts/popper.min.js"></script>
    <script src="../scripts/jquery.min.js"></script>
    <script defer src="../scripts/app.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        $(document).on('submit', '#loginUserForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("authenticate_user", true);

            $.ajax({
                type: 'POST',
                url: '../functions/authenticateLogin.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = jQuery.parseJSON(response);
                    alertify.set('notifier', 'position', 'top-center');

                    if (res.status == 200) {
                        let alertModal = alertify.alert()
                            .setting({
                                'label': 'OK',
                                'message': res.message + ' Go to Homepage?',
                                'onok': function() {
                                    alertify.success(res.message + ' Redirecting to Homepage');
                                    setTimeout(function() {
                                        window.location.href = 'homepage.php';
                                    }, 1500);
                                }
                            });
                        alertModal.setHeader('<p><i class="fa-regular fa-circle-check" style="color: #009439;"></i> &nbsp Login Successful</p>');
                        alertModal.show();

                    } else if (res.status == 422 || res.status == 500) {
                        let errorNotif = alertify.error(res.message);
                        $('#usernameInput').addClass('is-invalid');
                        $('#passwordInput').addClass('is-invalid');
                        $('body').one('click', function() {
                            errorNotif.dismiss();
                            $('#usernameInput').removeClass('is-invalid');
                            $('#passwordInput').removeClass('is-invalid');
                        });

                    } else if (res.status == 401) {
                        let errorNotif = alertify.error(res.message);
                        $('#passwordInput').addClass('is-invalid');
                        $('body').one('click', function() {
                            errorNotif.dismiss();
                            $('#passwordInput').removeClass('is-invalid');
                        });

                    } else if (res.status == 404) {
                        let errorNotif = alertify.error(res.message);
                        $('#usernameInput').addClass('is-invalid');
                        $('body').one('click', function() {
                            errorNotif.dismiss();
                            $('#usernameInput').removeClass('is-invalid');
                        });
                    }
                }
            });
        });
    </script>

</body>

</html>