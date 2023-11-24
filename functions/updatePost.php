<?php
require_once('databaseConnection.php');

date_default_timezone_set('Asia/Manila');
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

if (isset($_POST['update_post'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id_edit']);
    $details = mysqli_real_escape_string($conn, $_POST['postDetails']);

    if (array_key_exists('privacyCheckbox', $_POST) && $_POST['privacyCheckbox'] !== '') {
        $privacy = mysqli_real_escape_string($conn, $_POST['privacyCheckbox']);
    } else {
        $privacy = "public";
    }

    $updatePostquery = "UPDATE post 
    SET details='$details', 
    date_edited='$currentDate', 
    time_edited='$currentTime',
    privacy='$privacy' 
    WHERE post_id='$post_id'";

    $query_run = mysqli_query($conn, $updatePostquery);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Post Updated Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Post Update Failed'
        ];
        echo json_encode($res);
        return;
    }
}
