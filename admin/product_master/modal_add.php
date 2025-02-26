<div class="modal fade" id="addPNModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Part Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>


            </div>
            <form id="addPNForm" action="add_pn.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pn">Part Number</label>
                            <input type="text" class="form-control" id="pn" name="pn" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pn_name">Part Name</label>
                            <input type="text" class="form-control" id="pn_name" name="pn_name" required>
                        </div>
                        <!-- Customer Field -->
                        <div class="form-group col-md-6">
                            <label for="customer_id">Customer ID</label>
                            <input type="text" class="form-control" id="customer_id" name="customer_id" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Part Number</button>
                </div>
            </form>
        </div>
    </div>
</div>