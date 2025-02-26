<?php include('back.php'); ?>
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
    <div class="wrapper">
        <!-- Navbar -->
        <?php include('../../include/nav.php'); ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Part Number Master</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Part Number Master</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- User Table Card -->

                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- Add User Button -->
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPNModal">
                                    <i class="fa fa-plus"></i> Add Data
                                </button>


                                <section class="section">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <!-- User Table -->
                                                <table id="partNumber" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Part NO</th>
                                                            <th>Part Name</th>
                                                            <th>Customer ID</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT id, productID, productName, customerID FROM products";  // Add 'id' in the SELECT query
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<tr>';
                                                                echo '<td>' . htmlspecialchars($row['productID'] ?? '') . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['productName'] ?? '') . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['customerID'] ?? '') . '</td>';
                                                                echo '<td>';
                                                                echo '<div class="d-flex gap-2">';
                                                                echo '<button type="button" class="btn btn-sm btn-warning mr-2" onclick="editPN(' . $row['id'] . ', \'' . htmlspecialchars($row['productID'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['customerID'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['productName'], ENT_QUOTES) . '\')"><i class="fa fa-edit"></i> Edit</button>';
                                                                echo '<button type="button" class="btn btn-sm btn-danger" onclick="deletePN(' . $row['id'] . ')"><i class="fa fa-trash"></i> Delete</button>';
                                                                echo '</div>';
                                                                echo '</td>';
                                                                echo '</tr>';
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='3'>No part numbers found</td></tr>";
                                                        }

                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Add Part Number Modal -->
    <?php include('modal_add.php'); ?>


    <!-- Edit Part Number Modal -->
    <?php include('modal_edite.php'); ?>



    <!-- Footer -->
    <?php include('../../include/footer.php'); ?>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../dist/sweetalert2/sweetalert2.js"></script>
    <script src="../../dist/js/dark_buton.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            initializeDataTable();

            // Show modal on "Add Data" button click
            handleAddDataButtonClick();

            // Form submission for adding part number
            handleAddPNFormSubmit();

            // Form submission for editing part number
            handleEditPNFormSubmit();
        });

        // Function to initialize DataTable
        function initializeDataTable() {
            $('#partNumber').DataTable({
                responsive: true,
                columnDefs: [{
                        targets: [2],
                        orderable: false
                    } // Disable sorting on 'Action' column
                ],
                lengthMenu: [10, 25, 50], // Adjust to your preference
                pageLength: 10, // Default page length
            });
        }

        // Function to handle "Add Data" button click
        function handleAddDataButtonClick() {
            $('button[data-bs-toggle="modal"]').on('click', function() {
                $('#addPNModal').modal('show');
            });
        }

        function deletePN(id) {
            // Show confirmation dialog with SweetAlert before proceeding with delete
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with AJAX request to delete the part number
                    $.ajax({
                        url: 'delete_pn.php', // PHP script to handle deletion
                        type: 'POST',
                        data: {
                            deletePNId: id
                        }, // Send the part number ID to delete
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500, // Automatically close after 1.5 seconds
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload the page after deletion
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was an issue with the request. Please try again later.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        // Success handler for delete action
        function handleDeleteResponse(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
        }

        // Error handler for AJAX requests
        function handleError() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an issue with the request. Please try again later.',
                confirmButtonText: 'OK'
            });
        }


        // Function to handle Edit button click and show modal
        function editPN(id, productID, customerID, productName) {
            // Set values in the modal input fields
            document.getElementById('edit_productID').value = id;
            document.getElementById('edit_pn').value = productID;
            document.getElementById('edit_pn_name').value = productName;
            document.getElementById('edit_customer_id').value = customerID;

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('editPNModal'));
            myModal.show();
        }

        // Function to handle form submission for adding part numbers
        function handleAddPNFormSubmit() {
            $('#addPNForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'add_pn.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: handleAddPNResponse,
                    error: handleError
                });
            });
        }

        // Success handler for adding part numbers
        function handleAddPNResponse(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
        }

        // Function to handle form submission for editing part numbers
        function handleEditPNFormSubmit() {
            $('#editPNForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'edit_pn.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: handleEditPNResponse,
                    error: handleError
                });
            });
        }

        // Success handler for editing part numbers
        function handleEditPNResponse(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>

</body>

</html>