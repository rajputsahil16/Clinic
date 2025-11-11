<?php $this->load->view('layout/header');  ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Patient Records</li>
                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title"><b>Pharmacy List</b></h3>
                                <div class="ml-auto">
                                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addPatientRecordModal"><i class="fas fa-plus mr-1"></i>Add Patient Record</button>
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
                                            <th>Patient Name</th>
                                            <th>Visit Date</th>
                                            <th>Symptoms</th>
                                            <th>Diagnosis</th>
                                            <th>Prescription</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Patient Name</th>
                                            <th>Visit Date</th>
                                            <th>Symptoms</th>
                                            <th>Diagnosis</th>
                                            <th>Prescription</th>
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
        $('#submitform').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url('Patient_records/add_patient_records') ?>',
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#addPatientRecordModal').modal('hide');
                        $('#submitform')[0].reset();
                        $('.model-backdrop').remove();
                        table.ajax.reload();
                        showAlert('success', 'Success', response.message, 2000);
                    } else {
                        showAlert('error', 'Error Adding Record');
                    }
                }
            })
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
                "url": "<?= base_url('Patient_records/fetch_data') ?>",
                "type": "GET",
                "dataSrc": "data",
                "dataType": "json",
                "error": function(xhr, error, thrown) {
                    console.error('DataTable AJAX Error:', error, thrown);
                    console.error('Response:', xhr.responseText);
                }
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
                    "data": "patient_name"
                },
                {
                    "data": "visit_date"
                },
                {
                    "data": "symptoms"
                },
                {
                    "data": "diagnosis"
                },
                {
                    "data": "prescription"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}" data-toggle="modal" data-target="#editPatientRecordModal"><i class="fas fa-edit mr-1"></i>Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}"><i class="fas fa-trash mr-1"></i>Delete</button>`;
                    }
                }
            ]

        })

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
                showAlert('error', 'No Row selected !');
                return;
            }

            $.ajax({
                url: '<?= base_url('patient_records/export_csv') ?>',
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
                    link.download = "patient_records.csv";
                    link.click();
                }
            })
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this record?')) {
                $.ajax({
                    url: '<?= base_url('Patient_records/delete_patient_record/') ?>' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            table.ajax.reload();
                            showAlert('success', 'Deleted', response.message, 2000);
                        } else {
                            showAlert('error', 'Error', response.message);
                        }
                    }
                });
            }
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('Patient_records/get_patient_by_id/') ?>' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $('#editPatientRecordModal #patients').val(response.data.patient_id),
                            $('#editPatientRecordModal #visitdate').val(response.data.visit_date),
                            $('#editPatientRecordModal #symptoms').val(response.data.symptoms),
                            $('#editPatientRecordModal #diagnosis').val(response.data.diagnosis),
                            $('#editPatientRecordModal #prescription').val(response.data.prescription)

                        // $('#editPatientRecordModal').modal('show');
                    } else {
                        showAlert('error', 'Error', 'Error While Fetching Data');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            })

            $('#editform').on('submit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: '<?= base_url('Patient_records/update_patient_record/') ?>' + id,
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#editPatientRecordModal').modal('hide');
                            $('#editform')[0].reset();
                            $('.modal-backdrop').remove();
                            table.ajax.reload();
                            showAlert('success', 'Updated', response.message, 2000);
                        }
                    }
                })
            })
        })

        let csvdata = [];
        $(document).on('change', '#file', function() {
            var file_data = $('#file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: '<?= base_url('Patient_records/upload_csv') ?>',
                method: 'POST',
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    if (response.status == 'success') {
                        swal.fire(response.message);
                        csvdata = response.data;
                        var tbody = '';
                        $.each(response.matched_csv_data, function(index, item) {
                            var system_fetched_data = response.matched_system_data[index];

                            tbody += `<tr>
                                        <td ${item.id != system_fetched_data.id ? ' class="bg-pink"' : ''} >${item.id}</td>
                                        <td ${item.patient_name != system_fetched_data.patient_name ? ' class="bg-pink"' : ''} >${item.patient_name}</td>
                                        <td ${item.visit_date != system_fetched_data.visit_date ? ' class="bg-pink"' : ''} >${item.visit_date}</td>
                                        <td ${item.symptoms != system_fetched_data.symptoms ? ' class="bg-pink"' : ''} >${item.symptoms}</td>
                                        <td ${item.diagnosis != system_fetched_data.diagnosis ? ' class="bg-pink"' : ''} >${item.diagnosis}</td>
                                        <td ${item.prescription != system_fetched_data.prescription ? ' class="bg-pink"' : ''} >${item.prescription}</td>
                                      </tr>`;
                        });
                        $('#example1 tbody').html(tbody);

                        var tbody = '';
                        $.each(response.matched_system_data, function(index, item) {
                            var csv_patient_record = response.matched_csv_data[index];
                            tbody += `<tr>
                                            <td ${item.id != csv_patient_record.id ? ' class="bg-pink"' : ''} >${item.id || ''}</td>
                                            <td ${item.patient_name != csv_patient_record.patient_name ? ' class="bg-pink"' : ''} >${item.patient_name || ''}</td>
                                            <td ${item.visit_date != csv_patient_record.visit_date ? ' class="bg-pink"' : ''} >${item.visit_date || ''}</td>
                                            <td ${item.symptoms != csv_patient_record.symptoms ? ' class="bg-pink"' : ''} >${item.symptoms || ''}</td>
                                            <td ${item.diagnosis != csv_patient_record.diagnosis ? ' class="bg-pink"' : ''} >${item.diagnosis || ''}</td>
                                            <td ${item.prescription != csv_patient_record.prescription ? ' class="bg-pink"' : ''} >${item.prescription || ''}</td>
                                    </tr>`;
                        });
                        $('#example3 tbody').html(tbody);

                    }
                }

            })
        });

        $('#importform').on('submit', function(e) {
            e.preventDefault();
            var form_data = new FormData(this);
            form_data.append('override', $('#override').is(':checked') ? 1 : 0);
            $.ajax({
                url: '<?= base_url('Patient_records/import_csv') ?>',
                method: 'POST',
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#importModal').modal('hide');
                        $('#importform')[0].reset();
                        $('.model-backdrop').remove();
                        table.ajax.reload();
                        showAlert('success', 'Success', response.message, 2000);
                    } else {
                        showAlert('error', 'Error Importing Data');
                    }
                }
            })
        });

        $(document).on('click', '#close', function() {
            $('#example1 tbody').empty();
            $('#example3 tbody').empty();
            $('#file').val('');
        });
    });
