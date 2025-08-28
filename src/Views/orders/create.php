<?php ob_start(); ?>

<h1>Create New Order</h1>

<form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name *</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email">
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                    </div>
                    <div class="mb-3">
                        <label for="customer_address" class="form-label">Address</label>
                        <textarea class="form-control" id="customer_address" name="customer_address" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-select" id="material" name="material">
                                    <option value="">Select Material</option>
                                    <option value="PLA">PLA</option>
                                    <option value="ABS">ABS</option>
                                    <option value="PETG">PETG</option>
                                    <option value="TPU">TPU</option>
                                    <option value="WOOD">WOOD</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-3">
        <div class="card-header">
            <h5>File Uploads</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="design_file" class="form-label">3D Design File</label>
                        <input type="file" class="form-control" id="design_file" name="design_file" 
                               accept=".stl,.obj,.3mf,.gcode">
                        <small class="form-text text-muted">Supported formats: STL, OBJ, 3MF, GCODE</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_image" class="form-label">Reference Image</label>
                        <input type="file" class="form-control" id="reference_image" name="reference_image" 
                               accept="image/*">
                        <small class="form-text text-muted">Optional reference image</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Create Order</button>
        <a href="/" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php
$content = ob_get_clean();
$title = 'Create Order';
include '../layout.php';
?>