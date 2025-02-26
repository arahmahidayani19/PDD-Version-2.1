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
</head>

<body>
    <?php include('../../include/nav.php'); ?>
    <div class="wrapper">

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Part Number Documents</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Part Number Documents</li>
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
                            <div class="card-body">
                                <!-- Add Data Button -->
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                    <i class="fa fa-plus mr-2"></i> Add Data
                                </button>

                                <?php include('file_back-end.php'); ?>

                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="partsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <?php
                                                        $columns = [];
                                                        $columns_result = $conn->query("SHOW COLUMNS FROM products");
                                                        while ($column = $columns_result->fetch_assoc()) {
                                                            $field = $column['Field'];

                                                            // Skip jika kolom ada di hidden_columns
                                                            if (!in_array($field, $hidden_columns)) {
                                                                // Hapus "_path" dari nama kolom
                                                                $cleaned_field = str_replace('_path', '', $field);

                                                                // Ubah huruf pertama tiap kata jadi besar
                                                                $formatted_field = ucwords(str_replace('_', ' ', $cleaned_field));

                                                                $columns[] = $field;
                                                                echo "<th>" . htmlspecialchars($formatted_field) . "</th>";
                                                            }
                                                        }
                                                        echo "<th class='text-center' style='width: 150px;'>Action</th>";
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // PHP untuk menampilkan data dari database
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            // Cek apakah ada kolom yang memiliki nilai selain kolom wajib
                                                            $has_additional_data = false;
                                                            foreach ($columns as $col) {
                                                                // Lewati kolom wajib
                                                                if (in_array($col, $required_columns)) {
                                                                    continue;
                                                                }

                                                                // Jika kolom memiliki nilai, tandai sebagai ada data tambahan
                                                                if (!empty($row[$col])) {
                                                                    $has_additional_data = true;
                                                                    break;
                                                                }
                                                            }

                                                            // Tampilkan baris jika ada kolom tambahan yang memiliki nilai
                                                            if ($has_additional_data) {
                                                                echo "<tr>";
                                                                foreach ($columns as $col) {
                                                                    // Cek apakah kolom berisi path (misalnya dengan memeriksa apakah ada tanda '/' atau ekstensi file)
                                                                    if (!empty($row[$col]) && (strpos($row[$col], '/') !== false || strpos($row[$col], '.') !== false)) {
                                                                        // Jika kolom mengandung path file, buat link dinamis
                                                                        $file_url = 'file_proxy.php?path=' . urlencode($row[$col]);
                                                                        echo "<td><a href='" . htmlspecialchars($file_url) . "' target='_blank'>" . htmlspecialchars(basename($row[$col])) . "</a></td>";
                                                                    } else {
                                                                        // Menampilkan nilai biasa
                                                                        echo "<td>" . (!is_null($row[$col]) ? htmlspecialchars($row[$col]) : '') . "</td>";
                                                                    }
                                                                }
                                                                // Tombol Edit dan Delete (menggunakan productID yang masih tersimpan di $row meskipun tidak ditampilkan)
                                                                echo "<td class='text-center'>
                                                                <div class='d-flex justify-content-center' style='gap: 10px;'>
                                                                    <a href='#' class='btn btn-warning btn-sm edit-btn'
                                                                       data-id='" . htmlspecialchars($row['productID']) . "'
                                                                       data-toggle='modal' data-target='#editPartNumberModal'>
                                                                        <i class='fas fa-edit'></i> Edit
                                                                    </a>
                                                                    <a href='#' class='btn btn-danger btn-sm delete-btn' 
                                                                       data-id='" . htmlspecialchars($row['productID']) . "'>
                                                                        <i class='fas fa-trash-alt'></i> Delete
                                                                    </a>
                                                                </div>
                                                              </td>";

                                                                echo "</tr>";
                                                            }
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='" . (count($columns) + 1) . "'>Data not found</td></tr>";
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

    <?php include('modal_add.php'); ?>
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
            // Inisialisasi Select2 untuk part number
            $('#partNumber').select2({
                dropdownParent: $('#inlineForm'), // Sesuaikan dengan ID modal yang benar
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select a part number',
                allowClear: true
            });

            // Pastikan modal events dihandle dengan benar
            $('#inlineForm').on('shown.bs.modal', function() {
                $('#partNumber').select2('open');
            });

            // Destroy dan reinit select2 saat modal ditutup untuk mencegah masalah
            $('#inlineForm').on('hidden.bs.modal', function() {
                $('#partNumber').select2('destroy');
                $('#partNumber').select2({
                    dropdownParent: $('#inlineForm'),
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Select a part number',
                    allowClear: true
                });
            });
        });

        $(document).ready(function() {
            // Initialize DataTable
            initializeDataTable();

            // Show modal on "Add Data" button click
            handleAddDataButtonClick();

            // Form submission for adding part number
            handleAddPNFormSubmit();

            // Handle Edit button click
            handleEditButtonClick();

            // Form submission for editing part number
            handleEditPNFormSubmit();

            // Handle Delete button click
            handleDeleteButtonClick();

            // Initialize DataTable
            function initializeDataTable() {
                $('#partsTable').DataTable({
                    "destroy": true,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": false,
                    "scrollY": "400px",
                    "scrollCollapse": true,
                    "scrollX": true
                });
            }

            // Handle "Add Data" button click
            function handleAddDataButtonClick() {
                $('button[data-bs-toggle="modal"]').on('click', function() {
                    $('#inlineForm').modal('show');
                });
            }

            // Handle Add PN Form submission
            function handleAddPNFormSubmit() {
                $('#addPartNumberForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = new FormData(this);

                    // Tampilkan loading SweetAlert
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
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var result = JSON.parse(response);

                            if (result.status === 'success') {
                                // Show success message with SweetAlert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: result.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#fff',
                                    iconColor: '#28a745',
                                    customClass: {
                                        popup: 'animated fadeInDown'
                                    }
                                }).then(function() {
                                    // Close modal and refresh page
                                    $('#inlineForm').modal('hide');
                                    if (result.redirect) {
                                        window.location.href = result.redirect;
                                    } else {
                                        location.reload();
                                    }
                                });
                            } else {
                                // Show error message with SweetAlert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: result.message,
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
                                text: 'An unexpected error occurred',
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
            }

            // Handle Edit button click
            function handleEditButtonClick() {
                $(document).on('click', '.edit-btn', function() {
                    var productID = $(this).data('id');

                    // Tampilkan loading SweetAlert
                    Swal.fire({
                        title: 'Loading...',
                        html: '<div class="loading-spinner"></div>Loading data...',
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

                    // Fetch product data with AJAX
                    $.ajax({
                        url: 'get_product.php',
                        type: 'POST',
                        data: {
                            productID: productID
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Tutup loading alert
                            Swal.close();

                            // Populate form fields with fetched data
                            $('#edit_partNumber').val(data.productID);

                            // Loop through all other fields dynamically
                            for (var key in data) {
                                if (key !== 'productID') {
                                    // For regular text fields
                                    if ($('#edit_' + key).length) {
                                        $('#edit_' + key).val(data[key]);
                                    }
                                    // For file fields, show the existing path
                                    if ($('#edit_' + key + '_path').length) {
                                        $('#edit_' + key + '_path').val(data[key]);
                                    }
                                }
                            }

                            // Show the edit modal
                            $('#editForm').modal('show');
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to fetch product data.',
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
            }

            // Handle Edit PN Form submission
            function handleEditPNFormSubmit() {
                $('#editPartNumberForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = new FormData(this);

                    // Tampilkan loading SweetAlert
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
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            try {
                                var result = JSON.parse(response);

                                if (result.status === 'success') {
                                    // Show success message with SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: result.message,
                                        timer: 1500,
                                        showConfirmButton: false,
                                        background: '#fff',
                                        iconColor: '#28a745',
                                        customClass: {
                                            popup: 'animated fadeInDown'
                                        }
                                    }).then(function() {
                                        // Close modal and redirect or refresh
                                        $('#editForm').modal('hide');
                                        if (result.redirect) {
                                            window.location.href = result.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    // Show error message with SweetAlert
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Update Failed!',
                                        html: result.message,
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
                        error: function(xhr, status, error) {
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
            }

            // Handle Delete button click
            function handleDeleteButtonClick() {
                $(document).on('click', '.delete-btn', function() {
                    var productID = $(this).data('id');
                    var row = $(this).closest('tr');

                    // Show confirmation dialog with SweetAlert
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

                            // Proceed with deletion
                            $.ajax({
                                url: 'delete_files.php',
                                type: 'POST',
                                data: {
                                    id: productID
                                },
                                success: function(response) {
                                    try {
                                        var res = JSON.parse(response);
                                        if (res.status === 'success') {
                                            // Jika menggunakan DataTable
                                            if (typeof table !== 'undefined') {
                                                table.row(row).remove().draw(false);
                                            }

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
                                            }).then(function() {
                                                if (!table) {
                                                    location.reload();
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
                                            timer: 1500,
                                            background: '#fff',
                                            iconColor: '#dc3545',
                                            customClass: {
                                                popup: 'border-radius-3'
                                            }
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
            }

            // Function to check which input to use (path or file)
            function checkInput(pathId, fileId) {
                var filePath = $('#' + fileId).val();
                if (filePath) {
                    $('#' + pathId).val('');
                }
            }
        });

        // Make the checkInput function available globally
        function checkInput(pathId, fileId) {
            var filePath = $('#' + fileId).val();
            if (filePath) {
                $('#' + pathId).val('');
            }
        }
    </script>


</body>

</html>