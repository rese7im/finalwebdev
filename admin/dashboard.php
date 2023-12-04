<?php
include '../config/dbCon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
}


if (isset($_GET['log'])) {
    if ($_GET['log'] == 'true') {
        session_destroy();
        header("Location: ../index.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>


    <div class="container">
        <h1>Dashboard</h1>
        <a href="dashboard.php?log=true">Logout</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>

            <tbody id="listUsers">
                <?php
                $sql = "SELECT * FROM users";
                $select = mysqli_query($con, $sql);


                if (mysqli_num_rows($select) != 0) {
                    $counter = 0;


                    while ($row = mysqli_fetch_array($select)) {
                        $counter++;

                        ?>

                        <tr>
                            <th scope="row">
                                <?php echo $counter; ?>
                            </th>
                            <td>
                                <?php echo $row['email']; ?>
                            </td>
                            <td>
                                <?php echo $row['created_at']; ?>
                            </td>

                            <td>
                                <button value="<?php echo $row['id']; ?>" class="btn btn-primary editBtn">Edit</button>
                                <button type="button" value="<?php echo $row['id']; ?>"
                                    class="btn btn-danger deleteBtn">Delete</button>
                            </td>

                        </tr>


                        <?php

                    }
                }
                ?>
            </tbody>
        </table>


        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Email</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="userID" id="userID">

                        <!-- Inputs -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="userEmail" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary saveUser">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>

</html>


<script>

    $(document).ready(function () {

        // $('.editBtn').on('click', function()){

        // }

        $(document).on('click', '.saveUser', function (e) {
            e.preventDefault();

            var userID = $('#userID').val();
            var email = $('#userEmail').val();

            $.ajax({
                async: true,
                type: "GET",
                url: "process/updateUser.php?userID=" + userID + "&userEmail=" + email,
                dataType: "json",
                success: function (response) {

                    $('#editModal').modal('hide');

                    Swal.fire(
                        'Updated!',
                        'User has been updated.',
                        'success'
                    );

                    var counter = 0;

                    $('#listUsers').html('');

                    for (let i = 0; i < response.length; i++) {
                        counter++;

                        var id, email, created_at;

                        id = response[i][0];
                        email = response[i][1];
                        created_at = response[i][3];
                        $('#listUsers').append('<tr>\
                                <th scope="row">'+ counter + '</th >\
                                <td>'+ email + '</td>\
                                <td>'+ created_at + '</td>\
                            <td>\
                                <button type="button" value="'+ id + '" class="btn btn-primary editBtn">Edit</button>\
                                <button type="button" value="'+ id + '" class="btn btn-danger deleteBtn">Delete</button>\
                                </td >\
                            </tr>');

                    }


                }

            });


        });

        $(document).on('click', '.editBtn', function (e) {
            e.preventDefault();
            var userID = $(this).val();

            $.ajax({
                async: true,
                type: "GET",
                url: "process/editModal.php?userID=" + userID,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {

                    var email = response[1];

                    $('#userEmail').val(email);
                    $('#userID').val(userID);

                    $('#editModal').modal('show');


                }

            });


        });


        $(document).on('click', '.deleteBtn', function (e) {
            e.preventDefault();
            var userID = $(this).val();


            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        async: true,
                        type: "GET",
                        url: "process/deleteModal.php?userID=" + userID,
                        dataType: "json",
                        success: function (response) {

                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );

                            $('#listUsers').html('');

                            var counter = 0;

                            for (let i = 0; i < response.length; i++) {
                                counter++;

                                var id, email, created_at;

                                id = response[i][0];
                                email = response[i][1];
                                created_at = response[i][3];
                                $('#listUsers').append('<tr>\
                                <th scope="row">'+ counter + '</th >\
                                <td>'+ email + '</td>\
                                <td>'+ created_at + '</td>\
                            <td>\
                                <button type="button" value="'+ id + '" class="btn btn-primary editBtn">Edit</button>\
                                <button type="button" value="'+ id + '" class="btn btn-danger deleteBtn">Delete</button>\
                                </td >\
                            </tr >');

                            }

                        }

                    });

                }
            })

        });

    });


</script>