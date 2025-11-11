<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Supplier</li>
                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title"><b>Supplier List</b></h3>
                                <div class="ml-auto">
                                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addSupplierModal"><i class="fas fa-plus mr-1"></i>Add Supplier</button>
                                    <button class="btn btn-sm btn-secondary float-right mr-3" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import mr-1"></i>Import CSV</button>
                                    <button class="btn btn-sm btn-success export_csv mr-3"><i class="fas fa-file-export mr-1"></i>Export Csv</button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>GST_NO</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>GST_NO</th>
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
<div class="modal fade" id="addSupplierModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="addSupplierform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Supplier</h4>
                    <button type="submit" class="btn btn-warning">Add Supplier</button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Patient Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                        <div class="col-sm-10">
                            <input type="tel" class="form-control" name="contact" id="contact" placeholder="Enter Contact Number">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="address" id="address" placeholder="Enter Address">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gst_no" class="col-sm-2 col-form-label">GST NO</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="gst_no" id="gst_no" placeholder="Enter GST NO">
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
                    <h4 class="modal-title">Import Suppliers</h4>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-warning float-right mr-2">Import Supplier</button>
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

<script>
    $(document).ready(function() {

        $(document).on('click', '#close', function() {
            $('#example1 tbody').empty();
            $('#example3 tbody').empty();
            $('#file').val('');
        });

        var table = $('#example2').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 7] // Disable sorting for the first and fifth columns
            }],
            "order": [],
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "<?= base_url('Supplier/fetch_suppliers') ?>",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row) {
                        return `<input type="checkbox" class="row-select" value="${row.id}" >`;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "contact"
                },
                {
                    "data": "email"
                },
                {
                    "data": "address"
                },
                {
                    "data": "gst_no"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-sm btn-info edit-btn" data-id="${data.id}"  data-toggle="modal" data-target="#editSupplierModal"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}"><i class="fas fa-trash mr-1"></i>Delete</button>`;
                    }
                }
            ]
        });

        $(document).on('change', '#select_all', function() {
            $('.row-select').prop('checked', $(this).prop('checked'));
        });

        $('.export_csv').on('click', function() {
            let selectedIds = [];

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

            $('.row-select:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showAlert('error', 'No Row Selected !');
                return;
            }

            $.ajax({
                url: '<?= base_url('supplier/export_csv') ?>',
                method: 'POST',
                data: {
                    ids: selectedIds
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob) {
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "suppliers.csv";
                    link.click();
                }
            })
        });

        $('#addSupplierform').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url('Supplier/add_supplier') ?>',
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#addSupplierModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', ' ', response.message, 2000);
                        $('.modal-backdrop').remove();
                    } else {
                        showAlert('error', 'Error while adding supplier', response.message, 2000);
                    }
                },
                error: function(err) {
                    console.log(err);
                    swal.fire('An error occurred while adding supplier', 'error');
                }

            })
        })

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('supplier/delete_supplier/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        table.ajax.reload();
                        showAlert('success', 'Deleted', response.message, 2000);
                    } else {
                        showAlert('error', 'Delete Failed', response.message, 2000);
                    }
                }
            })
        })

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');

            $('#editform').data('edit-id', id);

            $.ajax({
                url: '<?= base_url('supplier/fetch_supplier/') ?>' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#editname').val(response.name);
                    $('#editcontact').val(response.contact);
                    $('#editemail').val(response.email);
                    $('#editaddress').val(response.address);
                    $('#editgst_no').val(response.gst_no);
                }
            })
        })

        $('#editform').on('submit', function(e) {
            e.preventDefault();
            var id = $(this).data('edit-id');

            $.ajax({
                url: '<?= base_url('supplier/update_supplier/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editSupplierModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', 'Updated', response.message, 2000);
                        $('.modal-backdrop').remove();
                    } else {
                        showAlert('error', 'Update Failed', response.message, 2000);
                    }
                }
            })
        })

        let csvdata = [];
        $(document).on('change', '#file', function() {

            var form_data = new FormData();
            var file_data = $('#file').prop('files')[0];
            form_data.append('file', file_data);

            $.ajax({
                url: '<?= base_url('supplier/upload_csv') ?>',
                method: 'POST',
                dataType: 'json',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        showAlert('success', '', response.message, 2000);
                        csvdata = response.data;
                        var tbody = '';
                        $.each(response.matched_csv_data, function(index, item) {
                            var system_fetched_data = response.matched_system_data[index];
                            tbody += `<tr>
                                <td>${item.id}</td>
                                <td>${item.name}</td>
                                <td>${item.contact}</td>
                                <td>${item.email}</td>
                                <td>${item.address}</td>
                                <td>${item.gst_no}</td>
                            </tr>`;
                        });
                        $('#example1 tbody').html(tbody);

                        var tbody = '';
                        $.each(response.matched_system_data, function(index, item) {
                            tbody += `<tr>
                                <td>${item.id}</td>
                                <td>${item.name}</td>
                                <td>${item.contact}</td>
                                <td>${item.email}</td>
                                <td>${item.address}</td>
                                <td>${item.gst_no}</td>
                            </tr>`;
                        });
                        $('#example3 tbody').html(tbody);
                    }
                }
            });

        });

        $('#importform').on('submit', function(e) {
            e.preventDefault();
            var form_data = new FormData();
            var override = $('#override').is(':checked') ? 1 : 0;
            form_data.append('override', override);
            var file_data = $('#file').prop('files')[0];
            form_data.append('file', file_data);

            $.ajax({
                url: '<?= base_url('supplier/import_csv') ?>',
                method: 'POST',
                dataType: 'json',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#importModal').modal('hide');
                        table.ajax.reload();
                        showAlert('success', '', response.message, 2000);
                        $('.modal-backdrop').remove();
                    } else {
                        showAlert('error', 'Import Failed', response.message, 2000);
                    }
                }
            });

        });
    });
</script>



<div class="modal fade" id="editSupplierModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="editform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Editing Supplier</h4>
                    <button type="submit" class="btn btn-warning">Update Supplier</button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="editname" id="editname" placeholder="Enter Patient Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                        <div class="col-sm-10">
                            <input type="tel" class="form-control" name="editcontact" id="editcontact" placeholder="Enter Contact Number">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="editemail" id="editemail" placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="editaddress" id="editaddress" placeholder="Enter Address">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gst_no" class="col-sm-2 col-form-label">GST NO</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="editgst_no" id="editgst_no" placeholder="Enter GST NO">
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