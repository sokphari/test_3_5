<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud with Ajax</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between mt-5">
            <h3>User List</h3>
            <button class="btn btn-primary" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        </div>
        <table class="table table-success mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>GENDER</th>
                    <th>EMAIL</th>
                    <th>PASSWORD</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="tablebody">

            </tbody>
        </table>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" id="addUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <input type="hidden" name="userId" id="userId">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" id="userName" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Gender</label>
                                <input type="text" id="userGender" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" id="userEmail" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" id="userPassword" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" id="saveBtn" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- end modal -->

    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Fetch users and populate the table
    $(document).ready(function() {
        fetchUser();
        function fetchUser(){
            $.ajax({
                type: "GET",
                url: "fetch_users.php",
                success: function (response) {
                    $("#tablebody").html(response);
                }
            });
        }
        // Clear modal for add
        $("#addUserBtn").click(function() {
            $("#modalTitle").text("Add User");
            $("#userId").val("");
            $("#userName").val("");
            $("#userGender").val("");
            $("#userEmail").val("");
            $("#userPassword").val("");
        });
        // Edit button handler
        $(document).on("click", ".editBtn", function() {
            let id = $(this).data("id");
            $.ajax({
                type: "GET",
                url: "get_user.php",
                data: { id: id },
                success: function(response) {
                    let user = JSON.parse(response);
                    if (user.error) {
                        alert(user.error);
                        return;
                    }
                    $("#modalTitle").text("Edit User");
                    $("#userId").val(user.id); // id នេះយើងបានមកពី​ data-id attribute
                    $("#userName").val(user.name); // name នេះយើងបានមកពី JSON response 
                    $("#userGender").val(user.gender); // gender នេះយើងបានមកពី JSON response
                    $("#userEmail").val(user.email);
                    $("#userPassword").val(""); // Leave password blank for security
                    $("#addUserModal").modal("show");
                },
                error: function() {
                    alert("Error fetching user data");
                }
            });
        });
        $("#saveBtn").click(function() {
            let id = $("#userId").val();
            let name = $("#userName").val();
            let gender = $("#userGender").val();
            let email = $("#userEmail").val();
            let password = $("#userPassword").val();
            console.log(name,gender,email,password);
            //check validation 
            if (!name || !gender || !email || !password) {
                alert("Please fill all the fields");
                return;
            }
            let url = id ? "update_user.php" : "add_user.php";
            let data = id ? {
                id,
                name,
                gender,
                email,
                password
            } : {
                name,
                gender,
                email,
                password
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(response) {
                    if (response.trim() == "success") {
                        if(id){
                            alert("User updated successfully");
                        }else{
                            alert("User saved successfully");
                        }
                        $("#addUserModal").modal("hide");
                        location.reload();
                    }else{
                        alert("Error saving user");
                    }
                }

            });
        })
        // function deleteUser(id){
        //     if(confirm("Are You Sure")){
        //         $.ajax({
        //             type: "GET",
        //             url: "delete_users.php",
        //             data: {id:id},
        //             success: function (response) {
        //                 if(response.trim()  == "success"){
        //                     alert("User deleted successfully");
        //                     location.reload();
        //                 }else{
        //                     alert("Error deleting user");
        //                 }
        //             }
        //         });
        //     }else{
        //         alert("User not deleted");
        //         return;
        //     }
        // }
        $(document).on("click",".deleteBtn",function(){
            let userId = $(this).data("id"); //get id from data-id attribute
            console.log("User ID to delete:", userId); // Debugging log
            if(confirm("Are You Sure")){
                $.ajax({
                    type: "GET",
                    url: "delete_user.php",
                    data: {id:userId},
                    success: function (response) {
                        if(response.trim()  == "success"){
                            alert("User deleted successfully");
                            location.reload();
                        }else{
                            alert("Error deleting user");
                        }
                    }
                });
            }else{
                alert("User not deleted");
                return;
            }
        })
        $(document).on("click",".editBtn",function(){
            let userId = $(this).data("id"); // get id from data-id attribute
            console.log("User ID to edit:", userId); // Debugging log
        })
    })
</script>

</html>