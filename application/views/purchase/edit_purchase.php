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
                            <li class="breadcrumb-item active">Purchase edit</li>

                        </ol>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center bg-orange">
                                <h3 class="card-title text-white"><b>Edit Purchase</b></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form class="form-horizontal" enctype="multipart/form-data" id="editPurchaseform">

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="hidden" name="id" id="id" value="<?= $id ?>">
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
                                                    <option value="paid">Paid</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="partial">Partial</option>
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

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h3 class="card-title"><b>Items</b></h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <table id="purchaseItemsTable" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th><i class="far fa-trash-alt"></i></th>
                                                                <th>Medicine Name</th>
                                                                <th>Ordered qty </th>
                                                                <th>Total qty</th>
                                                                <th>Cost Price</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="5" class="text-right">Total Amount :</th>
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
                                        <button type="submit" class="btn btn-success">Update</button>
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

        var id = $('#id').val();
        var total = 0;

        $.ajax({
            url: '<?= base_url("purchase/get_purchase/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                if (response.purchase) {
                    $('#supplier_id').val(response.purchase.supplier_id);
                    $('#purchase_date').val(response.purchase.purchase_date);
                    $('#payment_status').val(response.purchase.payment_status);
                    $('#remarks').val(response.purchase.remarks);
                } else {
                    alert('Purchase not found.');
                }

                if (response.items && response.items.length > 0) {
                    let itemTableBody = '';
                    let grandTotal = 0;

                    response.items.forEach(function(item) {
                        const itemTotal = parseFloat(item.quantity) * parseFloat(item.cost_price);
                        grandTotal += itemTotal;

                        itemTableBody += `
                                        <tr data-item-id="${item.id}" data-medicine-id="${item.medicine_id}">
                                            <td><i class="far fa-trash-alt text-danger delete_btn"></i></td>
                                            <td>${item.medicine_name}</td>
                                            <td><input type="number" class="form-control orderqty" value="${item.quantity}"></td>
                                            <td><input type="number" class="form-control totalqty" value="${item.quantity}" readonly ></td>
                                            <td><input type="number" class="form-control cost_price" value="${item.cost_price}"></td>
                                            <td><input type="number" class="form-control total-price" value="${itemTotal.toFixed(2)}" readonly ></td>
                                        </tr>
                                        `;
                    });

                    $('#purchaseItemsTable tbody').html(itemTableBody);
                    $('#total-price').text(grandTotal.toFixed(2));
                }

            }
        });

        $(document).on('click', '.delete_btn', function() {

            var row = $(this).closest('tr');
            var id = row.data('item-id');

            $.ajax({
                url: '<?= base_url('purchase/delete_purchase/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        row.remove();

                        let grandTotal = 0;
                        if (row.length > 0) {
                            $('#purchaseItemsTable tbody tr').each(function() {
                                const total = parseFloat($(this).find('.total-price').val()) || 0;
                                grandTotal += total;
                            });
                        }

                        $('.total-price').val(grandTotal.toFixed(2));
                        $('#grand_total').val(grandTotal.toFixed(2));
                        $('#total-price').text(grandTotal.toFixed(2));

                    } else {
                        showAlert('error', 'Error Deleting Item');
                    }
                }
            })

        });



        $(document).on('input', '.orderqty , .cost_price', function() {
            let grandTotal = 0;

            $('#purchaseItemsTable tbody tr').each(function() {
                var qty = parseFloat($(this).find('.orderqty').val());
                const cost_price = parseFloat($(this).find('.cost_price').val());
                const total = qty * cost_price;

                $(this).find('.total').text(total.toFixed(2));
                $(this).find('.totalqty').val(qty)

                grandTotal += total;
            });

            $('.total-price').val(grandTotal.toFixed(2));
            $('#grand_total').val(grandTotal.toFixed(2));
            $('#total-price').text(grandTotal.toFixed(2));

        });


        $('#editPurchaseform').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            let items = [];
            $('#purchaseItemsTable tbody tr').each(function() {
                const item_id = $(this).data('item-id');
                const medicine_id = $(this).data('medicine-id');
                const orderqty = parseFloat($(this).find('.orderqty').val());
                const totalqty = parseFloat($(this).find('.totalqty').val());
                const cost_price = parseFloat($(this).find('.cost_price').val());
                const total_price = parseFloat($(this).find('.total-price').val());

                items.push({
                    id: item_id,
                    medicine_id: medicine_id,
                    orderqty: orderqty,
                    totalqty: totalqty,
                    cost_price: cost_price,
                    total: total_price
                });
            });

            const formData = {
                supplier_id: $('#supplier_id').val(),
                purchase_date: $('#purchase_date').val(),
                payment_status: $('#payment_status').val(),
                remarks: $('#remarks').val(),
                total_amount: $('#grand_total').val(),
                items: items
            };

            $.ajax({
                url: '<?= base_url('purchase/update_purchase/') ?>' + id,
                method: 'POST',
                dataType: 'json',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    if (response.status == 'success') {
                        showAlert('success', response.message);
                        setTimeout(() => {
                            window.location.href = '<?= base_url("purchase/index") ?>';
                        }, 1000);
                    } else {
                        showAlert('Error while Updating');
                    }
                },
                error: function() {
                    showAlert('error', 'Something went wrong!');
                }
            });
        });







    });
</script>