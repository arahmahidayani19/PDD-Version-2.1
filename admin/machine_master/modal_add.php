<div class="modal fade" id="inlineForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Machine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addMesinForm" action="add_machine.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="line_name">Line</label>
                                <input type="text" class="form-control" id="line_name" name="line_name" placeholder="Enter line name" required>
                            </div>
                            <div class="form-group">
                                <label for="machineName">M/C No</label>
                                <input type="text" class="form-control" id="machineName" name="machineName" placeholder="Enter machine number" required>
                            </div>
                            <div class="form-group">
                                <label for="assetNo">Asset No</label>
                                <input type="text" class="form-control" id="assetNo" name="assetNo" placeholder="Enter asset number" required>
                            </div>
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter brand" required>
                            </div>
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model" name="model" placeholder="Enter model" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serialNo">Serial No</label>
                                <input type="text" class="form-control" id="serialNo" name="serialNo" placeholder="Enter serial number" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" class="form-control" id="date" name="date" placeholder="Enter date" required>
                            </div>
                            <div class="form-group">
                                <label for="tonnage">Tonnage</label>
                                <input type="number" step="0.01" class="form-control" id="tonnage" name="tonnage" placeholder="Enter tonnage" required>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Machine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>