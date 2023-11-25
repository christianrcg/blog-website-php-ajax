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

    <!-- For browsers -->
    <link rel="icon" type="image/png" href="../images/webico/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../images/webico/favicon-16x16.png" sizes="16x16">

    <!-- For iOS -->
    <link rel="apple-touch-icon" href="../images/webico/apple-touch-icon.png">

    <!-- CSS links-->
    <link rel="stylesheet" type="text/css" href="../css/customStyle.css">

</head>

<body>
    <section class="hidden minh2 divider grd1">
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
                <a href="../functions/logout.php" class="btn btn-danger btn-sm align-self-center"> Logout </a>
        </div>
    <?php
            } else {
    ?>
        <h6 style="margin-top: 3rem;" class="align-self-center"> Be one of us:</h6>
        <a href="#loginOrRegisterSection" class="btn btn-primary btn-sm"> Click Here</a>
        </div>
    <?php
            }
    ?>
    <!-- end of the block of code-->

    <div class="wave-btm">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
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

        <svg id="visual" class="blobpos" viewBox="0 0 960 300" width="960" height="300" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
            <g transform="translate(497.5680766911354 149.16270089611925)">
                <path id="blob1" d="M78.7 -94.3C103.8 -72.7 127.2 -49.6 130.8 -23.9C134.3 1.8 117.9 30.2 102.3 60.8C86.7 91.4 71.9 124.2 46.3 138.3C20.6 152.5 -15.8 148.2 -48.7 135.5C-81.7 122.8 -111 101.9 -133 72.5C-155.1 43.1 -169.9 5.3 -165.7 -31.1C-161.5 -67.4 -138.3 -102.3 -107.5 -122.8C-76.7 -143.4 -38.4 -149.7 -5.8 -142.8C26.8 -135.9 53.6 -115.8 78.7 -94.3" fill="#5213b1"></path>
            </g>
            <g transform="translate(467.8879178275882 142.96134632061438)" style="visibility: hidden;">
                <path id="blob2" d="M102.9 -117.3C129.5 -100.3 144.5 -64.2 142.3 -31.7C140.2 0.8 120.8 29.7 102.5 57.1C84.2 84.5 66.9 110.4 39.9 128.2C13 146 -23.6 155.7 -49.3 143C-75.1 130.4 -89.9 95.5 -99.8 64.4C-109.6 33.3 -114.4 6.2 -117 -27.9C-119.6 -61.9 -120.1 -102.8 -100.2 -120.9C-80.3 -139.1 -40.2 -134.5 -1 -133.3C38.1 -132.1 76.3 -134.2 102.9 -117.3" fill="#5213b1"></path>
            </g>
        </svg>

    </section>
    <div class="spacer2 wave1"></div>

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
    <section class="hidden minh">
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
    <div class="divider peak1 "></div>
    <!-- JS Scripts -->
    <script defer src="../scripts/bootstrap.bundle.min.js"></script>
    <script defer src="../scripts/popper.min.js"></script>
    <script defer src="../scripts/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/kute.js@2.1.2/dist/kute.min.js"></script>

    <script>
        const blobAnimate = KUTE.fromTo(
            '#blob1', {
                path: '#blob1'
            }, {
                path: '#blob2'
            }, {
                repeat: 999,
                duration: 2000,
                yoyo: true
            }
        )
        blobAnimate.start()
    </script>
</body>

</html>