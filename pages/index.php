<?php
require_once('../functions/databaseConnection.php');

function getLoggedUsername($conn)
{
    if (isset($_SESSION['logged_user_id'])) {
        $userId = $_SESSION['logged_user_id'];

        // Query the database for the username using the user ID
        $getUserQuery = mysqli_query($conn, "SELECT username FROM user WHERE user_id='$userId'");

        // Check if the query returned a result
        if ($getUserQuery && mysqli_num_rows($getUserQuery) > 0) {
            $user = mysqli_fetch_assoc($getUserQuery);
            return $user['username'];
        }
    }

    // Return null or any default value if the user is not found or not logged in
    return null;
}

$loggedInUser = getLoggedUsername($conn);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTIVITY 2</title>
    <!-- CSS links-->
    <link rel="stylesheet" type="text/css" href="../css/customStyle.css">
    <!-- Alertify -->

</head>

<body>
    <section class="hidden">
        <h1>Welcome!</h1>
        <p>This is a small website based on the web technologies: PHP, MySQl, Ajax and Bootstrap 5</p>

        <div class="hidden d-flex flex-column justify-content-center m-3">
            <h6>Posts made public of the users: </h6>
            <a href="#postSection" class="btn btn-light"> View Posts</a>

            <!-- Start of the block of the code that will only be shown if there is a logged in user in the session ($_SESSION['user'])-->
            <?php
            if ($loggedInUser !== null) {
            ?>
                <p style="margin: 3rem auto 1rem; font-size: x-large;"> Hello <b><?php echo $loggedInUser; ?>! </b></p>
                <a href="homepage.php" class="btn btn-outline-light btn-sm mb-3"> Homepage </a>
                <a href="../functions/logout.php" class="btn btn-outline-danger btn-sm align-self-center"> Logout </a>
        </div>
    <?php
            } else {
    ?>
        <h6 style="margin-top: 3rem;" class="align-self-center"> Be one of us:</h6>
        <a href="#loginOrRegisterSection" class="btn btn-outline-primary"> Click Here</a>
        </div>
    <?php
            }
    ?>
    <!-- end of the block of code-->
    </section>

    <section class="hidden" id="postSection">
        <h2> Posts Made by the Users</h2>
        <p> random public posts go brrrrrrrrrrrrrrt</p>

        <div class="d-flex justify-content-center m-2" style="width: 80vw;">
            <?php
            $publicPostsQuery = "SELECT 
                p.post_id, 
                p.details, 
                p.date_posted, 
                p.time_posted, 
                COALESCE(p.date_edited, 'N/A') AS date_edited, 
                COALESCE(p.time_edited, 'N/A') AS time_edited, 
                p.postedby, 
                u.username
            FROM 
                post p
            LEFT JOIN 
                user u ON p.postedby = u.user_id
            WHERE 
                p.privacy = 'public';
            ";

            $result = mysqli_query($conn, $publicPostsQuery);

            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row['username'];
                $datePosted = date("F j, Y", strtotime($row['date_posted']));

                //formats to 12 -hour clock
                $timePosted = ($row['time_posted'] !== 'N/A') ? date("g:i a", strtotime($row['time_posted'])) : 'N/A';
                $dateEdited = $row['date_edited'];
                $timeEdited = $row['time_edited'];
                $details = $row['details'];


                // if both $dateEdited and $timeEdited is 'N/A' which is null, assign a text' - edited' otherwise leave blank string
                $editedPost = ($dateEdited != 'N/A' && $timeEdited != 'N/A') ? ' - edited' : '';
            ?>
                <!-- div inside a while loop when fetching user data -->
                <div class="card m-2 hidden">
                    <div class="card-body">
                        <h5 class="card-title">Posted by <b> <?php echo $username; ?></b></h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary"> <?php echo $datePosted . ' at ' . $timePosted . $editedPost; ?> </h6>
                        <p class="card-text"><?php echo $details; ?></p>
                    </div>
                </div>
                <!-- end of the div-->
            <?php
            }
            ?>
        </div>

    </section>

    <!-- This section will also be hidden if there is a logged in user-->
    <?php
    if ($loggedInUser !== null) {
    } else {
    ?>
        <section class="hidden" id="loginOrRegisterSection">
            <h2> Want to share some post?</h2>
            <div class="hidden d-flex flex-column justify-content-center m-3">
                <a class="btn btn-light" href="loginpage.php"> Login </a>
                <p class="my-3"> or don't have an account yet? </p>
                <a class="btn btn-outline-light" href="registerpage.php"> Register</a>
            </div>
        </section>
    <?php
    }
    ?>
    <!-- end of the login or register section-->
    <section class="hidden">
        <h1>Built by these Web Technologies</h1>
        <div class="logos">
            <div class="logo hidden">
                <img src="../images/icons/html5.jpg">
            </div>
            <div class="logo hidden">
                <img src="../images/icons/css3.jpg">
            </div>
            <div class="logo hidden">
                <img src="../images/icons/javascript.jpg">
            </div>
            <div class="logo hidden">
                <img src="../images/icons/jquery.jpg">
            </div>
            <div class="logo hidden">
                <img src="../images/icons/phplogo.png">
            </div>
            <div class="logo hidden">
                <img src="../images/icons/mysql.jpg">
            </div>
        </div>
    </section>

    <!-- JS Scripts  -->
    <script defer src="../scripts/bootstrap.bundle.min.js"></script>
    <script defer src="../scripts/popper.min.js"></script>
    <script defer src="../scripts/app.js"></script>
</body>

</html>