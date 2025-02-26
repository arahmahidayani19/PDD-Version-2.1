<div class="modal fade" id="editMachineModal" tabindex="-1" role="dialog" aria-labelledby="editMachineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Machine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editMesinForm" action="edite_machine.php" method="POST">
                    <input type="hidden" id="editMachineId" name="editMachineId">
                    <!-- Use Bootstrap grid for horizontal layout -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editMachineName">M/C No</label>
                                <input type="text" class="form-control" id="editMachineName" name="editMachineName" required>
                            </div>
                            <div class="form-group">
                                <label for="editAssetNo">Asset No</label>
                                <input type="text" class="form-control" id="editAssetNo" name="editAssetNo" required>
                            </div>
                            <div class="form-group">
                                <label for="editBrand">Brand</label>
                                <input type="text" class="form-control" id="editBrand" name="editBrand" required>
                            </div>
                            <div class="form-group">
                                <label for="editModel">Model</label>
                                <input type="text" class="form-control" id="editModel" name="editModel" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSerialNo">Serial No</label>
                                <input type="text" class="form-control" id="editSerialNo" name="editSerialNo" required>
                            </div>
                            <div class="form-group">
                                <label for="editDate">Date</label>
                                <input type="text" class="form-control" id="editDate" name="editDate" required>
                            </div>
                            <div class="form-group">
                                <label for="editTonnage">Tonnage</label>
                                <input type="number" step="0.01" class="form-control" id="editTonnage" name="editTonnage" required>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Machine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>