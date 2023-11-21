<?php

if (isset($_SESSION['logged_user_id'])) { // Checks if the user is logged in

    require_once('databaseConnection.php');
    $userId = $_SESSION['logged_user_id'];

    // Retrieve the username from the database using the user ID
    $getUserQuery = mysqli_query($conn, "SELECT username FROM user WHERE user_id='$userId'");

    if (mysqli_num_rows($getUserQuery) > 0) {
        $user = mysqli_fetch_assoc($getUserQuery);
        $currentUser = $user['username'];
        // $username contains the username associated with the user ID in the session
    }
} else {
    header('location:../pages/index.php');
    exit();
}
