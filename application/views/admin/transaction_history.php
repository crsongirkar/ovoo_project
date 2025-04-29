<!-- <?php $this->load->model('subscription_model'); ?>
<div class="card">
    <div class="row">
        <div class="col-sm-12">
                <br>
                <br>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>###</th>
                            <th><?php echo trans("user");?></th>
                            <th><?php echo trans("payment_method");?></th>
                            <th><?php echo trans("amount");?></th>
                            <th><?php echo trans("payment time");?></th>
                            <th><?php echo trans("transaction_id");?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sl = 1;
                            foreach ($subscriptions as $subscription): 
                        ?>
                        <tr id='row_<?php echo $subscription['subscription_id'];?>'>
                            <td><?php echo $sl++;?></td>
                            <td><strong><?php echo $this->common_model->get_user_name_by_id($subscription['user_id']);?></strong></td>
                            <td><?php echo $subscription['payment_method'];?></td>
                            <td><?php echo $subscription['currency'].' '.$subscription['paid_amount'];?></td>
                            <td><?php echo date('Y-m-d H:i:s',$subscription['payment_timestamp']);?></td>                            
                            <td><a href="#" data-toggle="modal" data-target="#exampleModalCenter" class="transaction_details" data-id='<?php echo $subscription['subscription_id'];?>'><?php echo $subscription['transaction_id'];?></a></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

    <!-- Modal -->
<!-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo trans("transaction_details");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal-loader" style="display: none; text-align: center;"> <img src="<?php echo base_url(); ?>assets/images/preloader.gif" /> </div>
        <div id="dynamic-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans("close");?></button>
      </div>
    </div>
  </div>
</div> -->



<!-- <script>
    $(document).ready(function() {
        $(document).on('click', '.transaction_details', function(e) {
            e.preventDefault();
            var id  = $(this).data('id');
            var url = "<?php echo base_url('admin/get_transaction_details'); ?>";
            $('#dynamic-content').html('');
            $('#modal-loader').show();
            $.ajax({
                url: url,
                type: 'POST',
                data: {"subscription_id": id},
                dataType: 'html'
            })
            .done(function(data) {
                console.log(data);
                $('#dynamic-content').html('');
                $('#dynamic-content').html(data);
                $('#modal-loader').hide();
            })
            .fail(function() {
                $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $('#modal-loader').hide();
            });
        });
    });
</script>


<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();
    });
</script>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script> -->



