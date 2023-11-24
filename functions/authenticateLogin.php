<?php
require_once('databaseConnection.php');


if (isset($_POST['authenticate_user'])) {
    $loginUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $loginPassword = mysqli_real_escape_string($conn, $_POST['password']);

    if ($loginUsername == NULL || $loginPassword == null) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory.'
        ];
        echo json_encode($res);
        return;
    }
    //checks the loginUsername is at the database username
    $userAccountsQuery = mysqli_query($conn, "SELECT * FROM user WHERE username='$loginUsername'");

    /*
    this if statement will be true if there are existing user from the $userAccounts query
    which then we validated that the input username is indeed present in the database
    */
    if ($userAccountsQuery) {
        if (mysqli_num_rows($userAccountsQuery) > 0) {
            $user = mysqli_fetch_assoc($userAccountsQuery); // if the prev query is true, this variable only holds 'one' username
            $storedPassword = $user['password']; //assigns the user password to the storedPassword which contains hashed password

            //Verify stored password with password_verify() by PHP
            if (password_verify($loginPassword, $storedPassword)) {
                $_SESSION['logged_user_id'] = $user['user_id'];
                $res = [
                    'status' => 200,
                    'message' => 'Authenticated'
                ];
                echo json_encode($res);
                return;
            } else {
                $res = [
                    'status' => 401,
                    'message' => 'Incorrect Password'
                ];
                echo json_encode($res);
                return;
            }
        } else {
            $res = [
                'status' => 404,
                'message' => 'Incorrect Username'
            ];
            echo json_encode($res);
            return;
        }
    } else {
        // Display MySQL error if query fails
        $res = [
            'status' => 500,
            'message' => 'Query error: ' . mysqli_error($conn)
        ];
        echo json_encode($res);
        return;
    }
}
