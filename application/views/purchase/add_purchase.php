<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb bg-white p-2">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item Active"><a href="<?= base_url('purchase/index') ?>">Purchase</a></li>
                            <li class="breadcrumb-item active">Purchase Add</li>

                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center bg-orange">
                                <h3 class="card-title text-white"><b>Add Purchase</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form class="form-horizontal" enctype="multipart/form-data" id="addPurchaseform">

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="supplier_id" class="col-form-label">Supplier Name</label>
                                                <select class="form-control" name="supplier_id" id="supplier_id">
                                                    <option value="">Select Supplier</option>
                                                    <?php foreach ($suppliers as $supplier): ?>
                                                        <option value="<?= $supplier->id ?>"><?= $supplier->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="purchase_date" class="col-form-label">Purchase Date</label>
                                                <input type="date" class="form-control" name="purchase_date" id="purchase_date" placeholder="Enter Purchase Date">

                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="payment_status" class="col-form-label">Payment Status</label>
                                                <select name="payment_status" id="payment_status" class="form-control">
                                                    <option value="">Select Payment Status</option>
                                                    <option value="Paid">Paid</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Partial">Partial</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group row">
                                                <label for="remarks" class="col-form-label">Remarks</label>
                                                <input class="form-control" type="text" name="remarks" id="remarks" pkaceholder="Enter Remarks">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" class="form-control" id="medicine_search" placeholder="Search Medicine to add in purchase">
                                            <div id="medicine_list">
                                                <ul class="list-group">
                                                    <!-- Dynamic Medicine List will be appended here -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h3 class="card-title"><b>Items</b></h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <table id="itemTable" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th><i class="far fa-trash-alt"></i></th>
                                                                <th>Service Description </th>
                                                                <th>Ordered qty </th>
                                                                <th>Total qty</th>
                                                                <th>Cost Price</th>
                                                                <th>MRP</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="6" class="text-right">Total Amount :</th>
                                                                <th id="total-price"></th>
                                                                <input type="hidden" name="grand_total" id="grand_total" value="">

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

                                    <div class="card-footer">
                                        <input type="hidden" name="grand_total" id="grand_total" value="">
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
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




<?php $this->load->view('layout/footer'); ?>

<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('Purchase/get_all_purchase') ?>",
                "type": "POST",
                "dataSrc": ""
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "supplier_name"
                },
                {
                    "data": "purchase_date"
                },
                {
                    "data": "payment_status"
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



        $('#medicine_search').on('keyup', function() {
            var query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: '<?= base_url('Pharmacy/search_medicines') ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        var medicineList = ' ';
                        $.each(response, function(index, item) {
                            medicineList += '<li class="list-group-item p-1 medicineListItem" data-id="' + item.id + '" style="background-color: #e6e66bff; cursor:pointer;">' + $('<div>').text(item.medicine_name).html() + '</li>';
                        });
                        $('#medicine_list ul').html(medicineList);
                    }
                });
            }
        });

        $(document).on('click', '.medicineListItem', function() {
            var medicinename = $(this).text();
            var medid = $(this).data('id');

            var tableBody = `<tr>
                <td><i class="fas fa-trash-alt text-red delete"></i></td>
                <td>${medicinename} <input type="hidden" class="medicine_id" value=${medid} ></td>
                <td><input type="number" class="form-control form-control-sm orderqty" name="orderqty" id="orderqty" value="1" ></td>
                <td><input type="number" class="form-control form-control-sm totalqty" name="totalqty" id="totalqty" value='1' readonly></td>
                <td><input type="number" class="form-control form-control-sm cost_price"  name="cost_price" id="cost_price" value="1" ></td>
                <td><input type="number" class="form-control form-control-sm mrp" name="mrp" id="mrp" value="1" ></td>
                <td><input type="number" class="form-control form-control-sm total" name="total" id="total" value="1" readonly></td>
               </tr>
            `;
            $('#itemTable tbody').append(tableBody);

            $('#medicine_search').val('');
            $('#medicine_list ul').empty();

            updateTotalAmount();
        });

        $(document).on('input', '.cost_price, .orderqty', function() {

            var row = $(this).closest('tr');
            let subtotal = 0;
            var qty = parseFloat(row.find('.orderqty').val());
            var cost_price = parseFloat(row.find('.cost_price').val());
            subtotal = qty * cost_price;


            row.find('.totalqty').val(qty);
            row.find('.total').val(subtotal);

            updateTotalAmount();

        });


        $(document).on('click', '.delete', function() {
            $(this).closest('tr').remove();
            updateTotalAmount();
        });

        $('#addPurchaseform').on('submit', function(e) {
            e.preventDefault();

            let items = [];
            $('#itemTable tbody tr').each(function() {
                let orderqty = parseFloat($(this).find('.orderqty').val());
                let cost = parseFloat($(this).find('.cost_price').val());

                items.push({
                    medicine_id: $(this).find('.medicine_id').val(),
                    orderqty: orderqty,
                    cost_price: cost,
                    total: orderqty * cost
                });

            });

            const payload = {
                supplier_id: $('#supplier_id').val(),
                purchase_date: $('#purchase_date').val(),
                payment_status: $('#payment_status').val(),
                remarks: $('#remarks').val(),
                items: items,
                total_amount: $('#grand_total').val()
            };

            $.ajax({
                url: '<?= base_url('purchase/add_purchase') ?>',
                method: 'POST',
                dataType: 'json',
                data: JSON.stringify(payload),
                success: function(response) {
                    if (response.status == 'success') {
                        showAlert('success', response.message);
                        window.location.href = '<?= base_url('purchase/index') ?>';
                    } else {
                        swal.fire('error', response.message);
                    }
                }
            })

        });

        function updateTotalAmount() {
            var totalSum = 0;
            $('.total').each(function() {
                var price = parseFloat($(this).val()) || 0;
                totalSum += price;
            });
            $('#total-price').text(totalSum.toFixed(2));
            $('#grand_total').val(totalSum.toFixed(2));
        }



    });
</script>