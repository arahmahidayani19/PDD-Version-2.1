<div class="modal fade text-left w-100" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 98%; width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Daily Transactions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="proses_submit.php" method="post">
                    <div class="row">
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <div class="col-md-12 mb-4 entry" id="entry-<?php echo $i; ?>" style="<?php echo $i > 0 ? 'display:none;' : ''; ?>">
                                <h6>Entry <?php echo $i + 1; ?></h6>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label for="partNumber<?php echo $i; ?>" class="form-label">Part Number</label>
                                        <select id="partNumber<?php echo $i; ?>" name="partNumber[]" class="form-control select2" style="width: 100%;" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="" disabled selected>Select a part number</option>
                                            <?php
                                            $sqlpart_number = "SELECT productID FROM products";
                                            $resultpart_number = $conn->query($sqlpart_number);
                                            if ($resultpart_number->num_rows > 0) {
                                                while ($rowpart_number = $resultpart_number->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rowpart_number['productID']) . '">' . htmlspecialchars($rowpart_number['productID']) . '</option>';
                                                }
                                            } else {
                                                echo '<option>No Part Number data found.</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="shift<?php echo $i; ?>" class="form-label">Shift</label>
                                        <select id="shift<?php echo $i; ?>" name="shift[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="1st Shift">1st Shift</option>
                                            <option value="2nd Shift">2nd Shift</option>
                                            <option value="3rd Shift">3rd Shift</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineType<?php echo $i; ?>" class="form-label">Line</label>
                                        <select id="machineType<?php echo $i; ?>" name="machineType[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?> onchange="loadMachines(<?php echo $i; ?>)">
                                            <option value="">Select Line</option>
                                            <?php
                                            $sqlline = "SELECT DISTINCT line_name FROM lines_machines";
                                            $resultline = $conn->query($sqlline);
                                            if ($resultline->num_rows > 0) {
                                                while ($rowline = $resultline->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rowline['line_name']) . '">' . htmlspecialchars($rowline['line_name']) . '</option>';
                                                }
                                            } else {
                                                echo '<option>No Line data found.</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineNumber<?php echo $i; ?>" class="form-label">Machine</label>
                                        <select id="machineNumber<?php echo $i; ?>" name="machineNumber[]" class="form-control">
                                            <option value="" disabled selected>Select a machine</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineStatus<?php echo $i; ?>" class="form-label">Status</label>
                                        <select id="machineStatus<?php echo $i; ?>" name="machineStatus[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="running">Running</option>
                                            <option value="stopped">Stopped</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="transactionDatetime<?php echo $i; ?>" class="form-label">Date & Time</label>
                                        <input type="datetime-local" id="transactionDatetime<?php echo $i; ?>" name="transactionDatetime[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="jobsiteno<?php echo $i; ?>" class="form-label">JS No</label>
                                        <select id="jobsiteno<?php echo $i; ?>" name="jobsiteno[]" class="form-control select2" style="width: 100%;" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="" disabled selected>Select JS No</option>
                                            <?php
                                            $sqljob_order = "SELECT jobOrderID FROM job_orders";
                                            $resultjob_order = $conn->query($sqljob_order);
                                            if ($resultjob_order->num_rows > 0) {
                                                while ($rowjob_order = $resultjob_order->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rowjob_order['jobOrderID']) . '">' . htmlspecialchars($rowjob_order['jobOrderID']) . '</option>';
                                                }
                                            } else {
                                                echo '<option>No JS data found.</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="addEntryBtn" class="btn btn-primary">Add Entry</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>