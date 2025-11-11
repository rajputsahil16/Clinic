<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Patient</li>
                        </ol>
                    </div><!-- /.col -->
                </div>

                <div class="row ">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title align-items-center">Patient List</h3>
                                <div class="ml-auto">
                                    <button class="btn btn-sm btn-primary align-right" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus mr-1"></i>Add Patient</button>
                                    <button class="btn btn-sm btn-secondary align-right" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import mr-1"></i>Import CSV</button>
                                    <button id="export_btn" class="btn btn-sm btn-success"> <i class="fas fa-file-export mr-1"></i>Export Csv</button>
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
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Blood Group</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- loading spinner -->
                                        <div class="ajax-loader" id="loading-overlay">
                                            <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i></div>
                                        </div>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Blood Group</th>
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


<!-- Add Patient Modal -->
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="submitform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Adding Patient</h4>
                    <button type="submit" class="btn btn-success">Add Patient</button>
                </div>
                <div class="modal-body">


                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Patient Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <?php foreach (get_enum_values('patients', 'gender') as $value): ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="age" class="col-sm-2 col-form-label">Age</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="age" id="age" placeholder="Enter Your Age">
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
                        <label for="blood_group" class="col-sm-2 col-form-label">Blood Group</label>
                        <div class="col-sm-10">
                            <select name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <?php foreach (get_enum_values('patients', 'blood_group') as $value): ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
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
                    <h4 class="modal-title">Import Patients</h4>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success float-right mr-2">Import Patient</button>
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
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Blood Group</th>
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
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Blood Group</th>

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
</div>



<?php $this->load->view('layout/footer'); ?>

