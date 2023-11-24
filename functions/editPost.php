<?php
require_once('databaseConnection.php');
date_default_timezone_set('Asia/Manila');

if (isset($_GET['post_id'])) {
    $post_id = mysqli_real_escape_string($conn, $_GET['post_id']);

    $getPostQuery = "SELECT 
    post_id, 
    details,  
    privacy 
    FROM post 
    WHERE post_id='$post_id'";
    $query_run = mysqli_query($conn, $getPostQuery);

    if (mysqli_num_rows($query_run) == 1) {
        $post = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'Post Fetch Successfully by id',
            'data' => $post
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Post Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}
