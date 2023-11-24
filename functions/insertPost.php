<?php
require_once('databaseConnection.php');

date_default_timezone_set('Asia/Manila');

$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

if (isset($_POST['insert_post'])) {
    $postDetails = mysqli_real_escape_string($conn, $_POST['postDetails']);
    $postedByid = mysqli_real_escape_string($conn, $_POST['currentUserId']);
    $currentUserPosted = mysqli_real_escape_string($conn, $_POST['currentUsername']);

    if (array_key_exists('privacyCheckbox', $_POST) && $_POST['privacyCheckbox'] !== '') {
        $privacy = mysqli_real_escape_string($conn, $_POST['privacyCheckbox']);
    } else {
        $privacy = "public";
    }

    $insertQuery = "INSERT INTO post (details, date_posted, time_posted, privacy, postedby) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);
    if ($stmt) {
        $stmt->bind_param("ssssi", $postDetails, $currentDate, $currentTime, $privacy, $postedByid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $res = [
                'status' => 200,
                'message' => 'Post Created.'
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 400,
                'message' => 'Post Creation Failed.'
            ];
            echo json_encode($res);
            return;
        }
        $stmt->close();
    } else {
        $res = [
            'status' => 500,
            'message' => 'Server Error.'
        ];
        echo json_encode($res);
        return;
    }
}
