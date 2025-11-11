<?php $this->load->view('layout/header');  ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pharmacy</li>
                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title"><b>Pharmacy List</b></h3>
                                <div class="ml-auto">
                                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus mr-1"></i>Add Pharmacy</button>
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
                                            <th>Medicine Name</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Cost Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Medicine Name</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Cost Price</th>
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
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(document).on('click', '#close', function() {
            $('#example1 tbody').empty();
            $('#example3 tbody').empty();
            $('#file').val('');
        });

        $('#submitform').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?= base_url('pharmacy/add_pharmacy') ?>",
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-lg').modal('hide');
                        $('#submitform')[0].reset();
                        $('.modal-backdrop').remove();
                        table.ajax.reload();
                        showAlert('success', response.message);
                    } else {
                        showAlert('error', 'Error Adding Pharmacy');
                    }
                },
                error: function(err) {
                    console.log(err);
                    showAlert('error', 'Error while adding');
                }
            })
        });

        let table = $('#example2').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 7] // Disable sorting for the first and fifth columns
            }],
            "order": [],
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "<?= base_url('pharmacy/fetch_data') ?>",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,

                    "render": function(data, type, row) {
                        return `<input type="checkbox" class="row-select" value="${row.id}">`;
                    }
                }, {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "medicine_name"
                },
                {
                    "data": "description"
                },
                {
                    "data": "quantity"
                },
                {
                    "data": "unit_price"
                },
                {
                    "data": "cost_price"
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
                showAlert('error', 'No Rows are selected !');
                return;
            }

            $.ajax({
                url: '<?= base_url('pharmacy/export_csv') ?>',
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
                    link.download = "pharmacy_records.csv";
                    link.click();
                }
            })
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "<?= base_url('pharmacy/delete_pharmacy') ?>/" + id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {
                        swal.fire(response.message);
                        table.ajax.reload();
                    } else {
                        shoeAlert("not delete ");
                    }
                },
                error: function(err) {
                    console.log(err);
                    swal.fire("error", "Error while deleting");
                }

            });
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "<?= base_url("pharmacy/get_pharmacy/") ?>" + id,
                method: "POST",
                dataType: "json",
                success: function(response) {
                    $('#editModal #name').val(response.medicine_name);
                    $('#editModal #description').val(response.description);
                    $('#editModal #quantity').val(response.quantity);
                    $('#editModal #unit_price').val(response.unit_price);
                    $('#editModal #cost_price').val(response.cost_price);
                },
                error: function(err) {
                    console.log(err);
                    showAlert('Error fetching patient data');
                }
            })
        });

        $("#updateform").on("submit", function(e) {
            e.preventDefault();
            var id = $('.edit-btn').data('id');

            $.ajax({
                url: "<?= base_url('pharmacy/update_pharmacy/') ?>" + id,
                method: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#editModal').modal('hide');
                        $('#updateform')[0].reset();
                        swal.fire(response.message);
                        $('.modal-backdrop').remove();
                        table.ajax.reload();
                    } else {
                        showAlert('Error Updating Pharmacy');
                    }
                },
                error: function(err) {
                    console.log(err);
                    showAlert('Something Went Wrong !');
                }
            });

        });

        $('#importform').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Importing Patients...',
                text: 'Please wait while we import your data.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            var file_data = $('#file').prop('files')[0];
            var formData = new FormData();
            formData.append('file', file_data);
            formData.append('override', $('#override').is(':checked') ? 1 : 0);

            $.ajax({
                url: '<?= base_url('pharmacy/import_csv') ?>',
                method: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        setTimeout(function() {
                            Swal.close();

                            Swal.fire(response.message);
                            $('#importModal').modal('hide');
                            $('.modal-backdrop').remove();
                            table.ajax.reload();
                            $('#importform')[0].reset();
                            $('#example1 tbody').html('');
                            $('#example3 tbody').html('');
                        }, 2000);
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire('Error', 'Server error', 'error');
                }
            });
        });

        let csvdata = [];
        $(document).on('change', '#file', function() {
            var file_data = $('#file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: '<?= base_url('pharmacy/upload_csv') ?>',
                method: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    if (response.status == 'success') {
                        swal.fire(response.message);
                        csvdata = response.data;

                        var tbody = '';
                        $.each(response.matched_csv_data, function(index, pharmacy) {
                            var systemPharmacy = response.matched_system_data[index]; // matched DB row

                            tbody += `<tr>
                                        <td${pharmacy.id != systemPharmacy.id ? ' class="bg-pink"' : ''}>${pharmacy.id}</td>
                                        <td${pharmacy.medicine_name != systemPharmacy.medicine_name ? ' class="bg-pink"' : ''}>${pharmacy.medicine_name}</td>
                                        <td${pharmacy.description != systemPharmacy.description ? ' class="bg-pink"' : ''}>${pharmacy.description}</td>
                                        <td${pharmacy.quantity != systemPharmacy.quantity ? ' class="bg-pink"' : ''}>${pharmacy.quantity}</td>
                                        <td${pharmacy.unit_price != systemPharmacy.unit_price ? ' class="bg-pink"' : ''}>${pharmacy.unit_price}</td>
                                        <td${pharmacy.cost_price != systemPharmacy.cost_price ? ' class="bg-pink"' : ''}>${pharmacy.cost_price}</td>
                                    </tr>`;
                        });

                        $('#example1 tbody').html(tbody);


                        var tbody = '';
                        $.each(response.matched_system_data, function(index, pharmacy) {
                            var csvPharmacy = response.matched_csv_data[index];
                            tbody += `<tr>
                                <td${pharmacy.id !== csvPharmacy.id ? ' class="bg-pink"' : ''}>${pharmacy.id}</td>
                                <td${pharmacy.medicine_name !== csvPharmacy.medicine_name ? ' class="bg-pink"' : ''}>${pharmacy.medicine_name}</td>
                                <td${pharmacy.description !== csvPharmacy.description ? ' class="bg-pink"' : ''}>${pharmacy.description}</td>
                                <td${pharmacy.quantity !== csvPharmacy.quantity ? ' class="bg-pink"' : ''}>${pharmacy.quantity}</td>
                                <td${pharmacy.unit_price !== csvPharmacy.unit_price ? ' class="bg-pink"' : ''}>${pharmacy.unit_price}</td>
                                <td${pharmacy.cost_price !== csvPharmacy.cost_price ? ' class="bg-pink"' : ''}>${pharmacy.cost_price}</td>
                            </tr>`
                        })
                        $('#example3 tbody').html(tbody);
                    }
                }
            })
        })
    });
</script>




<?php $this->load->view('layout/footer');  ?>


<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="submitform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Pharmacy</h4>
                    <button type="submit" class="btn btn-warning">Add Pharmacy</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Pharmacy Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter Quantity">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit_price" class="col-sm-3 col-form-label">Unit Price</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="unit_price" id="unit_price">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cost_price" class="col-sm-3 col-form-label">Cost Price</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="cost_price" id="cost_price">
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


<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="updateform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Pharmacy</h4>
                    <button type="submit" class="btn btn-warning">Add Pharmacy</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Pharmacy Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter Quantity">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit_price" class="col-sm-3 col-form-label">Unit Price</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="unit_price" id="unit_price">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cost_price" class="col-sm-3 col-form-label">Cost Price</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="cost_price" id="cost_price">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="importform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Bulk Pharmacy Data</h4>
                    <button type="submit" class="btn btn-warning">Add Pharmacy</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Select File</label>
                                <div class="col-sm-5">
                                    <input type="file" name="file" id="file" placeholder="Choose File">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
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
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-bordered table-hover" id="example1">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Medicine Name</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Cost Price</th>
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
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-bordered table-hover" id="example3">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Id</th>
                                                <th>Medicine Name</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Cost Price</th>

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>