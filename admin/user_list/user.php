<?php include('../sidebar.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/nav.css">
    <link rel="stylesheet" href="../../dist/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <?php include('../../include/nav.php'); ?>
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">User List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">User List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inlineForm">
                                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Add User
                                </button>
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="userTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be loaded dynamically via JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Include Modal -->
    <?php include('modal_add.php'); ?>
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editUserForm">
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-control" id="editRole" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php include('../../include/footer.php'); ?>
    </div>

    <!-- JS Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.js"></script>

    <script>
        $(document).ready(function() {
            loadUserTable();

            function loadUserTable() {
                $.ajax({
                    url: "user_data.php",
                    method: "GET",
                    success: function(data) {
                        try {
                            const users = JSON.parse(data);
                            let rows = "";

                            users.forEach((user) => {
                                rows += `
                        <tr>
                            <td>${user.username}</td>
                            <td>${user.role}</td>
                            <td>
                               <button class="btn btn-warning btn-sm editUserBtn" 
                                       data-id="${user.id}" 
                                       data-username="${user.username}" 
                                       data-password="${user.password}"
                                       data-role="${user.role}">
                                <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                                <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>`;
                            });

                            if ($.fn.DataTable.isDataTable("#userTable")) {
                                $("#userTable").DataTable().clear().destroy();
                            }

                            $("#userTable tbody").html(rows);
                            $("#userTable").DataTable({
                                retrieve: true,
                                autoWidth: false,
                            });
                        } catch (e) {
                            console.error("Error parsing JSON data", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(`Error loading user data: ${status} - ${error}`);
                    },
                });
            }

            // Helper function to close Add Modal
            function closeAddModal() {
                try {
                    // Try Bootstrap 5 method first
                    var addModal = bootstrap.Modal.getInstance(document.getElementById('inlineForm'));
                    if (addModal) {
                        addModal.hide();
                        return;
                    }
                } catch (e) {
                    console.log("Not using Bootstrap 5, trying Bootstrap 4 method");
                }

                // Fallback to Bootstrap 4 method
                $("#inlineForm").modal("hide");
            }

            // Helper function to close Edit Modal
            function closeEditModal() {
                try {
                    // Try Bootstrap 5 method first
                    var editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    if (editModal) {
                        editModal.hide();
                        return;
                    }
                } catch (e) {
                    console.log("Not using Bootstrap 5, trying Bootstrap 4 method");
                }

                // Fallback to Bootstrap 4 method
                $("#editUserModal").modal("hide");
            }

            // Add User - Show Modal Button
            $(document).on("click", ".addUserBtn", function() {
                // Reset form first to clear any previous data
                $("#addUserForm")[0].reset();

                // For Bootstrap 5
                try {
                    var addModal = new bootstrap.Modal(document.getElementById('inlineForm'));
                    addModal.show();
                } catch (e) {
                    // For Bootstrap 4
                    $("#inlineForm").modal("show");
                }
            });

            // Handle Add User Form
            $(document).ready(function() {
                // Handle Add User Form
                $("#addUserForm").on("submit", function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: '<div class="loading-spinner"></div>Processing your request...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                        background: '#fff',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });

                    $.ajax({
                        url: "add_user.php",
                        method: "POST",
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                // Close the modal first
                                closeAddModal();

                                // Reset form fields 
                                $("#addUserForm")[0].reset();

                                // Then reload table data
                                loadUserTable();

                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: "New user has been added successfully",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#fff',
                                    iconColor: '#28a745',
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                });
                            } else {
                                // Error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: response.message || "Unable to add new user",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#fff',
                                    iconColor: '#dc3545',
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: "The server is not responding. Please try again later.",
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#fff',
                                iconColor: '#dc3545',
                                customClass: {
                                    popup: 'animated fadeInDown'
                                }
                            });
                            console.error(`Error adding user: ${status} - ${error}`);
                        },
                    });
                });

                // Edit User - Button click event
                $(document).on("click", ".editUserBtn", function() {
                    const userId = $(this).data("id");
                    const username = $(this).data("username");
                    const role = $(this).data("role");
                    const password = $(this).data("password");

                    console.log("Edit Click - ID:", userId, "Username:", username, "Role:", role, "Password:", password);

                    $("#editUserId").val(userId);
                    $("#editUsername").val(username);
                    $("#editRole").val(role);
                    $("#editPassword").val(password);

                    // Untuk Bootstrap 5
                    try {
                        var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                        editModal.show();
                    } catch (e) {
                        // Untuk Bootstrap 4
                        $("#editUserModal").modal("show");
                    }
                });

                // Handle Edit User Form
                $("#editUserForm").on("submit", function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    // Show loading state
                    Swal.fire({
                        title: 'Updating...',
                        html: '<div class="loading-spinner"></div>Updating your data...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                        background: '#fff',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });

                    $.ajax({
                        url: "edit_user.php",
                        method: "POST",
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                // Pendekatan untuk menutup modal
                                try {
                                    var editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                                    if (editModal) {
                                        editModal.hide();
                                    }
                                } catch (e) {
                                    console.log("Bootstrap 5 close failed:", e);
                                }

                                try {
                                    $("#editUserModal").modal('hide');
                                    $('body').removeClass('modal-open');
                                    $('.modal-backdrop').remove();
                                } catch (e) {
                                    console.log("Bootstrap 4 close failed:", e);
                                }

                                // Reload table
                                loadUserTable();

                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: "User information has been updated in the system",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#fff',
                                    iconColor: '#28a745',
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Update Failed!',
                                    html: response.message || "Unable to update user information",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#fff',
                                    iconColor: '#dc3545',
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Connection Error!',
                                text: "Failed to connect to the server",
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#fff',
                                iconColor: '#dc3545',
                                customClass: {
                                    popup: 'animated fadeInDown'
                                }
                            });
                            console.error(`Error updating user: ${status} - ${error}`);
                        },
                    });
                });

                // Delete User function
                window.deleteUser = function(id) {
                    Swal.fire({
                        title: 'Delete Confirmation',
                        text: "Are you sure you want to delete this user?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        },
                        background: '#fff',
                        backdrop: `
                rgba(0,0,0,0.4)
                left top
                no-repeat
            `
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait while we process your request',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            $.ajax({
                                url: "delete_user.php",
                                method: "POST",
                                data: {
                                    userId: id
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.status === "success") {
                                        // Reload table data immediately
                                        loadUserTable();

                                        // Show success message
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted Successfully!',
                                            text: "User has been removed from the system",
                                            showConfirmButton: false,
                                            timer: 1500,
                                            background: '#fff',
                                            iconColor: '#28a745',
                                            customClass: {
                                                popup: 'animated fadeInDown'
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Delete Failed!',
                                            text: response.message || "Unable to delete user",
                                            showConfirmButton: false,
                                            timer: 1500,
                                            background: '#fff',
                                            iconColor: '#dc3545',
                                            customClass: {
                                                popup: 'animated fadeInDown'
                                            }
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Connection Error!',
                                        text: "Failed to connect to the server",
                                        showConfirmButton: false,
                                        timer: 1500,
                                        background: '#fff',
                                        iconColor: '#dc3545',
                                        customClass: {
                                            popup: 'animated fadeInDown'
                                        }
                                    });
                                    console.error(`Error deleting user: ${status} - ${error}`);
                                },
                            });
                        }
                    });
                };
            });
        });
    </script>



</body>

</html>