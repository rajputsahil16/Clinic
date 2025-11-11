<?php $this->load->view('layout/header'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb bg-transparent">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active">Default Settings</li>

                    </ol>
                </div><!-- /.col -->
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Default Settings</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Update Enum Values</h3>
                        </div>
                        <form id="enumForm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="table_name">Select Table</label>
                                    <select class="form-control select2" id="table_name" name="table_name" required>
                                        <option value="">-- Select Table --</option>
                                        <?php foreach ($tables as $table): ?>
                                            <option value="<?= $table ?>"><?= $table ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="field_name">Select Enum Field</label>
                                    <select class="form-control select2" id="field_name" name="field_name" required>
                                        <option value="">-- Select Field --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="enum_values">Enum Values <small class="text-muted">(comma separated)</small></label>
                                    <input type="text" class="form-control" id="enum_values" name="enum_values" placeholder="e.g. Male , Female , Other" required>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </form>
                        <!-- <div id="result" class="px-3 pb-3"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {


        // When table changes, fetch enum fields
        $('#table_name').on('change', function() {
            var table = $(this).val();
            $('#field_name').html('<option value="">-- Select Field --</option>');
            if (table) {
                $.ajax({
                    url: "<?= base_url('settings/get_enum_fields') ?>",
                    method: "POST",
                    data: {
                        table: table
                    },
                    dataType: "json",
                    success: function(fields) {
                        $.each(fields, function(i, field) {
                            $('#field_name').append('<option value="' + field + '">' + field + '</option>');
                        });
                    }
                });
            }
        });

        // ...existing code...

        $('#field_name').on('change', function() {
            var table = $('#table_name').val();
            var field = $(this).val();
            $('#enum_values').val('');
            if (table && field) {
                $.ajax({
                    url: "<?= base_url('settings/get_enum_values') ?>",
                    method: "POST",
                    data: {
                        table: table,
                        field: field
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#enum_values').val(response.enum_values);
                    }
                });
            }
        });

        // ...existing code...

        $('#enumForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('settings/save_enum') ?>",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    // $('#result').html('<div class="alert alert-success">' + response.message + '</div>');
                    showAlert('success', 'Success!', response.message);
                    $('#enumForm')[0].reset();
                },
                error: function() {
                    showAlert('error', 'Error!', 'Error saving enum values.');
                }
            });
        });
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    })
</script>



<?php $this->load->view('layout/footer'); ?>