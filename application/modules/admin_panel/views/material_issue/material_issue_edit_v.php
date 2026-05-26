<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Material Issue | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="edit Purchase Order">

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <?php $this->load->view('components/_common_head'); ?>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; text-align: right; }
        input[type=number] { text-align: right; -moz-appearance: textfield; }
        .border-black-bottom { border-bottom: 1px dotted #000 }
    </style>
</head>

<body class="sticky-header">
<section>
    <?php $this->load->view('components/left_sidebar'); ?>
    <div class="body-content" style="min-height: 1500px;">
        <?php $this->load->view('components/top_menu'); ?>

        <div class="page-head">
            <h3 class="m-b-less">Edit Material Issue</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Edit Material Issue </li>
                </ol>
            </div>
        </div>

        <div class="wrapper">

            <!-- Header Form -->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Edit <?= $material_issue_data[0]->material_issue_slip_number ?>
                            <span class="tools pull-right"><a class="t-collapse fa fa-chevron-down" href="javascript:;"></a></span>
                        </header>
                        <div class="panel-body">
                            <form id="form_edit_receive_purchase_order" method="post" action="<?=base_url('admin/form-edit-material-issue')?>" class="cmxform form-horizontal tasi-form">
                                <div class="form-group">
                                    <div class="col-lg-3">
                                        <label for="material_issue_slip_number" class="control-label text-danger">Issue Slip Number*</label>
                                        <input id="material_issue_slip_number" name="material_issue_slip_number" value="<?= $material_issue_data[0]->material_issue_slip_number ?>" type="text" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="material_issue_date" class="control-label text-danger">Issue Date*:</label><br/>
                                        <input id="material_issue_date" name="material_issue_date" type="date" value="<?= $material_issue_data[0]->material_issue_date ?>" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="material_issue_to_form" class="control-label text-danger">Issue To / From*</label>
                                        <select id="material_issue_to_form" name="material_issue_to_form" class="form-control select2">
                                            <option value="">Issue To / From</option>
                                            <option value="1" <?php if($material_issue_data[0]->material_issue_to_form == '1'){ echo 'selected'; } ?>>Godown</option>
                                            <option value="2" <?php if($material_issue_data[0]->material_issue_to_form == '2'){ echo 'selected'; } ?>>Fabricator</option>
                                            <option value="3" <?php if($material_issue_data[0]->material_issue_to_form == '3'){ echo 'selected'; } ?>>Stock Out</option>
                                            <option value="4" <?php if($material_issue_data[0]->material_issue_to_form == '4'){ echo 'selected'; } ?>>Stock Return</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3" <?php if($material_issue_data[0]->jobber_challan_receipt_id == 0){ echo 'style="display:none"'; } ?> id="challan_div">
                                        <label for="jobber_challan_receipt_id" class="control-label text-danger">Challan No*</label>
                                        <select id="jobber_challan_receipt_id" name="jobber_challan_receipt_id" class="form-control select2">
                                            <option value="">Challan No</option>
                                            <?php foreach($jobber_challan_details as $jcd){
                                                foreach($jobber_challan_details1 as $jcd1) {
                                                    if($material_issue_data[0]->jobber_challan_receipt_id != $jcd->jobber_issue_id && $jcd->jobber_issue_id == $jcd1->jobber_challan_receipt_id) {
                                                        continue 2;
                                                    }
                                                }
                                            ?>
                                            <option value="<?= $jcd->jobber_issue_id ?>" <?php if($material_issue_data[0]->jobber_challan_receipt_id == $jcd->jobber_issue_id){ echo 'selected'; } ?>><?= $jcd->jobber_challan_number ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-3" <?php if($material_issue_data[0]->am_id == 0){ echo 'style="display:none"'; } ?> id="supplier_div">
                                        <label for="am_id" class="control-label text-danger">Supplier*</label>
                                        <select id="am_id" name="am_id" class="form-control select2">
                                            <option value="">Select Supplier</option>
                                            <?php foreach($buyer_details as $bd){ $sn = ($bd->short_name == '' ? '-' : $bd->short_name); ?>
                                            <option value="<?= $bd->am_id ?>" <?php if($material_issue_data[0]->am_id == $bd->am_id){ echo 'selected'; } ?>><?= $bd->name . ' [' . $sn . ']' ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="terms_condition" class="control-label">Terms and Conditions</label>
                                        <textarea id="terms_condition" name="terms_condition" class="form-control round-input"><?= $material_issue_data[0]->terms_condition ?></textarea>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <textarea id="remarks" name="remarks" class="form-control round-input"><?= $material_issue_data[0]->remarks ?></textarea>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="total_value" class="control-label text-danger">Total value*</label>
                                        <input id="total_value" name="total_value" value="<?= $material_issue_data[0]->total_value ?>" type="text" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="virtual_status" class="control-label text-danger">Virtual Status*</label>
                                        <select required class="form-control" name="virtual_status" id="virtual_status">
                                            <option <?= ($material_issue_data[0]->virtual_status == 1) ? 'selected' : '' ?> value="1">True</option>
                                            <option <?= ($material_issue_data[0]->virtual_status == 0) ? 'selected' : '' ?> value="0">False</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-4">
                                        <button class="btn btn-success" type="submit"><i class="fa fa-refresh"> Update Material Issue</i></button>
                                    </div>
                                </div>
                                <input type="hidden" id="material_issue_id_add" name="material_issue_id" class="hidden" value="<?= $material_issue_data[0]->material_issue_id ?>" />
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
                            Add Material details for <?= $material_issue_data[0]->material_issue_slip_number ?>
                            <span class="tools pull-right"><a class="t-collapse fa fa-chevron-down" href="javascript:;"></a></span>
                        </header>
                        <div class="panel-body">
                            <ul id="purchase_order_tabs" class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#supp_po_list" data-toggle="tab">List</a></li>
                                <li><a href="#supp_po_add" data-toggle="tab">Add</a></li>
                                <li id="supp_po_details_edit_tab" class="disabled"><a href="#po_details_edit" data-toggle="">Edit</a></li>
                            </ul>

                            <div class="tab-content">
                                <img id="pod_edit_loader" class="hidden" style="display:block; margin: auto" src="<?= base_url('assets/img/ellipsis.gif') ?>" alt="" />

                                <!-- LIST TAB -->
                                <div id="supp_po_list" class="tab-pane fade in active">
                                    <table id="supp_po_details_table" class="table data-table dataTable">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Color</th>
                                                <th>Qnty</th>
                                                <th>Rate</th>
                                                <th>Total</th>
                                                <th>Cust. Ord.</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- ADD TAB -->
                                <div id="supp_po_add" class="tab-pane fade">
                                    <br/>
                                    <form id="form_add_receive_purchase_order_details" method="post" action="<?=base_url('admin/form-add-material-issue-details')?>" class="cmxform form-horizontal tasi-form">
                                        <div class="form-group">
                                            <div class="col-lg-3">
                                                <label for="id_id_add" class="control-label text-danger">Item *</label>
                                                <select id="id_id_add" name="id_id_add" required class="select2 form-control round-input">
                                                    <option value="">Select Item</option>
                                                    <?php foreach($item_details as $id) { ?>
                                                    <!-- FIX: added data-id_id attribute -->
                                                    <option value="<?= $id->id_id ?>"
                                                        data-id_id="<?= $id->id_id ?>"
                                                        data-c_id="<?= $id->c_id ?>"
                                                        data-remain_quantity_for_material_issue="<?= $id->remain_quantity_for_material_issue ?>"
                                                        data-pur_id="<?= $id->purchase_order_receive_detail_id ?>"
                                                        data-im_code="<?= $id->im_code ?>"
                                                        data-im_id="<?= $id->im_id ?>"
                                                        data-unit="<?= $id->unit ?>"
                                                        data-color="<?= $id->color ?>"
                                                        data-mat_issue="<?= $id->material_issue_status ?>">
                                                        <?= $id->item ?>[<?= $id->color ?>]
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" id="im_id" name="im_id" required class="form-control" />
                                                <input type="hidden" id="id_id_hidden" name="id_id_hidden" required class="form-control" />
                                                <input id="material_issue_date1" name="material_issue_date1" type="hidden" value="<?= $material_issue_data[0]->material_issue_date ?>" class="form-control round-input" />
                                                <input id="mat_is_id" name="mat_is_id" type="hidden" value="<?= $this->uri->segment(3) ?>" class="form-control round-input" />
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="color_add" class="control-label text-danger">Colour *</label>
                                                <input type="text" id="color_add" name="color_add" required class="form-control" readonly />
                                                <input type="hidden" id="c_id" name="c_id" required class="form-control" />
                                            </div>

                                            <div class="col-lg-1 border-black-bottom">
                                                <label class="control-label">Unit</label><br />
                                                <label id="pod_unit_add"></label>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="issue_quantity_preview" class="control-label text-danger">Actual Quantity *</label>
                                                <input type="number" step="0.01" id="issue_quantity_preview" name="issue_quantity_preview" required class="form-control" readonly />
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="issue_quantity_enter" class="control-label text-danger">Enter Quantity *</label>
                                                <input type="number" step="0.01" id="issue_quantity_enter" name="issue_quantity_enter" required class="form-control" />
                                            </div>

                                            <div class="col-lg-3"></div>

                                            <div class="col-lg-4">
                                                <label for="co_id" class="control-label">Customer Order No*</label>
                                                <select id="co_id" name="co_id" class="form-control select2">
                                                    <option value="">Select Customer Order</option>
                                                    <?php if(isset($customer_order)) { foreach($customer_order as $co) { ?>
                                                    <option value="<?= $co->co_id ?>"><?= $co->co_no ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-4"></div>

                                            <div class="col-lg-2">
                                                <label class="control-label">&nbsp;</label><br>
                                                <button class="btn btn-success" style="margin: auto; display:block;" type="button" id="preview_btn">
                                                    <i class="fa fa-search"></i> Preview
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group" id="preview_table"></div>

                                        <input type="hidden" name="material_issue_id" id="material_issue_id" class="hidden" value="<?= $material_issue_data[0]->material_issue_id ?>" />
                                    </form>
                                </div>

                                <!-- EDIT TAB -->
                                <div id="po_details_edit" class="tab-pane">
                                    <br/>
                                    <form id="form_edit_receive_purchase_order_details" method="post" action="<?=base_url('admin/form-edit-material-issue-details')?>" class="cmxform form-horizontal tasi-form">
                                        <div class="form-group">
                                            <div class="col-lg-3">
                                                <label for="item_name_edit" class="control-label">Item Name</label>
                                                <input type="text" id="item_name_edit" name="item_name_edit" required class="form-control" readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="c_id_edit" class="control-label">Colour</label>
                                                <input type="text" id="c_id_edit" name="c_id_edit" required class="form-control" readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="issue_quantity_edit" class="control-label text-danger">Qnty*</label>
                                                <input type="text" id="issue_quantity_edit" name="issue_quantity_edit" required class="form-control" />
                                                <input type="hidden" id="issue_quantity_hidden_edit" name="issue_quantity_hidden_edit" required readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="issue_rate_edit" class="control-label text-danger">Rate*</label>
                                                <input type="text" id="issue_rate_edit" name="issue_rate_edit" required class="form-control" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="total_amount_edit" class="control-label text-danger">Total*</label>
                                                <input type="text" id="total_amount_edit" name="total_amount_edit" required class="form-control" readonly />
                                                <input type="hidden" id="total_amount_hidden_edit" name="total_amount_hidden_edit" required readonly />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-4">
                                                <label for="co_id_edit" class="control-label">Customer Order No*</label>
                                                <select id="co_id_edit" name="co_id_edit" class="form-control select2">
                                                    <option value="">Select Customer Order</option>
                                                    <?php if(isset($customer_order)) { foreach($customer_order as $co) { ?>
                                                    <option value="<?= $co->co_id ?>"><?= $co->co_no ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-lg-offset-4">
                                                <label class="control-label">&nbsp;</label><br>
                                                <button class="btn btn-success" style="margin: auto; display:block;" type="submit">
                                                    <i class="fa fa-plus"></i> Update details
                                                </button>
                                                <input type="hidden" id="material_issue_id_edit" name="material_issue_id" class="hidden" value="<?= $material_issue_data[0]->material_issue_id ?>" />
                                                <input type="hidden" name="material_issue_detail_id" id="material_issue_detail_id_edit" class="hidden" value="" />
                                                <input type="hidden" name="purchase_order_receive_detail_id_edit" id="purchase_order_receive_detail_id_edit" class="hidden" value="" />
                                            </div>
                                        </div>
                                    </form>
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

    // ── DataTable ─────────────────────────────────────────────────────────────
    $(document).ready(function() {
        $('#supp_po_details_table').DataTable({
            "processing": true,
            "language": { processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>' },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('admin/ajax-material-issue-details-table-data')?>",
                "type": "POST",
                "dataType": "json",
                data: { material_issue_id_add: function() { return $("#material_issue_id_add").val(); } }
            },
            "columns": [
                { "data": "item_name"       },
                { "data": "item_color"      },
                { "data": "item_qty"        },
                { "data": "item_rate"       },
                { "data": "total_amount"    },
                { "data": "customer_orders" },
                { "data": "action"          }
            ],
            "columnDefs": [{ "targets": [6], "orderable": false }]
        });
        $('[data-toggle="tooltip"]').tooltip();
    });


    // ── Issue To/From change ──────────────────────────────────────────────────
    $("#material_issue_to_form").change(function() {
        var val = parseInt($(this).val());
        $('#challan_div').hide();
        $('#supplier_div').hide();
        if (val === 2) { $('#challan_div').show(); }
        else if (val === 3 || val === 4) { $('#supplier_div').show(); }
    });


    // ── Item dropdown change → fill fields + fetch actual qty ─────────────────
    $(document).on('change', '#id_id_add', function() {
        var selected           = $('option:selected', this);
        var id_id_add          = $(this).val();
        var im_id              = selected.data('im_id');
        var id_id              = selected.data('id_id');
        var color              = selected.data('color');
        var c_id               = selected.data('c_id');
        var unit               = selected.data('unit');
        var purc_rcv_detail_id = selected.data('pur_id');
        var _td = new Date();
        var issue_date_add = _td.getFullYear() + '-' +
            String(_td.getMonth() + 1).padStart(2, '0') + '-' +
            String(_td.getDate()).padStart(2, '0');

        if (parseInt(id_id_add) > 0) {
            $('#im_id').val(im_id);
            $('#id_id_hidden').val(id_id);
            $('#color_add').val(color);
            $('#c_id').val(c_id);
            $("#pod_unit_add").html("<b>" + unit + "</b>");

            $.ajax({
                url: "<?= base_url('admin/fetch-remainng-stock-for-material-issue') ?>",
                method: "post",
                dataType: 'json',
                data: { 'item_dtl_id': id_id_add, 'issue_date_add': issue_date_add, 'purc_rcv_id': purc_rcv_detail_id },
                success: function(result) {
                    $('#issue_quantity_preview').val(result);
                    $('#issue_quantity_enter').attr('max', result);
                },
                error: function(e) { console.log(e); }
            });
        } else {
            $('#im_id').val('');
            $('#id_id_hidden').val('');
            $('#color_add').val('');
            $('#c_id').val('');
            $("#pod_unit_add").html('');
            $('#issue_quantity_preview').val('');
            $('#issue_quantity_enter').val('');
        }
    });


    // ── Preview button ────────────────────────────────────────────────────────
    $(document).on('click', '#preview_btn', function() {
        var issue_quantity_prev    = parseFloat($('#issue_quantity_preview').val()) || 0;
        var issue_quantity_preview = parseFloat($('#issue_quantity_enter').val())   || 0;

        if (issue_quantity_prev <= 0) {
            alert('Please select an item first.');
            return false;
        }
        if (issue_quantity_preview <= 0) {
            alert('Please enter a quantity.');
            return false;
        }
        if (issue_quantity_preview > issue_quantity_prev) {
            alert('Enter a value less than or equal to: ' + issue_quantity_prev);
            return false;
        }

        var selected_option = $('option:selected', '#id_id_add');
        var id_id       = selected_option.val();
        var im_id       = $('#im_id').val();
        var purc_rcv_id = selected_option.data('pur_id');
        var issue_date  = $('#material_issue_date').val();

        if (!id_id || id_id === '') {
            alert('Please select an item first.');
            return false;
        }

        $.ajax({
            url: "<?= base_url('admin/ajax-get-consume-list-purchase-order-receive-detail') ?>",
            method: "post",
            dataType: 'json',
            data: {
                'id_id':                  id_id,
                'im_id':                  im_id,
                'issue_quantity_preview': issue_quantity_preview,
                'issue_date_add':         issue_date,
                'purc_rcv_id':            purc_rcv_id
            },
            success: function(response) {
                var table        = '';
                var preview_data = response.preview_data;

                if (response.status == true) {
                    var sum_q = 0, sum_t = 0; // ← FIX: removed sum_r, rate total makes no sense

                    table += '<table class="table">';
                    table += '<thead><tr>';
                    table += '<th scope="col">Item Name</th>';
                    table += '<th scope="col">Colour</th>';
                    table += '<th scope="col">Qnty</th>';
                    table += '<th scope="col">Rate</th>';
                    table += '<th scope="col">Total</th>';
                    table += '</tr></thead><tbody>';

                    for (var i = 0; i < preview_data.length; i++) {
                        var consumed   = parseFloat(preview_data[i].consumed)   || 0;
                        var item_rate  = parseFloat(preview_data[i].item_rate)  || 0;
                        var total_rate = parseFloat(preview_data[i].total_rate) || 0;

                        if (consumed <= 0) continue; // skip null/zero rows

                        sum_q += consumed;
                        // ← FIX: do NOT add rates — rate per row is meaningful,
                        //         total of rates is meaningless
                        sum_t += total_rate;

                        var rcv_id = preview_data[i].purchase_order_receive_detail_id;

                        table += '<tr>';
                        table += '<th scope="row">' + preview_data[i].item_name + '</th>';
                        table += '<td>' + preview_data[i].color +
                                 '<input type="hidden" name="c_id[]" id="c_id_' + rcv_id + '" value="' + preview_data[i].c_id + '"></td>';
                        table += '<td><input type="text" id="issue_quantity_' + rcv_id + '" name="issue_quantity[]" required class="form-control class_q" value="' + consumed + '" readonly /></td>';
                        table += '<td><input type="text" id="issue_rate_' + rcv_id + '" name="issue_rate[]" required class="form-control class_r" value="' + item_rate + '" /></td>';
                        table += '<td>' +
                                 '<input type="text" id="total_amount_' + rcv_id + '" name="total_amount[]" required class="form-control class_t" value="' + total_rate + '" readonly />' +
                                 '<input type="hidden" name="id_id[]" id="id_id_' + rcv_id + '" value="' + preview_data[i].id_id + '">' +
                                 '<input type="hidden" name="im_id[]" id="im_id_' + rcv_id + '" value="' + preview_data[i].im_id + '">' +
                                 '<input type="hidden" name="purchase_order_receive_detail_id[]" id="purchase_order_receive_detail_id_' + rcv_id + '" value="' + rcv_id + '">' +
                                 '</td>';
                        table += '</tr>';
                    }

                    table += '</tbody>';
                    table += '<tfoot><tr>';
                    table += '<th colspan="2">Total</th>';
                    table += '<th id="tot_qn" style="text-align:right;">' + sum_q.toFixed(2) + '</th>';
                    table += '<th id="tot_ra" style="text-align:right;">-</th>'; // ← FIX: rate total = "-"
                    table += '<th id="tot_to" style="text-align:right;">' + sum_t.toFixed(2) + '</th>';
                    table += '</tr></tfoot></table>';
                    table += '<div class="form-group"><div class="col-lg-4 col-lg-offset-4">';
                    table += '<label class="control-label">&nbsp;</label><br>';
                    table += '<button class="btn btn-success" style="margin: auto; display:block;" type="submit"><i class="fa fa-plus"></i> Add details</button>';
                    table += '</div></div>';

                } else {
                    table = '<p class="text-danger">Sorry! No preview data available.</p>';
                }

                $('#preview_table').html(table);
            },
            error: function(xhr) {
                console.log('AJAX Error:', xhr.responseText);
                alert('Server error - check console.');
            }
        });
    });


    // ── Rate blur → recalculate row total ─────────────────────────────────────
    $(document).on('blur', '.class_r', function() {
        var class_r = parseFloat($(this).val()) || 0;
        var class_q = parseFloat($(this).closest('tr').find(".class_q").val()) || 0;
        $(this).closest('tr').find(".class_t").val((class_r * class_q).toFixed(2));

        var sum_q = 0, sum_t = 0; // ← FIX: removed sum_r
        $(".class_t").each(function() {
            sum_q += parseFloat($(this).closest('tr').find(".class_q").val()) || 0;
            // ← FIX: do NOT sum rates
            sum_t += parseFloat($(this).val()) || 0;
        });
        $("#tot_qn").text(sum_q.toFixed(2));
        $("#tot_ra").text('-'); // ← FIX: rate total = "-"
        $("#tot_to").text(sum_t.toFixed(2));
    });


    // ── Header form ───────────────────────────────────────────────────────────
    $("#form_edit_receive_purchase_order").validate({
        rules: { material_issue_date: { required: true } }
    });
    $('#form_edit_receive_purchase_order').ajaxForm({
        beforeSubmit: function() { return $("#form_edit_receive_purchase_order").valid(); },
        success: function(returnData) { notification(JSON.parse(returnData)); }
    });


    // ── ADD form ──────────────────────────────────────────────────────────────
    $("#form_add_receive_purchase_order_details").validate({
        rules: { issue_quantity_enter: { required: true } }
    });
    $('#form_add_receive_purchase_order_details').ajaxForm({
        beforeSubmit: function() { return $("#form_add_receive_purchase_order_details").valid(); },
        success: function(returnData) {
            var obj = JSON.parse(returnData);
            $('#preview_table').html('');
            $('#total_value').val(obj.tot_amount);
            $("#form_add_receive_purchase_order_details").validate().resetForm();
            $("#co_id").select2("val", "");
            notification(obj);
            $("#id_id_add").select2('open');
            $("#id_id_add").focus();
            $('#supp_po_details_table').DataTable().ajax.reload();
        }
    });


    // ── Edit row click → populate EDIT tab ───────────────────────────────────
    $("#supp_po_details_table").on('click', '.material_issue_detail_id', function() {
        $("#pod_edit_loader").removeClass('hidden');
        var material_issue_detail_id = $(this).attr('material_issue_detail_id');

        $.ajax({
            url: "<?= base_url('admin/ajax-fetch-material-issue-details-on-pk') ?>",
            method: "post",
            dataType: 'json',
            data: { 'material_issue_detail_id': material_issue_detail_id },
            success: function(result) {
                var edit_data = result[0];
                $("#item_name_edit").val(edit_data.item);
                $("#c_id_edit").val(edit_data.color);
                $("#co_id_edit").val(edit_data.co_id).trigger('change.select2');
                $("#issue_quantity_edit").val(edit_data.issue_quantity);
                $("#issue_quantity_hidden_edit").val(edit_data.issue_quantity);
                $("#issue_rate_edit").val(edit_data.issue_rate);
                $("#total_amount_edit").val(edit_data.total_amount);
                $("#total_amount_hidden_edit").val(edit_data.total_amount);
                $("#material_issue_detail_id_edit").val(edit_data.material_issue_detail_id);
                $("#purchase_order_receive_detail_id_edit").val(edit_data.purchase_order_receive_detail_id);

                $('#supp_po_details_edit_tab').removeClass('disabled');
                $('#supp_po_details_edit_tab').children("a").attr("data-toggle", 'tab');
                $('a[href="#po_details_edit"]').tab('show');
                $("#pod_edit_loader").addClass('hidden');
            }
        });
    });


    // ── EDIT form ─────────────────────────────────────────────────────────────
    $("#form_edit_receive_purchase_order_details").validate({
        rules: { issue_quantity_edit: { required: true } }
    });
    $('#form_edit_receive_purchase_order_details').ajaxForm({
        beforeSubmit: function() { return $("#form_edit_receive_purchase_order_details").valid(); },
        success: function(returnData) {
            var obj = JSON.parse(returnData);
            $("#form_add_receive_purchase_order_details").validate().resetForm();
            notification(obj);
            $('#form_add_receive_purchase_order_details')[0].reset();
            $('#supp_po_details_table').DataTable().ajax.reload();
        }
    });


    // ── Edit quantity blur → recalculate total ────────────────────────────────
    $("#issue_quantity_edit").on('blur', function() {
        var qty     = parseFloat($(this).val()) || 0;
        var max_qty = parseFloat($("#issue_quantity_hidden_edit").val()) || 0;
        var rate    = parseFloat($("#issue_rate_edit").val()) || 0;
        if (qty > max_qty) {
            alert('Maximum issue quantity limit: ' + max_qty);
            $(this).val(max_qty);
            qty = max_qty;
        }
        $("#total_amount_edit").val((qty * rate).toFixed(2));
    });


    // ── Delete ────────────────────────────────────────────────────────────────
    $(document).on('click', '.delete', function() {
        var $this = $(this);
        if (confirm("Are You Sure?")) {
            $.ajax({
                url: "<?= base_url('admin/del-material-issue-details-list') ?>",
                dataType: 'json',
                type: 'POST',
                data: {
                    tab:                              $(this).attr('tab'),
                    tab_pk:                           $(this).attr('tab-pk'),
                    data_pk:                          $(this).attr('data-pk'),
                    reference_tab:                    $(this).attr('reference-tab'),
                    reference_pk:                     $(this).attr('reference-pk'),
                    reference_data_pk:                $(this).attr('reference-data-pk'),
                    issue_quantity:                   $(this).attr('issue_quantity'),
                    total_amount:                     $(this).attr('total_amount'),
                    purchase_order_receive_detail_id: $(this).attr('purchase_order_receive_detail_id')
                },
                success: function(returnData) {
                    $this.closest('tr').remove();
                    notification(returnData);
                    $("#supp_po_details_table").DataTable().ajax.reload();
                },
                error: function(returnData) { notification(JSON.parse(returnData)); }
            });
        }
    });


    // ── Notification ──────────────────────────────────────────────────────────
    function notification(obj) {
        toastr[obj.type](obj.msg, obj.title, {
            "closeButton": true, "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000", "extendedTimeOut": "7000",
            "showEasing": "swing", "hideEasing": "linear",
            "showMethod": "fadeIn", "hideMethod": "fadeOut"
        });
    }

</script>
</body>
</html>