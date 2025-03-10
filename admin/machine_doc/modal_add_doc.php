<div class="modal fade" id="inlineForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Machine Document</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addPartNumberForm" action="add_proses.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php include '../koneksi.php'; ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; margin-bottom: 8px;">Machine ID</label>
                        <select class="form-control" id="machineIDInput" name="machine_name" required
                            style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="" disabled selected>Select Machine ID</option>
                            <?php
                            $sql = "SELECT machine_name FROM lines_machines";
                            $result = mysqli_query($conn, $sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['machine_name']) . '">' . htmlspecialchars($row['machine_name']) . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No Machine IDs found</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    // Loop untuk membuat input field untuk setiap kolom dokumen
                    foreach ($docColumns as $col):
                        // Ubah nama kolom menjadi label yang lebih rapi
                        $displayLabel = ucwords(str_replace('_', ' ', str_replace('_path', '', $col))); ?>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; color: #666; margin-bottom: 8px;"><?php echo $displayLabel; ?></label>
                            <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                <input type="text" id="<?php echo $col; ?>" name="<?php echo $col; ?>"
                                    style="flex: 1; padding: 5px; border: 1px solid #ddd; border-radius: 4px;"
                                    placeholder="Input path here">
                                <div style="position: relative; width: 350px;">
                                    <input type="file" id="<?php echo $col; ?>_file" name="<?php echo $col; ?>_file"
                                        style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;"
                                        onchange="checkInput('<?php echo $col; ?>', '<?php echo $col; ?>_file')">
                                    <div style="display: flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                                        <span style="padding: 5px; background: #f8f9fa; border-right: 1px solid #ddd;">Choose File</span>
                                        <span style="padding: 5px; color: #666;">No file chosen</span>
                                    </div>
                                </div>
                            </div>
                            <small style="color: #666; font-size: 0.875em;">Choose to input a path or upload a file</small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" type="button" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Machine Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>