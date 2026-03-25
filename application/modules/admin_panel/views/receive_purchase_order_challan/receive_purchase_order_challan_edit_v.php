<?php
/**
 * Purchase Challan Receipt - Edit View
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Material Challan Receipt | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="edit Purchase Challan Order">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css" />

    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!--iCheck-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>

    <style>
    .hide { display: none; }
    .border-black-bottom { border-bottom: 1px dotted #000 }
    </style>
</head>

<body class="sticky-header">

    <section>
        <?php $this->load->view('components/left_sidebar'); ?>

        <div class="body-content" style="min-height: 1500px;">

            <?php $this->load->view('components/top_menu'); ?>

            <div class="page-head">
                <h3 class="m-b-less">Edit Material Challan Receipt</h3>
                <div class="state-information">
                    <ol class="breadcrumb m-b-less bg-less">
                        <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                        <li class="active"> Edit Material Challan Receipt </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper">

                <!-- Header Form -->
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Edit <?= $receive_purchase_order_details[0]->purchase_order_receive_bill_no ?>
                                <span class="tools pull-right">
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <form id="form_edit_receive_purchase_order" method="post"
                                    action="<?=base_url('admin/form-edit-receive-purchase-orderr')?>"
                                    class="cmxform form-horizontal tasi-form">

                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label for="purchase_order_receive_bill_no" class="control-label text-danger">Purchase Bill Number *</label>
                                            <input id="purchase_order_receive_bill_no"
                                                name="purchase_order_receive_bill_no"
                                                value="<?= $receive_purchase_order_details[0]->purchase_order_receive_bill_no ?>"
                                                type="text" placeholder="Purchase Receive Number"
                                                class="form-control round-input" />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="purchase_order_receive_date" class="control-label text-danger">Purchase Bill Date *</label>
                                            <input id="purchase_order_receive_date" name="purchase_order_receive_date"
                                                value="<?php echo date('Y-m-d', strtotime($receive_purchase_order_details[0]->purchase_order_receive_date)); ?>"
                                                type="date" placeholder="Purchase Receive Date"
                                                class="form-control round-input" />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="am_id_add" class="control-label text-danger">Select Supplier *</label>
                                            <input type="hidden" id="am_id_hidden" name="am_id_hidden"
                                                value="<?=$receive_purchase_order_details[0]->am_id?>"><br>
                                            <label value="<?=$receive_purchase_order_details[0]->am_id?>">
                                                <strong><?=$receive_purchase_order_details[0]->acc_master_name?></strong>
                                            </label>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="control-label text-danger">Status *</label><br />
                                            <input type="radio" name="status" id="enable" value="1"
                                                <?= ($receive_purchase_order_details[0]->status == 1) ? 'checked' : '' ?>
                                                required class="iCheck-square-green">
                                            <label for="enable" class="control-label">Enable</label>

                                            <input type="radio" name="status" id="disable" value="0"
                                                <?= ($receive_purchase_order_details[0]->status == 0) ? 'checked' : '' ?>
                                                required class="iCheck-square-red">
                                            <label for="disable" class="control-label">Disable</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-4">
                                            <button class="btn btn-success" type="submit">
                                                <i class="fa fa-refresh"> Update Material Challan Receipt</i>
                                            </button>
                                        </div>
                                    </div>

                                    <input type="hidden" id="purchase_order_receive_id" name="purchase_order_receive_id"
                                        class="hidden"
                                        value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                                </form>
                            </div>
                        </section>
                    </div>
                </div>


                <!-- Detail Tabs -->
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Material Challan Receipt details for
                                <?= $receive_purchase_order_details[0]->purchase_order_receive_bill_no ?>
                                <span class="tools pull-right">
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">

                                <ul id="purchase_order_tabs" class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#supp_po_list" data-toggle="tab">List</a></li>
                                    <li><a href="#supp_po_add" data-toggle="tab">Add</a></li>
                                    <li id="supp_po_details_edit_tab" class="disabled">
                                        <a href="#po_details_edit" data-toggle="">Edit</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <img id="pod_edit_loader" class="hidden" style="display:block; margin: auto"
                                        src="<?= base_url('assets/img/ellipsis.gif') ?>" alt="" />

                                    <!-- LIST TAB -->
                                    <div id="supp_po_list" class="tab-pane fade in active">
                                        <table id="supp_po_details_table" class="table data-table dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Po. Num.</th>
                                                    <th>Supp. Po. Num.</th>
                                                    <th>Item Name</th>
                                                    <th>Color</th>
                                                    <th>Qnty</th>
                                                    <th>Rcv. Dt.</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- ADD TAB -->
                                    <div id="supp_po_add" class="tab-pane fade">
                                        <br />
                                        <div class="form">
                                            <form id="form_add_receive_purchase_challan_order_details" method="post"
                                                action="<?=base_url('admin/form-add-receive-purchase-challan-order-details')?>"
                                                class="cmxform form-horizontal tasi-form">

                                                <div class="form-group">
                                                    <div class="col-lg-4">
                                                        <label for="po_id" class="control-label text-danger">Purchase Order</label>
                                                        <select id="po_id" name="po_id" class="select2 form-control round-input">
                                                            <option value="">Select Purchase Order</option>
                                                            <?php foreach($purchase_order as $val) { ?>
                                                            <option value="<?=$val['po_id']?>"><?=$val['po_number']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="rcv_date_detail" class="control-label text-danger">Receive Date *</label>
                                                        <input type="date" id="rcv_date_detail" name="rcv_date_detail"
                                                            value="<?php echo date('Y-m-d', strtotime($receive_purchase_order_details[0]->purchase_order_receive_date)); ?>"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="sup_id" class="control-label text-danger">Supp.Purchase Order</label>
                                                        <select id="sup_id" name="sup_id" class="select2 form-control round-input">
                                                            <option value="">Select Supp.Purchase Order</option>
                                                            <?php foreach($supp_purchase_order as $val) { ?>
                                                            <option value="<?=$val['sup_id']?>"><?=$val['supp_po_number']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-lg-4">
                                                        <label for="id_id_add" class="control-label text-danger">Item *</label>
                                                        <select id="id_id_add" name="id_id_add" required
                                                            class="select2 form-control round-input">
                                                            <option value="">Select Item</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <label for="color_add" class="control-label text-danger">Colour *</label>
                                                        <input type="text" id="color_add" name="color_add" required
                                                            class="form-control" readonly />
                                                    </div>

                                                    <div class="col-lg-1 border-black-bottom">
                                                        <label for="pod_unit_add" class="control-label">Unit</label><br />
                                                        <label id="pod_unit_add"></label>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pod_quantity_add" class="control-label text-danger">Quantity *</label>
                                                        <input type="number" step="0.01" id="pod_quantity_add"
                                                            name="pod_quantity_add" required class="form-control" />
                                                        <!-- hidden: stores max allowed quantity from PO -->
                                                        <input type="hidden" id="pod_quantity_add_hidden"
                                                            name="pod_quantity_add_hidden" class="form-control" />
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="sup_pod_remarks" class="control-label">Remarks</label>
                                                        <input type="text" id="sup_pod_remarks" name="sup_pod_remarks"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-lg-offset-4 text-center">
                                                    <label class="control-label">&nbsp;</label><br>
                                                    <button type="submit" id="btn-show-content"
                                                        class="btn btn-info addon-btn m-b-10"> Add Details </button>
                                                </div>

                                                <input type="hidden" name="purchase_order_receive_id"
                                                    id="purchase_order_receive_id" class="hidden"
                                                    value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                                            </form>
                                        </div>
                                    </div>

                                    <!-- EDIT TAB -->
                                    <div id="po_details_edit" class="tab-pane">
                                        <br />
                                        <div class="form">
                                            <form id="form_edit_receive_purchase_challan_order_details" method="post"
                                                action="<?=base_url('admin/form-edit-receive-purchase-challan-order-details')?>"
                                                class="cmxform form-horizontal tasi-form">

                                                <div class="form-group">
                                                    <div class="col-lg-4">
                                                        <label for="po_id_edit" class="control-label text-danger">Purchase Order</label>
                                                        <select id="po_id_edit" name="po_id_edit"
                                                            class="select2 form-control round-input">
                                                            <option value="">Select Purchase Order</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="rcv_date_detail_edit" class="control-label">Receive Date</label>
                                                        <input type="date" id="rcv_date_detail_edit"
                                                            name="rcv_date_detail_edit" class="form-control" />
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="sup_id_edit" class="control-label text-danger">Supp.Purchase Order</label>
                                                        <select id="sup_id_edit" name="sup_id_edit"
                                                            class="select2 form-control round-input">
                                                            <option value="">Select Supp.Purchase Order</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="id_id_edit" class="control-label text-danger">Item *</label>
                                                        <select id="id_id_edit" name="id_id_edit" required
                                                            class="select2 form-control round-input">
                                                            <option value="">Select Item</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <label for="color_edit" class="control-label text-danger">Colour *</label>
                                                        <input type="text" id="color_edit" name="color_edit" required
                                                            class="form-control" readonly />
                                                    </div>

                                                    <div class="col-lg-1 border-black-bottom">
                                                        <label for="pod_unit_edit" class="control-label">Unit</label><br />
                                                        <label id="pod_unit_edit"></label>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pod_quantity_edit" class="control-label text-danger">Quantity *</label>
                                                        <input type="number" step="0.01" id="pod_quantity_edit"
                                                            name="pod_quantity_edit" required class="form-control" />
                                                        <!-- hidden: original saved quantity -->
                                                        <input type="hidden" id="pod_quantity_edit_hidden"
                                                            name="pod_quantity_edit_hidden" class="form-control" />
                                                        <!-- hidden: remaining PO quantity -->
                                                        <input type="hidden" id="remain_item_quantity"
                                                            name="remain_item_quantity" class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-lg-2">
                                                        <label for="sup_pod_remarks_edit" class="control-label">Remarks</label>
                                                        <input type="text" id="sup_pod_remarks_edit"
                                                            name="sup_pod_remarks_edit" class="form-control" />
                                                    </div>

                                                    <div class="col-lg-4 col-lg-offset-4">
                                                        <label class="control-label">&nbsp;</label><br>
                                                        <button class="btn btn-success"
                                                            style="margin: auto; display:block;" type="submit">
                                                            <i class="fa fa-plus"></i> Update details
                                                        </button>
                                                        <input type="hidden" id="purchase_order_receive_id"
                                                            name="purchase_order_receive_id" class="hidden"
                                                            value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                                                        <input type="hidden" name="purchase_order_receive_detail_id"
                                                            id="purchase_order_receive_detail_id" class="hidden"
                                                            value="" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>

            <?php $this->load->view('components/footer'); ?>

        </div>
    </section>


    <script src="<?=base_url()?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>
    <?php $this->load->view('components/_common_js'); ?>

    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/js/responsive.bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/admin_panel/js/data-table-init.js"></script>
    <script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
    <script>$('.select2').select2();</script>
    <script src="<?=base_url();?>assets/admin_panel/js/icheck/skins/icheck.min.js"></script>
    <script src="<?=base_url();?>assets/admin_panel/js/icheck-init.js"></script>
    <script src="<?=base_url();?>assets/admin_panel/js/jquery.validate.min.js"></script>
    <script src="<?=base_url();?>assets/admin_panel/js/jquery.form.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#supp_po_details_table').DataTable({
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('admin/ajax-receive-purchase-challan-order-details-table-data')?>",
                "type": "POST",
                "dataType": "json",
                data: {
                    purchase_order_receive_id: function() {
                        return $("#purchase_order_receive_id").val();
                    },
                },
            },
            "columns": [
                { "data": "po_number"     },
                { "data": "sup_po_number" },
                { "data": "item_name"     },
                { "data": "item_color"    },
                { "data": "item_qty"      },
                { "data": "receive_date"  },
                { "data": "action"        },
            ],
            "columnDefs": [{
                "targets": [1, 6],
                "orderable": false,
            }]
        });
    });


    // ── PO change → load supp orders + items (with pod_quantity) ──
    $("#po_id").change(function() {
        $po_id        = $(this).val();
        $am_id_hidden = $('#am_id_hidden').val();

        if ($po_id != '' || $po_id > 0) {
            $.ajax({
                url: "<?= base_url('admin/all-items-on-purchase-challan-order') ?>",
                method: "post",
                dataType: 'json',
                data: { po_id: $po_id, am_id_hidden: $am_id_hidden },
                success: function(result) {
                    var sup_po_orders = result.sup_po_orders;
                    $("#sup_id").html("<option value=''>Select Supp.Purchase order</option>");
                    $.each(sup_po_orders, function(index, item) {
                        $("#sup_id").append('<option value=' + item.sup_id + '> ' + item.supp_po_number + '</option>');
                    });

                    var all_items = result.all_items;
                    $("#id_id_add").html("<option value=''>Select Item</option>");
                    $.each(all_items, function(index, item) {
                        // pod_quantity stored as option attribute for max limit check
                        $("#id_id_add").append(
                            '<option value='      + item.id_id       +
                            ' pod_quantity='      + item.pod_quantity +
                            ' unit='              + item.unit        +
                            ' color='             + item.color       +
                            '>  '                 + item.item_name   +
                            ' [' + item.color + ']</option>'
                        );
                    });
                    $('#id_id_add').select2('open');
                },
                error: function(e) { console.log(e); }
            });
        } else {
            $("#id_id_add").val('').html("<option value=''>Select Item</option>");
            $('#color_add').val('');
            $("#pod_unit_add").html('');
            $('#pod_quantity_add').val('');
            $('#pod_quantity_add_hidden').val('');
        }
    });


    // ── Supp PO change → load items (with pod_quantity) ──
    $("#sup_id").change(function() {
        $sup_id = $(this).val();

        if ($sup_id != '' || $sup_id > 0) {
            $.ajax({
                url: "<?= base_url('admin/all-items-on-supp-purchase-order') ?>",
                method: "post",
                dataType: 'json',
                data: { 'sup_id': $sup_id },
                success: function(all_items) {
                    $("#id_id_add").html("<option value=''>Select Item</option>");
                    $.each(all_items, function(index, item) {
                        $("#id_id_add").append(
                            '<option value='      + item.id_id       +
                            ' pod_quantity='      + item.pod_quantity +
                            ' unit='              + item.unit        +
                            ' color='             + item.color       +
                            '>  '                 + item.item_name   +
                            ' [' + item.color + ']</option>'
                        );
                    });
                    $('#id_id_add').select2('open');
                },
                error: function(e) { console.log(e); }
            });
        } else {
            $("#id_id_add").val('').html("<option value=''>Select Item</option>");
            $('#color_add').val('');
            $("#pod_unit_add").html('');
            $('#pod_quantity_add').val('');
            $('#pod_quantity_add_hidden').val('');
        }
    });


    // ── Item (ADD) change → fill color, unit, max quantity hidden ──
    $(document).on('change', '#id_id_add', function() {
        $unit         = $('option:selected', this).attr('unit');
        $color        = $('option:selected', this).attr('color');
        $pod_quantity = $('option:selected', this).attr('pod_quantity');
    
        $('#color_add').val($color);
        $("#pod_unit_add").html("<b>" + $unit + '</b>');
        $('#pod_quantity_add_hidden').val($pod_quantity);  
        $('#pod_quantity_add').val($pod_quantity);          
    });
    
    // $(document).on('change', '#id_id_add', function() {
    // let remaining = parseFloat($('option:selected', this).attr('pod_quantity')) || 0;
    // let color     = $('option:selected', this).attr('color');
    // let unit      = $('option:selected', this).attr('unit');

    //     if (remaining === 0) {
    //         alert('This item is fully received. Remaining quantity is 0.');
    //         $(this).val('').trigger('change');
    //         $('#color_add').val('');
    //         $("#pod_unit_add").html('');
    //         $('#pod_quantity_add').val('');
    //         $('#pod_quantity_add_hidden').val('');
    //         return;
    //     }
    
    //     $('#color_add').val(color);
    //     $("#pod_unit_add").html("<b>" + unit + "</b>");
    //     $('#pod_quantity_add_hidden').val(remaining);
    //     $('#pod_quantity_add').val(remaining);
    // });



    // ── ADD quantity keyup → validate only (no financial calc) ──
    $("#pod_quantity_add").on('keyup', function() {
        let pod_quantity_add        = parseFloat($('#pod_quantity_add').val()) || 0;
        let pod_quantity_add_hidden = parseFloat($('#pod_quantity_add_hidden').val()) || 0;

        if (pod_quantity_add === 0) {
            alert('Quantity can not be zero');
            return false;
        } else if (pod_quantity_add > pod_quantity_add_hidden) {
            alert('Can not be greater than recommended value: ' + pod_quantity_add_hidden);
            return false;
        }
    });


    // ── EDIT quantity change → validate max limit ──
    $("#pod_quantity_edit").on('keyup', function() {
        let pod_quantity_edit        = parseFloat($('#pod_quantity_edit').val()) || 0;
        let pod_quantity_edit_hidden = parseFloat($('#pod_quantity_edit_hidden').val()) || 0;
        let remain_item_quantity     = parseFloat($('#remain_item_quantity').val()) || 0;
        let max_limit                = pod_quantity_edit_hidden + remain_item_quantity;

        if (pod_quantity_edit === 0) {
            alert('Quantity can not be zero');
            return false;
        } else if (pod_quantity_edit > max_limit) {
            alert('Quantity Maximum: ' + max_limit);
            return false;
        }
    });


    // ── Header form ──
    $("#form_edit_receive_purchase_order").validate({
        rules: {
            purchase_order_receive_date: { required: true }
        },
        messages: {}
    });
    $('#form_edit_receive_purchase_order').ajaxForm({
        beforeSubmit: function() {
            return $("#form_edit_receive_purchase_order").valid();
        },
        success: function(returnData) {
            notification(JSON.parse(returnData));
        }
    });


    // ── ADD form validation + submit ──
    $("#form_add_receive_purchase_challan_order_details").validate({
        rules: {
            id_id_add:        { required: true },
            pod_quantity_add: { required: true },
            rcv_date_detail:  { required: true }
        },
        messages: {}
    });
    $('#form_add_receive_purchase_challan_order_details').ajaxForm({
        beforeSubmit: function() {
            return $("#form_add_receive_purchase_challan_order_details").valid();
        },
        success: function(returnData) {
            obj = JSON.parse(returnData);
            notification(obj);
            $("#id_id_add").val(null).trigger("change");
            $("#color_add").val('');
            $("#pod_unit_add").html('');
            $("#pod_quantity_add").val('');
            $("#pod_quantity_add_hidden").val('');
            $("#sup_pod_remarks").val('');
            $('#supp_po_details_table').DataTable().ajax.reload();
        }
    });


    // ── Edit button click → populate EDIT tab ──
    $("#supp_po_details_table").on('click', '.purchase_order_receive_detail_id', function() {
        $("#pod_edit_loader").removeClass('hidden');
        $purchase_order_receive_detail_id = $(this).attr('purchase_order_receive_detail_id');

        $.ajax({
            url: "<?= base_url('admin/ajax-fetch-receive-purchase-challan-order-details-on-pk') ?>",
            method: "post",
            dataType: 'json',
            data: { 'purchase_order_receive_detail_id': $purchase_order_receive_detail_id },
            success: function(result) {
                $d = result.oreder_receive_details;

                $("#po_id_edit").html("<option>" + $d.po_number + "</option>").trigger('change');
                $("#sup_id_edit").html("<option>" + $d.supp_po_number + "</option>").trigger('change');
                $("#id_id_edit").html("<option>" + $d.item + "</option>").trigger('change');
                $("#pod_unit_edit").html('<b>' + $d.unit + '</b>');
                $("#color_edit").val($d.color);
                $("#pod_quantity_edit").val($d.item_quantity);
                $("#pod_quantity_edit_hidden").val($d.item_quantity);      // original saved qty
                $("#remain_item_quantity").val(result.remain_item_quantity); // remaining PO qty
                $("#sup_pod_remarks_edit").val($d.remarks);
                $("#purchase_order_receive_detail_id").val($d.purchase_order_receive_detail_id);
                $("#rcv_date_detail_edit").val($d.receive_date);

                $('#supp_po_details_edit_tab').removeClass('disabled');
                $('#supp_po_details_edit_tab').children("a").attr("data-toggle", 'tab');
                $('a[href="#po_details_edit"]').tab('show');
                $("#pod_edit_loader").addClass('hidden');
            }
        });
    });


    // ── EDIT form validation + submit ──
    $("#form_edit_receive_purchase_challan_order_details").validate({
        rules: {
            id_id_edit:        { required: true },
            pod_quantity_edit: { required: true }
        },
        messages: {}
    });
    $('#form_edit_receive_purchase_challan_order_details').ajaxForm({
        beforeSubmit: function() {
            return $("#form_edit_receive_purchase_challan_order_details").valid();
        },
        success: function(returnData) {
            obj = JSON.parse(returnData);
            notification(obj);
            $('#supp_po_details_table').DataTable().ajax.reload();
        }
    });


    // ── delete1 - detail row ──
    $(document).on('click', '.delete1', function() {
        $this = $(this);
        if (confirm("Are You Sure?")) {
            $.ajax({
                url: "<?= base_url('admin/del-receive-purchase-challan-order-details-list') ?>",
                dataType: 'json',
                type: 'POST',
                data: {
                    tab:               $(this).attr('tab'),
                    tab_pk:            $(this).attr('tab-pk'),
                    data_pk:           $(this).attr('data-pk'),
                    reference_tab:     $(this).attr('reference-tab'),
                    reference_pk:      $(this).attr('reference-pk'),
                    reference_data_pk: $(this).attr('reference-data-pk')
                },
                success: function(returnData) {
                    $this.closest('tr').remove();
                    notification(returnData);
                    $("#supp_po_details_table").DataTable().ajax.reload();
                },
                error: function(returnData) {
                    notification(JSON.parse(returnData));
                }
            });
        }
    });


    // ── delete - generic ──
    $(document).on('click', '.delete', function() {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: "<?= base_url('ajax-del-row-on-table-and-pk') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    tab:         $(this).attr('tab'),
                    pk_name:     $(this).attr('pk-name'),
                    pk_value:    $(this).attr('pk-value'),
                    child:       $(this).attr('child'),
                    ref_table:   $(this).attr('ref-table'),
                    ref_pk_name: $(this).attr('ref-pk-name')
                },
                success: function(returnData) { notification(returnData); },
                error: function(e, v) { console.log(e + v); }
            });
        }
    });


    function notification(obj) {
        toastr[obj.type](obj.msg, obj.title, {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "7000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });
    }

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>

</body>
</html>