<script>
    $(document).ready(function() {

        $(document).on('click', '#close', function() {
            $('#example1 tbody').empty();
            $('#example3 tbody').empty();
            $('#file').val('');
        });

        let table = $('#example2').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 8] // Disable sorting for the first and fifth columns
            }],
            "order": [],
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "<?= base_url('patient/fetch_patients') ?>",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [{
                    "data": null,
                    "orderable": false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="row-select" value="${row.id}">`;
                    }
                },
                {
                    // Serial number column
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "gender"
                },
                {
                    "data": "age"
                },
                {
                    "data": "contact"
                },
                {
                    "data": "email"
                },
                {
                    "data": "blood_group"
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

        $('#importform').on('submit', function(e) {
            e.preventDefault();

            // Collect patient data from the CSV preview table
            var patientArray = [];
            $('#example1 tbody tr').each(function() {
                var $tds = $(this).find('td');
                patientArray.push({
                    id: $tds.eq(0).text(),
                    name: $tds.eq(1).text(),
                    gender: $tds.eq(2).text(),
                    age: $tds.eq(3).text(),
                    contact: $tds.eq(4).text(),
                    email: $tds.eq(5).text(),
                    blood_group: $tds.eq(6).text()
                });
            });

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
                url: '<?= base_url('patient/import_csv') ?>',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {

                        setTimeout(function() {
                            Swal.close();
                            Swal.fire(response.message);
                            $('#example2').DataTable().ajax.reload();
                            $('#importform')[0].reset();
                            $('#example1 tbody').html('');
                            $('#example3 tbody').html('');
                        }, 2000);


                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(err) {
                    Swal.fire('Error', 'Server error', 'error');
                }
            });
        });



        let uploadedCsvData = [];

        $(document).on('change', '#file', function() {
            var file_data = $('#file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.ajax({
                url: '<?= base_url('patient/upload_csv') ?>',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {

                    if (response.status === 'success') {
                        Swal.fire(response.message);

                        // 👇 Store all CSV rows globally
                        uploadedCsvData = response.data;

                        var tbody = '';
                        $.each(response.matched_csv_data, function(index, patient) {
                            var systemPatient = response.matched_system_data[index];
                            tbody += `<tr>
                                <td${patient.id !== systemPatient.id ? ' class="bg-pink"' : ''}>${patient.id}</td>
                                <td${patient.name !== systemPatient.name ? ' class="bg-pink"' : ''}>${patient.name}</td>
                                <td${patient.gender !== systemPatient.gender ? ' class="bg-pink"' : ''}>${patient.gender}</td>
                                <td${patient.age !== systemPatient.age ? ' class="bg-pink"' : ''}>${patient.age}</td>
                                <td${patient.contact !== systemPatient.contact ? ' class="bg-pink"' : ''}>${patient.contact}</td>
                                <td${patient.email !== systemPatient.email ? ' class="bg-pink"' : ''}>${patient.email}</td>
                                <td${patient.blood_group !== systemPatient.blood_group ? ' class="bg-pink"' : ''}>${patient.blood_group}</td>
                            </tr>`;
                        });
                        $('#example1 tbody').html(tbody);

                        var tbody = '';
                        $.each(response.matched_system_data, function(index, patient) {
                            var csvPatient = response.matched_csv_data[index];
                            tbody += `<tr>
                                <td${patient.id !== csvPatient.id ? ' class="bg-pink"' : ''}>${patient.id}</td>
                                <td${patient.name !== csvPatient.name ? ' class="bg-pink"' : ''}>${patient.name}</td>
                                <td${patient.gender !== csvPatient.gender ? ' class="bg-pink"' : ''}>${patient.gender}</td>
                                <td${patient.age !== csvPatient.age ? ' class="bg-pink"' : ''}>${patient.age}</td>
                                <td${patient.contact !== csvPatient.contact ? ' class="bg-pink"' : ''}>${patient.contact}</td>
                                <td${patient.email !== csvPatient.email ? ' class="bg-pink"' : ''}>${patient.email}</td>
                                <td${patient.blood_group !== csvPatient.blood_group ? ' class="bg-pink"' : ''}>${patient.blood_group}</td>
                            </tr>`;
                        });
                        $('#example3 tbody').html(tbody);
                    } else {
                        showAlert(response.message);
                    }
                }
            });



        });

        $('#submitform').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('patient/add_patient') ?>',
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-lg').modal('hide');
                        $('#submitform')[0].reset();
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert('Error adding patient');
                    }
                },
                error: function(err) {
                    console.log(err);
                    alert('Error adding patient');

                }
            });
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('patient/get_patient/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#editModal #name').val(data.name);
                    $('#editModal select[name="gender"]').val(data.gender);
                    $('#editModal #age').val(data.age);
                    $('#editModal #contact').val(data.contact);
                    $('#editModal #email').val(data.email);
                    $('#editModal select[name="blood_group"]').val(data.blood_group);
                    $('#editModal').data('id', id); // Store the patient ID in the modal
                },
                error: function(err) {
                    console.log(err);
                    alert('Error fetching patient data');
                }
            });
        });

        $('#updateform').on('submit', function(e) {
            e.preventDefault();
            var id = $('#editModal').data('id');
            $.ajax({
                url: '<?= base_url('patient/update_patient/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editModal').modal('hide');
                        $('#updateform')[0].reset();
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        $('.modal-backdrop').remove();
                        table.ajax.reload();
                    } else {
                        alert('Error updating patient');
                    }
                },
                error: function(err) {
                    console.log(err);
                    alert('Error updating patient');

                }
            })
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('patient/delete_patient/') ?>' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert('Error deleting patient');
                    }
                },
                error: function(err) {
                    console.log(err);
                    alert('Error deleting patient');

                }
            })
        });

        $('#export_btn').on('click', function(e) {
            e.preventDefault();
            let selectedIds = [];

            Swal.fire({
                title: 'Exporting File...',
                html: 'Please wait, your file is being prepared. <br> This may take a few moments.',
                allowOutsideClick: false, 
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true, 
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            $('.row-select:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showAlert('error', 'Please select atleast 1 row !');
                return;
            }

            $.ajax({
                url: "<?= base_url('patient/export_csv') ?>",
                method: "POST",
                data: {
                    ids: selectedIds
                },
                xhrFields: {
                    responseType: 'blob'
                },
                // beforeSend: function() {
                //     // Show the loading overlay
                //     $('#loading-overlay').show();
                // },
                success: function(blob) {
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "patient_records.csv";
                    link.click();
                }
                // complete: function() {
                //     // Hide the loading overlay after the request is complete
                //     $('#loading-overlay').hide();
                // }
            });
        });
    });
</script>



<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="updateform">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Editing Patient</h4>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Patient Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <?php foreach (get_enum_values('patients', 'gender') as $value): ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-sm-2 col-form-label">Age</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="age" id="age" placeholder="Enter Age">
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
                        <label for="blood_group" class="col-sm-2 col-form-label">Blood Group</label>
                        <div class="col-sm-10">
                            <select name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <?php foreach (get_enum_values('patients', 'blood_group') as $value): ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
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