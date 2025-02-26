<!-- Edit Modal -->
<div class="modal fade" id="editForm" tabindex="-1" role="dialog" aria-labelledby="editFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFormLabel">Edit Part Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPartNumberForm" action="edit_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    // Get columns from database
                    $columns_result = $conn->query("SHOW COLUMNS FROM products");

                    // Function to check if field needs file upload
                    function needsFileUploadEdit($fieldName)
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
                                <label for="edit_partNumber" class="form-label">Part Number</label>
                                <input type="text" class="form-control" id="edit_partNumber" name="part_number" readonly>
                            </div>
                        <?php
                        }
                        // For fields that need file upload (detected dynamically)
                        elseif (needsFileUploadEdit($field)) {
                            $display_name = ucwords(str_replace('_', ' ', $field));
                        ?>
                            <div class="form-group">
                                <label><?php echo $display_name; ?></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="edit_<?php echo $field; ?>_path"
                                            name="<?php echo $field; ?>_path"
                                            class="form-control"
                                            placeholder="Input path here">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"
                                                id="edit_<?php echo $field; ?>_file"
                                                name="<?php echo $field; ?>_file"
                                                onchange="checkInput('edit_<?php echo $field; ?>_path', 'edit_<?php echo $field; ?>_file')">
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
                                <label for="edit_<?php echo $field; ?>" class="form-label"><?php echo $display_name; ?></label>
                                <input type="text" class="form-control" id="edit_<?php echo $field; ?>"
                                    name="<?php echo $field; ?>" placeholder="Enter <?php echo strtolower($display_name); ?>">
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Part Number</button>
                </div>
            </form>
        </div>
    </div>
</div>