<?php $this->load->model('subscription_model'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo trans("subscription_management"); ?></title>
     
    <style>
    .filter-controls {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .label {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
        display: inline-block;
    }
    .label-success {
        background-color: #5cb85c;
        color: white;
    }
    .label-info {
        background-color: #5bc0de;
        color: white;
    }
    .table-responsive {
        overflow-x: auto;
    }
    </style>

</head>
<body>
<div class="container-fluid">
    <div class="card">
        <div class="row">
            <div class="col-sm-12">
                <!-- Filter Controls -->
                <div class="row filter-controls">
                    <div class="col-md-3">
                        <label><?php echo trans("filter_by_payment"); ?></label>
                        <select class="form-control select2" id="payment-filter">
                            <option value="all"><?php echo trans("all_payments"); ?></option>
                            <option value="paid"><?php echo trans("paid_users"); ?></option>
                            <option value="free"><?php echo trans("free_users"); ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><?php echo trans("filter_by_method"); ?></label>
                        <select class="form-control select2" id="method-filter">
                            <option value="all"><?php echo trans("all_methods"); ?></option>
                            <option value="paypal">PayPal</option>
                            <option value="razorpay">Razorpay</option>
                            <option value="card">Card</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="free">Free</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><?php echo trans("filter_by_date"); ?></label>
                        <input type="text" class="form-control date-range" id="date-filter" placeholder="Select date range">
                    </div>
                    <div class="col-md-3" style="padding-top: 24px;">
                        <button class="btn btn-primary" id="apply-filter"><?php echo trans("apply_filter"); ?></button>
                        <button class="btn btn-default" id="reset-filter"><?php echo trans("reset"); ?></button>
                    </div>
                </div>
                
                <!-- Subscription Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="subscriptions-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo trans("user"); ?></th>
                                <th><?php echo trans("payment_method"); ?></th>
                                <th><?php echo trans("amount"); ?></th>
                                <th><?php echo trans("payment_time"); ?></th>
                                <th><?php echo trans("transaction_id"); ?></th>
                                <th><?php echo trans("status"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sl = 1;
                                foreach ($subscriptions as $subscription): 
                                    $is_paid = ($subscription['paid_amount'] > 0);
                                    $payment_type = $is_paid ? 'paid' : 'free';
                                    $payment_method = !empty($subscription['payment_method']) ? strtolower($subscription['payment_method']) : 'free';
                            ?>
                            <tr id='row_<?php echo $subscription['subscription_id']; ?>' 
                                data-payment-type="<?php echo $payment_type; ?>"
                                data-payment-method="<?php echo $payment_method; ?>"
                                data-payment-date="<?php echo date('Y-m-d', $subscription['payment_timestamp']); ?>">
                                <td><?php echo $sl++; ?></td>
                                <td><strong><?php echo $this->common_model->get_user_name_by_id($subscription['user_id']); ?></strong></td>
                                <td><?php echo $subscription['payment_method'] ?: trans('free'); ?></td>
                                <td><?php echo $is_paid ? $subscription['currency'].' '.$subscription['paid_amount'] : trans('free'); ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $subscription['payment_timestamp']); ?></td>
                                <td>
                                    <?php if (!empty($subscription['transaction_id'])): ?>
                                        <a href="#" data-toggle="modal" data-target="#exampleModalCenter" class="transaction_details" data-id='<?php echo $subscription['subscription_id']; ?>'>
                                            <?php echo $subscription['transaction_id']; ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo trans("no_transaction"); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_paid): ?>
                                        <span class="label label-success"><?php echo trans("paid"); ?></span>
                                    <?php else: ?>
                                        <span class="label label-info"><?php echo trans("free"); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><?php echo trans("transaction_details"); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-loader" style="display: none; text-align: center;">
                        <img src="<?php echo base_url(); ?>assets/images/preloader.gif" />
                    </div>
                    <div id="dynamic-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans("close"); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2();
    
    // Initialize date range picker
    $('.date-range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        }
    });
    
    $('.date-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    
    $('.date-range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    
    // Apply filter button click handler
    $('#apply-filter').click(function() {
        var paymentType = $('#payment-filter').val();
        var paymentMethod = $('#method-filter').val().toLowerCase();
        var dateRange = $('#date-filter').val();
        
        $('#subscriptions-table tbody tr').each(function() {
            var $row = $(this);
            var rowPaymentType = $row.data('payment-type');
            var rowPaymentMethod = $row.data('payment-method').toString().toLowerCase();
            var rowPaymentDate = $row.data('payment-date');
            var showRow = true;
            
            // Payment type filter
            if (paymentType !== 'all' && rowPaymentType !== paymentType) {
                showRow = false;
            }
            
            // Payment method filter
            if (paymentMethod !== 'all' && rowPaymentMethod !== paymentMethod) {
                showRow = false;
            }
            
            // Date range filter
            if (dateRange !== '') {
                try {
                    var dates = dateRange.split(' - ');
                    var startDate = new Date(dates[0]);
                    var endDate = new Date(dates[1]);
                    var rowDate = new Date(rowPaymentDate);
                    
                    // Normalize dates for comparison
                    startDate.setHours(0, 0, 0, 0);
                    endDate.setHours(23, 59, 59, 999);
                    rowDate.setHours(0, 0, 0, 0);
                    
                    if (rowDate < startDate || rowDate > endDate) {
                        showRow = false;
                    }
                } catch (e) {
                    console.error("Date parsing error:", e);
                }
            }
            
            // Toggle row visibility
            $row.toggle(showRow);
        });

    });
    $('#reset-filter').click(function() {
        $('#payment-filter').val('all').trigger('change');
        $('#method-filter').val('all').trigger('change');
        $('#date-filter').val('');
        $('#subscriptions-table tbody tr').show();
    });
    
    // Transaction details modal
    $(document).on('click', '.transaction_details', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?php echo base_url('admin/get_transaction_details'); ?>";
        $('#dynamic-content').html('');
        $('#modal-loader').show();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {"subscription_id": id},
            dataType: 'html'
        })
        .done(function(data) {
            $('#dynamic-content').html(data);
            $('#modal-loader').hide();
        })
        .fail(function() {
            $('#dynamic-content').html('<div class="alert alert-danger">Something went wrong, please try again...</div>');
            $('#modal-loader').hide();
        });
    });
    
    // Initialize parsley validation
    $('form').parsley();
});
</script>
</body>
</html>