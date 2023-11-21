<?php
require_once('../functions/databaseConnection.php');
include_once('../functions/authCheck.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../css/customStyle.css">

    <!-- Additional CSS-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body id="bodyContainer">
    <section>
        <div class="contentbox" style="width: 80vw; padding: 2em;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <i class="fa-solid fa-circle-arrow-left fa-2xl b-4 back-icon"></i>
                <span class="d-flex flex-column align-items-center mb-0">
                    <h1 style="margin-bottom: 1%;">Welcome! <b><?php echo $currentUser; ?> </b></h1>
                    <p style="font-weight: 200;">Here are your posts.</p>
                </span>

                <a href="../functions/logout.php" class="btn btn-outline-light ml-auto" name="logoutBtn">Logout</a>
            </div>

            <button type="button" data-bs-toggle="modal" data-bs-target="#insertPostModal" class="btn btn-outline-success mb-2"> Add Post </button>

            <div id="tblContainer">
                <table class="table text-center table-bordered table-dark" id="userPostsTable">
                    <thead>
                        <tr class="table-dark">
                            <th scope="col"> Post Details</th>
                            <th scope="col"> Date Posted</th>
                            <th scope="col"> Date Edited</th>
                            <th scope="col"> Privacy</th>
                            <th scope="col"> Actions</th>
                        </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                        <?php
                        $postsQuery = "SELECT post_id, details, date_posted, time_posted, COALESCE(date_edited, 'N/A') AS date_edited, COALESCE(time_edited, 'N/A') AS time_edited, privacy FROM post WHERE postedby = '$userId'";
                        $result = mysqli_query($conn, $postsQuery);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $date_posted = date("F j, Y", strtotime($row['date_posted']));

                            if ($row['date_edited'] && $row['time_edited'] != 'N/A') {
                                $date_edited = date("F j, Y", strtotime($row['date_edited']));
                            } else {
                                $date_edited = $row['date_edited'];
                                $row['time_edited'] = '';
                            }
                        ?>
                            <tr class="text-center">
                                <td> <?php echo $row['details']; ?></td>
                                <td> <?php echo $date_posted . ' ' . $row['time_posted']; ?></td>
                                <td> <?php echo $date_edited . ' ' . $row['time_edited']; ?></td>
                                <td> <?php echo $row['privacy']; ?></td>
                                <td>
                                    <!-- pass post_id as hidden input-->
                                    <input type="hidden" name="post_id" id="$post_id_input" value="<?php echo $row['post_id']; ?>">
                                    <button class="editPostButton btn btn-outline-primary btn-sm" value="<?php echo $row['post_id']; ?>">Edit</button>
                                    <button class="deletePostButton btn btn-outline-danger btn-sm" value="<?php echo $row['post_id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- modals -->

            <!-- Create Post Modal-->
            <div class="modal fade" id="insertPostModal" tabindex="-1" aria-labelledby="insertPostModal" aria-hidden="true" data-bs-theme="dark">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Post On Website!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="createPostForm">
                                <div class="input-group">
                                    <span class="input-group-text">Post Details</span>
                                    <textarea class="form-control" aria-label="With textarea" name="postDetails" required></textarea>
                                </div>
                                <div class="form-check mt-3 mx-2">
                                    <label class="form-check-label" for="privacyCheckbox"> Private? </label>
                                    <input class="form-check-input" type="checkbox" name="privacyCheckbox" value="private" id="privacyCheckbox">
                                </div>
                                <input type="hidden" name="currentUserId" value="<?php echo $userId; ?>">
                                <input type="hidden" name="currentUsername" value="<?php echo $currentUser; ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-light btn-sm">Create Post</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Post Modal-->
            <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModal" aria-hidden="true" data-bs-theme="dark">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Edit this Post!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="updatePostForm">
                                <div class="input-group">
                                    <span class="input-group-text">Post Details</span>
                                    <textarea class="form-control" id="detailsInput" name="postDetails" required></textarea>
                                </div>
                                <div class="form-check mt-3 mx-2">
                                    <label class="form-check-label" for="privacyCheckbox"> Private? </label>
                                    <input class="form-check-input" type="checkbox" name="privacyCheckbox" value="private" id="editPrivacyCheckbox">
                                </div>
                                <input type="hidden" name="post_id_edit" id="post_id_edit" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-light btn-sm">Update Post</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <script src="https://kit.fontawesome.com/0bdbee9ded.js" crossorigin="anonymous"></script>
    <script src="../scripts/bootstrap.bundle.min.js"></script>
    <script src="../scripts/popper.min.js"></script>
    <script defer src="../scripts/app.js"></script>
    <script src="../scripts/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        $(document).on('submit', '#createPostForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("insert_post", true);
            $.ajax({
                type: 'POST',
                url: '../functions/insertPost.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    let res = jQuery.parseJSON(response);
                    alertify.set('notifier', 'position', 'top-center');

                    if (res.status == 200) {
                        let notif = alertify.success(res.message);
                        $('body').one('click', function() {
                            notif.dismiss();
                        });
                        $('#insertPostModal').modal('hide');
                        $('#createPostForm')[0].reset();

                        $('#userPostsTable').load("homepage.php #userPostsTable");

                    } else if (res.status == 400 || res.message == 500) {
                        $('#insertPostModal').modal('hide');
                        let notif = alertify.error(res.message);
                        $('body').one('click', function() {
                            notif.dismiss();
                        });
                    }
                }
            });
        });

        $(document).on('click', '.editPostButton', function() {
            let post_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: '../functions/editPost.php?post_id=' + post_id,
                success: function(response) {
                    let res = jQuery.parseJSON(response);
                    alertify.set('notifier', 'position', 'top-center');

                    if (res.status == 200) {
                        $('#post_id_edit').val(res.data.post_id);
                        $('#detailsInput').val(res.data.details);

                        console.log('Privacy:', res.data.privacy);

                        if (res.data.privacy == "private") {
                            $('#editPrivacyCheckbox').prop('checked', true);
                        } else {
                            $('#editPrivacyCheckbox').prop('unchecked', true);
                        }

                        $('#editPostModal').modal('show');


                    } else if (res.status == 404) {
                        let notif = alertify.error(res.message);
                        $('body').one('click', function() {
                            notif.dismiss();
                        });
                    }
                }
            });
        });

        $(document).on('submit', '#updatePostForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("update_post", true);

            $.ajax({
                type: 'POST',
                url: '../functions/updatePost.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = jQuery.parseJSON(response);
                    alertify.set('notifier', 'position', 'top-center');

                    if (res.status == 200) {
                        let notif = alertify.success(res.message);
                        $('body').one('click', function() {
                            notif.dismiss();
                        });
                        $('#editPostModal').modal('hide');
                        $('#updatePostForm')[0].reset();

                        $('#userPostsTable').load("homepage.php #userPostsTable");
                    } else if (res.status == 500) {
                        $('#editPostModal').modal('hide');
                        let notif = alertify.error(res.message);
                        $('body').one('click', function() {
                            notif.dismiss();
                        });
                    }
                }
            });
        });

        $(document).on('click', '.deletePostButton', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete this post?')) {
                let post_id = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '../functions/deletePost.php',
                    data: {
                        'delete_post': true,
                        'post_id': post_id
                    },
                    success: function(response) {
                        let res = jQuery.parseJSON(response);
                        alertify.set('notifier', 'position', 'top-center');
                        if (res.status == 200) {
                            let notif = alertify.success(res.message);
                            $('body').one('click', function() {
                                notif.dismiss();
                            });
                            $('#userPostsTable').load("homepage.php #userPostsTable");

                        } else if (res.status == 500) {
                            let notif = alertify.error(res.message);
                            $('body').one('click', function() {
                                notif.dismiss();
                            });
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>