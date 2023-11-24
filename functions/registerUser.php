<?php
include('databaseConnection.php');

if (isset($_POST['register_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    //validates if the fields are empty or not when it was submitted.
    if ($username == NULL || $password == null) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory.'
        ];
        echo json_encode($res);
        return;
    }

    //Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //if the fields are not null, checks if the inputted username has a duplicate in existing table
    $checkUsernameQuery = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $sameUsernameExists = mysqli_fetch_assoc($checkUsernameQuery);

    if ($sameUsernameExists) {
        $res = [
            'status' => 409,
            'message' => 'Username already taken'
        ];
        echo json_encode($res);
        return;
    }

    //if the username is available -> proceeds to insert the username
    $registerUserQuery = mysqli_query($conn, "INSERT INTO user (username,password) VALUES ('$username','$hashed_password')");
    if ($registerUserQuery) {
        $res = [
            'status' => 200,
            'message' => 'User Created'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Internal Server Error'
        ];
        echo json_encode($res);
        return;
    }
}
