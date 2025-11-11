<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Purcahse</li>
                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title"><b>Purchase List</b></h3>
                                <div class="ml-auto">
                                    <a href="<?= base_url('purchase/add') ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus mr-1"></i>Add Purchase</a>
                                    <button id="export_selected" class="btn btn-sm btn-success float-right mr-3">
                                        <i class="fas fa-file-export mr-1"></i>Export CSV
                                    </button>
                                    <!-- <button class="btn btn-sm btn-secondary float-right mr-3" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import mr-1"></i>Import CSV</button> -->
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Supplier Name</th>
                                            <th>Purchase Date</th>
                                            <th>Payment Status</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Supplier Name</th>
                                            <th>Purchase Date</th>
                                            <th>Payment Status</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </div>
</div>


<!-- Add Supplier Modal -->
<div class="modal fade" id="addPurchaseModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="addPurchaseform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Purchase</h4>
                    <button type="submit" class="btn btn-warning">Add Purchase</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="supplier_id" class="col-sm-2 col-form-label">Supplier Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="supplier_id" id="supplier_id">
                                <option value="">Select Supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier->id ?>"><?= $supplier->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="purchase_date" class="col-sm-2 col-form-label">Purchase Date</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="purchase_date" id="purchase_date" placeholder="Enter Purchase Date">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="payment_status" class="col-sm-2 col-form-label">Payment Status</label>
                        <div class="col-sm-10">
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="">Select Payment Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="remarks" id="remarks">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<div class="modal fade" id="importModal">
    <div class="modal-dialog modal-xl">
        <form class="form-horizontal" enctype="multipart/form-data" id="importform">

            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Import Purchase</h4>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-warning float-right mr-2">Import Purchase</button>
                        <button class="btn btn-danger float-right" id="close" data-dismiss="modal">Close</button>
                    </div>

                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Select File</label>
                                <div class="col-sm-10">
                                    <input type="file" name="file" id="file" placeholder="Choose File">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <input type="checkbox" name="override" id="override" value="1" class="mr-2">
                                Override Existing Data
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-gray">
                                    <div class="card-title">Data from CSV File</div>
                                </div>
                                <div class="card-body table-responsive px-0">
                                    <table class="table table-bordered table-hover" id="example1">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Gst No</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <div class="card-title">Data from Database Table</div>
                                </div>
                                <div class="card-body table-responsive px-0">
                                    <table class="table table-bordered table-hover" id="example3">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Gst No</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<?php $this->load->view('layout/footer'); ?>





<div class="modal fade" id="editPurchasePModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="editPurchaseForm">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Editing Supplier</h4>
                    <button type="submit" class="btn btn-warning">Update Supplier</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="supplier_id" class="col-sm-2 col-form-label">Supplier Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="supplier_id" id="supplier_id">
                                <option value="">Select Supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier->id ?>"><?= $supplier->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="purchase_date" class="col-sm-2 col-form-label">Purchase Date</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="purchase_date" id="purchase_date" placeholder="Enter Purchase Date">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="payment_status" class="col-sm-2 col-form-label">Payment Status</label>
                        <div class="col-sm-10">
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="">Select Payment Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Partial">Partial</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="remarks" id="remarks">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>


<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 6] // Disable sorting for the first and fifth columns
            }],
            "order": [],
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "<?= base_url('Purchase/get_all_purchase') ?>",
                "type": "POST",
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `<input type="checkbox" class="row-select" value="${row.id}">`;
                    }
                },
                {
                    "data": "id"
                },
                {
                    "data": "supplier_name"
                },
                {
                    "data": "purchase_date"
                },
                {
                    "data": "payment_status",
                    "render": function(data, type, row) {
                        let badgeClass = '';
                        if (data === 'paid') {
                            badgeClass = 'badge badge-success';
                        } else if (data === 'pending') {
                            badgeClass = 'badge badge-warning';
                        } else if (data === 'partial') {
                            badgeClass = 'badge badge-info';
                        } else {
                            badgeClass = 'badge badge-secondary';
                        }

                        return `<span class="${badgeClass}">${data}</span>`;
                    }
                },
                {
                    "data": "remarks"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}"><i class="fas fa-trash mr-1"></i>Delete</button>`;
                    }
                }
            ]
        });

        $(document).on('change', '#select_all', function() {
            $('.row-select').prop('checked', $(this).prop('checked'));
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            window.location.href = '<?= base_url("purchase/edit_purchase/") ?>' + id;
        });


        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('purchase/delete_purchase/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        showAlert('success', response.message);
                        table.ajax.reload();
                    } else {
                        showAlert('error', 'Error While changing the status !');
                    }
                }
            });
        });

        // Export selected rows to CSV
        $('#export_selected').on('click', function(e) {
            e.preventDefault();

            swal.fire({
                title: 'Exporting File...',
                html: 'This may take a few minutes',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    swal.showLoading();
                }
            });

            let selectedIds = [];

            $('.row-select:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showAlert('error','No rows selected !');
                return;
            }

            $.ajax({
                url: "<?= base_url('purchase/export_selected') ?>",
                method: "POST",
                data: {
                    ids: selectedIds
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob) {
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "selected_purchases.csv";
                    link.click();
                }
            });
        });


        // $('#addPurchaseform').on('submit', function(e) {
        //     e.preventDefault();
        //     $.ajax({
        // url: '<?= base_url('Purchase/add_purchase') ?>',
        //         type: 'POST',
        //         dataType: 'json',
        //         data: $(this).serialize(),
        //         success: function(response) {
        //             if (response.status == 'success') {
        //                 $('#addPurchaseModal').modal('hide');
        //                 table.ajax.reload();
        //                 $('.modal-backdrop').remove();
        //                 swal.fire(
        //                     'Success',
        //                     response.message,
        //                     'success'
        //                 );
        //             } else {
        //                 showAlert(response.message, 'error');
        //             }
        //         }
        //     });
        // });
    });
</script>