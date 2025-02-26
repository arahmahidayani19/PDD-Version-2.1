<div class="modal fade" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="inlineFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inlineFormLabel">Add Part Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addPartNumberForm" action="add_proses.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    // Get columns from database
                    $columns_result = $conn->query("SHOW COLUMNS FROM products");

                    // Function to check if field needs file upload
                    function needsFileUpload($fieldName)
                    {
                        $fileRelatedTerms = ['path', 'file', 'document', 'attachment', 'upload', 'parameter', 'instruction', 'packaging'];
                        $fieldNameLower = strtolower($fieldName);

                        foreach ($fileRelatedTerms as $term) {
                            if (strpos($fieldNameLower, $term) !== false) {
                                return true;
                            }
                        }
                        return false;
                    }

                    while ($column = $columns_result->fetch_assoc()) {
                        $field = $column['Field'];

                        // Skip hidden columns
                        if (in_array($field, $hidden_columns)) {
                            continue;
                        }

                        // Special handling for productID/part number
                        if ($field === 'productID') {
                    ?>
                            <div class="form-group mb-3">
                                <label for="partNumber" class="form-label">Part Number</label>
                                <select id="partNumber" name="part_number" class="select2" style="width: 100%;" required>
                                    <option value="" disabled selected>Select a part number</option>
                                    <?php
                                    $sqlpart_number = "SELECT productID FROM products";
                                    $resultpart_number = $conn->query($sqlpart_number);
                                    if ($resultpart_number->num_rows > 0) {
                                        while ($rowpart_number = $resultpart_number->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($rowpart_number['productID']) . '">' .
                                                htmlspecialchars($rowpart_number['productID']) . '</option>';
                                        }
                                    } else {
                                        echo '<option>No Part Number data found.</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php
                        }
                        // For fields that need file upload (detected dynamically)
                        elseif (needsFileUpload($field)) {
                            $display_name = ucwords(str_replace('_', ' ', $field));
                        ?>
                            <div class="form-group">
                                <label><?php echo $display_name; ?></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="<?php echo $field; ?>_path"
                                            name="<?php echo $field; ?>_path"
                                            class="form-control"
                                            placeholder="Input path here">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"
                                                id="<?php echo $field; ?>_file"
                                                name="<?php echo $field; ?>_file"
                                                onchange="checkInput('<?php echo $field; ?>_path', '<?php echo $field; ?>_file')">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Choose to input a path or upload a file</small>
                            </div>
                        <?php
                        }
                        // For regular text fields
                        else {
                            $display_name = ucwords(str_replace('_', ' ', $field));
                        ?>
                            <div class="form-group mb-3">
                                <label for="<?php echo $field; ?>" class="form-label"><?php echo $display_name; ?></label>
                                <input type="text" class="form-control" id="<?php echo $field; ?>"
                                    name="<?php echo $field; ?>" placeholder="Enter <?php echo strtolower($display_name); ?>">
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Part Number</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Update file input label when file is selected
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            var fileName = this.files[0]?.name || 'No file chosen';
            this.nextElementSibling.innerHTML = fileName;
        });
    });

    function checkInput(pathId, fileId) {
        var pathInput = document.getElementById(pathId);
        var fileInput = document.getElementById(fileId);
        if (fileInput.value) {
            pathInput.value = '';
            pathInput.disabled = true;
        } else {
            pathInput.disabled = false;
        }
    }
</script>