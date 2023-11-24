<?php
require_once('databaseConnection.php');

date_default_timezone_set('Asia/Manila');

if (isset($_POST['delete_post'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    $deletePostQuery = "DELETE FROM post WHERE post_id='$post_id'";
    $query_run = mysqli_query($conn, $deletePostQuery);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Post Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Post Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
