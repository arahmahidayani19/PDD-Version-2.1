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
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<body>
    <div class="wrapper">
        <?php include('../../include/nav.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Machine Documents</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Machine Document</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <?php include('tabel_back.php'); ?>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                    <i class="fas fa-plus"></i> Add Data
                                </button>
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="partsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Machine Name</th>
                                                        <?php
                                                        if (!empty($docColumns)) {
                                                            foreach ($docColumns as $col) {
                                                                $displayLabel = ucwords(str_replace('_', ' ', str_replace('_path', '', $col)));
                                                                echo "<th>" . htmlspecialchars($displayLabel) . "</th>";
                                                            }
                                                        }
                                                        ?>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM machine_documents";
                                                    $result = mysqli_query($conn, $sql);

                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<tr>";
                                                            echo "<td>" . htmlspecialchars($row["machine_name"]) . "</td>";

                                                            foreach ($docColumns as $col) {
                                                                $path = $row[$col];
                                                                echo "<td>" . (!empty($path) ? "<a href='file_proxy.php?path=" . urlencode($path) . "' target='_blank'>" . htmlspecialchars(basename($path)) . "</a>" : "-") . "</td>";
                                                            }

                                                            echo "<td class='text-center'>
            <div class='btn-group'>
                <a href='#' 
                   class='btn btn-warning btn-sm edit-part mx-1' 
                   data-id='" . htmlspecialchars($row["id"]) . "' 
                   data-machine-name='" . htmlspecialchars($row["machine_name"]) . "' ";

                                                            foreach ($docColumns as $col) {
                                                                echo " data-" . $col . "='" . htmlspecialchars($row[$col]) . "' ";
                                                            }

                                                            echo " data-toggle='modal' data-target='#editForm'>
                    <i class='fas fa-edit'></i> Edit
                </a>
                <a href='#' class='btn btn-danger btn-sm deleteBtn mx-1' data-id='" . htmlspecialchars($row["id"]) . "'>
                    <i class='fas fa-trash-alt'></i> Delete
                </a>
            </div>
            </td>";

                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='" . (count($docColumns) + 2) . "' class='text-center'>Data tidak ditemukan</td></tr>";
                                                    }
                                                    ?>
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

    <?php include('modal_add_doc.php'); ?>
    <?php include('modal_edite.php'); ?>
    <?php include('../../include/footer.php'); ?>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
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
            $('.edit-part').click(function() {
                var id = $(this).data('id');
                var machineName = $(this).data('machine-name');

                // Set the hidden ID and select the machine name in dropdown
                $('#editMachineID').val(id);
                $('#editMachineIDInput').val(machineName);

                // Loop through each document column and set its value
                <?php foreach ($docColumns as $col): ?>
                    var colValue = $(this).data('<?php echo $col; ?>');
                    // Set the text input value
                    $(`#edit_<?php echo $col; ?>`).val(colValue);

                    // Reset the file input display text
                    if (colValue) {
                        $(`#edit_<?php echo $col; ?>_file`).next().find('span:last-child').text(colValue.split('/').pop());
                    } else {
                        $(`#edit_<?php echo $col; ?>_file`).next().find('span:last-child').text('No file chosen');
                    }
                <?php endforeach; ?>

                $('#editForm').modal('show');
            });
        });

        var table;

        $(document).ready(function() {
            table = $('#partsTable').DataTable({
                "destroy": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollY": "400px", // Aktifkan scroll vertikal
                "scrollCollapse": true,
                "scrollX": true
            });

            // Pastikan header ikut bergeser saat scroll
            $('.dataTables_scrollBody').css('overflow', 'auto');


            $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                $('#inlineForm').modal('show');
            });

            $('#partsTable').on('draw.dt', function() {
                $(document).on('click', 'button[data-bs-toggle="modal"]', function() {
                    $('#inlineForm').modal('show');
                });
            });
        });

        // Add form submission handler with enhanced alerts
        document.getElementById('addPartNumberForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

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

            fetch('add_proses.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#fff',
                            iconColor: '#28a745',
                            customClass: {
                                popup: 'animated fadeInDown'
                            }
                        }).then(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#fff',
                            iconColor: '#dc3545',
                            customClass: {
                                popup: 'animated fadeInDown'
                            }
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#fff',
                        iconColor: '#dc3545',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });
                });
        });

        // Edit form submission handler with enhanced alerts
        $('#editPartNumberForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

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
                url: 'edite_proses.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#fff',
                                iconColor: '#28a745',
                                customClass: {
                                    popup: 'animated fadeInDown'
                                }
                            }).then(() => {
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed!',
                                html: data.message,
                                timer: 1500,
                                showConfirmButton: false,
                                background: '#fff',
                                iconColor: '#dc3545',
                                customClass: {
                                    popup: 'animated fadeInDown'
                                }
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Invalid server response',
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
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error!',
                        text: 'Failed to connect to the server',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#fff',
                        iconColor: '#dc3545',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });
                }
            });
        });


        $(document).on('click', '.deleteBtn', function() {
            var partId = $(this).data('id');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Delete Confirmation',
                text: "Are you sure you want to delete this data?",
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
                        url: 'delete_machine_document.php', // Fixed URL path
                        type: 'POST',
                        data: {
                            id: partId
                        },
                        success: function(response) {
                            try {
                                var res = JSON.parse(response);
                                if (res.status === 'success') {
                                    table.row(row).remove().draw(false);

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted Successfully!',
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 1500,
                                        background: '#fff',
                                        iconColor: '#28a745',
                                        customClass: {
                                            title: 'text-success',
                                            popup: 'border-radius-3'
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Delete Failed!',
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 1500,
                                        background: '#fff',
                                        iconColor: '#dc3545',
                                        customClass: {
                                            title: 'text-danger',
                                            popup: 'border-radius-3'
                                        }
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Invalid server response',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Connection Error!',
                                text: 'Failed to connect to the server',
                                showConfirmButton: false,
                                timer: 1500,
                                background: '#fff',
                                iconColor: '#dc3545',
                                customClass: {
                                    title: 'text-danger',
                                    popup: 'border-radius-3'
                                }
                            });
                        }
                    });
                }
            });
        });

        function checkInput(pathId, fileId) {
            const fileInput = document.getElementById(fileId);
            const fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
            fileInput.nextElementSibling.lastElementChild.textContent = fileName;
        }
    </script>

</body>

</html>