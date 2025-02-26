<div class="modal fade" id="editPNModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Part Number</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="editPNForm" action="edit_pn.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <!-- Hidden input to hold the product ID for editing -->
                        <input type="hidden" id="edit_productID" name="productID">

                        <div class="form-group col-md-6">
                            <label for="edit_pn">Part Number</label>
                            <input type="text" class="form-control" id="edit_pn" name="pn" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_pn_name">Part Name</label>
                            <input type="text" class="form-control" id="edit_pn_name" name="pn_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_customer_id">Customer ID</label>
                            <input type="text" class="form-control" id="edit_customer_id" name="customer_id" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Part Number</button>
                </div>
            </form>
        </div>
    </div>
</div>