<?php include('../sidebar.php'); ?>
<?php include('../koneksi.php'); ?>
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
                            <h1 class="m-0">Machine Master</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Machine Master</li>
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

                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- Add User Button -->
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                    <i class="fa fa-plus"></i> Add Data
                                </button>


                                <section class="section">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <!-- User Table -->
                                                <table id="mesin" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>M/C No</th>
                                                            <th>Asset No</th>
                                                            <th>Brand</th>
                                                            <th>Model</th>
                                                            <th>Serial No</th>
                                                            <th>Date</th>
                                                            <th>Tonnage</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM lines_machines";
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                echo '<tr>';
                                                                echo '<td>' . htmlspecialchars($row['machine_name']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['asset_no']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['brand']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['model']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['serial_no']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['tonnage']) . '</td>';
                                                                echo '<td>';
                                                                echo '<div class="d-flex gap-2">';
                                                                echo '<button type="button" class="btn btn-sm btn-warning mr-2" onclick="editMachine(' . $row['id'] . ', \'' . htmlspecialchars($row['machine_name'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['asset_no'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['brand'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['model'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['serial_no'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['date'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['tonnage'], ENT_QUOTES) . '\')"><i class="fa fa-edit"></i> Edit</button>';
                                                                echo '<button type="button" class="btn btn-sm btn-danger" onclick="deleteMachine(' . $row['id'] . ')"><i class="fa fa-trash"></i> Delete</button>';
                                                                echo '</div>';
                                                                echo '</td>';
                                                                echo '</tr>';
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='8'>No machines found</td></tr>";
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
        <!-- End Content Wrapper -->
    </div>

    <!-- Add Machine Modal -->
    <?php include('modal_add.php'); ?>

    <!-- Edit Machine Modal -->
    <?php include('modal_edite.php'); ?>

    <!-- Footer -->
    <?php include('../../include/footer.php'); ?>
    </div>

    <!-- Scripts -->
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

            // Menangani klik tombol "Add Data" untuk membuka modal
            $('button[data-bs-toggle="modal"]').on('click', function() {
                $('#inlineForm').modal('show');
            });
        });
        // DataTable initialization
        $(document).ready(function() {
            $('#mesin').DataTable({
                responsive: true,
                columnDefs: [{
                    targets: [7],
                    orderable: false
                }]
            });
        });

        // Add Machine Form Submit
        $('#addMesinForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'add_machine.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Machine ' + response.machineName + ' added successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#inlineForm').modal('hide');
                        setTimeout(function() {
                            location.reload(); // Reload after success
                        }, 1500);
                    } else {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'An error occurred: ' + error,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        // Edit Machine Form Submit
        $('#editMesinForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'edite_machine.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Tambahkan ini untuk melihat respons di konsol
                    if (response.status === 'success') {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Machine ' + response.machineName + ' updated successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#editMachineModal').modal('hide');
                        setTimeout(function() {
                            location.reload(); // Reload after success
                        }, 2000);
                    } else {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Tambahkan ini untuk melihat kesalahan
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: 'An error occurred: ' + error,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            });
        });

        // Delete Machine
        // Delete Machine
        function deleteMachine(machineId) {
            var row = $('button[data-id="' + machineId + '"]').closest('tr');
            var machineName = row.find('td:first').text(); // Get machine name from table

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! Deleting machine " + machineName + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_machine.php', // Make sure this path is correct
                        type: 'POST',
                        data: {
                            deleteMachineId: machineId
                        }, // Send the ID as POST data
                        dataType: 'json', // Expect JSON response
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Machine ' + machineName + ' deleted!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $('#mesin').DataTable().row(row).remove().draw(); // Remove row from DataTable
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message, // Show error message from server
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Log the error for debugging
                            console.error(`Error: ${error}`);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'There was a problem deleting the machine.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }


        // Prefill Edit Modal
        function editMachine(id, machineName, assetNo, brand, model, serialNo, date, tonnage) {
            $('#editMachineId').val(id);
            $('#editMachineName').val(machineName);
            $('#editAssetNo').val(assetNo);
            $('#editBrand').val(brand);
            $('#editModel').val(model);
            $('#editSerialNo').val(serialNo);
            $('#editDate').val(date);
            $('#editTonnage').val(tonnage);

            $('#editMachineModal').modal('show');
        }
    </script>
</body>

</html>