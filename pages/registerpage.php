<?php
require_once('../functions/databaseConnection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>

    <link rel="stylesheet" href="../css/customStyle.css">
    <!-- additional css-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <section class="hidden">
        <div class="contentbox">
            <div class="mb-3">
                <i class="fa-solid fa-circle-arrow-left fa-2xl mb-4 back-icon"></i>
                <h1>Register a new user</h1>
            </div>

            <form id="registerUserForm">
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
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </section>

    <!-- <script src="https://kit.fontawesome.com/0bdbee9sded.js" crossorigin="anonymous"></script> -->
    <script defer src="../scripts/bootstrap.bundle.min.js"></script>
    <script defer src="../scripts/popper.min.js"></script>
    <script src="../scripts/jquery.min.js"></script>
    <script defer src="../scripts/app.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        $(document).on('submit', '#registerUserForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("register_user", true);

            $.ajax({
                type: 'POST',
                url: '../functions/registerUser.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    let res = jQuery.parseJSON(response);
                    alertify.set('notifier', 'position', 'top-center');

                    if (res.status == 200) {
                        let alertModal = alertify.alert()
                            .setting({
                                'label': 'Redirect',
                                'message': res.message + ' Redirect to Login Page?',
                                'onok': function() {
                                    alertify.success(res.message + ' Redirecting to Login Page');
                                    setTimeout(function() {
                                        window.location.href = 'loginpage.php';
                                    }, 2000);
                                }
                            });

                        alertModal.setHeader('<p><i class="fa-regular fa-circle-check" style="color: #009439;"></i> &nbsp Success</p>');
                        alertModal.show();

                    } else if (res.status == 409 || res.status == 422 || res.status == 500) {
                        let alertModal = alertify.alert()
                            .setting({
                                'label': 'Retry',
                                'message': res.message,
                                'onok': function() {
                                    alertify.error(res.message + ': Reloading the page');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }
                            });

                        alertModal.setHeader('<p><i class="fa-regular fa-circle-xmark" style="color: #c83c3c;"></i> &nbsp Error</p>');
                        alertModal.show();
                    }
                }

            });
        });
    </script>
</body>

</html>