</script>


<?php $this->load->view('layout/footer'); ?>


<!-- Add patient model -->
<div class="modal fade" id="addPatientRecordModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="submitform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Patient Record</h4>
                    <button type="submit" class="btn btn-warning">Add</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="patient" class="col-md-3 col-form-label">Select Patient</label>
                        <div class="col-md-9">
                            <select name="patients" id="patients" class="form-control" required>
                                <option value="">--Choose Patient--</option>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Visit Date</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="visitdate" id="visitdate">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="symptoms" class="col-sm-3 col-form-label">Symptoms</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="symptoms" id="symptoms">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Diagnosis" class="col-sm-3 col-form-label">Diagnosis</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="diagnosis" id="diagnosis">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prescription" class="col-sm-3 col-form-label">Prescription</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="prescription" id="prescription">
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

<!-- Edit patient model -->
<div class="modal fade" id="editPatientRecordModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="editform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Editing Patient Record</h4>
                    <button type="submit" class="btn btn-warning">Add</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="patient" class="col-md-3 col-form-label">Select Patient</label>
                        <div class="col-md-9">
                            <select name="patients" id="patients" class="form-control" required>
                                <option value="">--Choose Patient--</option>
                                <?php foreach ($patients as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Visit Date</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="visitdate" id="visitdate">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="symptoms" class="col-sm-3 col-form-label">Symptoms</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="symptoms" id="symptoms">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Diagnosis" class="col-sm-3 col-form-label">Diagnosis</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="diagnosis" id="diagnosis">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prescription" class="col-sm-3 col-form-label">Prescription</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="prescription" id="prescription">
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

<!-- Import Modal -->
<div class="modal fade" id="importModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="importform">
                <div class="modal-header bg-olive">
                    <h4 class="modal-title">Adding Bulk Pharmacy Data</h4>
                    <button type="submit" class="btn btn-warning">Add</button>
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
                                                <th>Patient Name</th>
                                                <th>Visit Date</th>
                                                <th>Symptoms</th>
                                                <th>Diagnosis</th>
                                                <th>Prescription</th>
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
                                                <th>Patient Name</th>
                                                <th>Visit Date</th>
                                                <th>Symptoms</th>
                                                <th>Diagnosis</th>
                                                <th>Prescription</th>

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