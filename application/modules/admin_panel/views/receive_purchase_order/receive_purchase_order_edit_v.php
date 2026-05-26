<?php
/**
 * Coded by: Pran Krishna Das
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Receive Purchase Order | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="edit Purchase Order">

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css" />
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">
    <?php $this->load->view('components/_common_head'); ?>
    <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; text-align: right; }
    .hide { display: none; }
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
            <h3 class="m-b-less">Edit Receive Purchase Order</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Edit Receive Purchase Order </li>
                </ol>
            </div>
        </div>

        <div class="wrapper">

            <!-- Header + Totals Row -->
            <div class="row">
                <div class="col-md-8">
                    <section class="panel">
                        <header class="panel-heading">
                            Edit <?= $receive_purchase_order_details[0]->purchase_order_receive_bill_no ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <form id="form_edit_receive_purchase_order" method="post"
                                action="<?=base_url('admin/form-edit-receive-purchase-order')?>"
                                class="cmxform form-horizontal tasi-form">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label for="purchase_order_receive_bill_no" class="control-label text-danger">Purchase Bill Number *</label>
                                        <input id="purchase_order_receive_bill_no" name="purchase_order_receive_bill_no"
                                            value="<?= $receive_purchase_order_details[0]->purchase_order_receive_bill_no ?>"
                                            type="text" placeholder="Purchase Receive Number" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="purchase_order_receive_date" class="control-label text-danger">Purchase Bill Date *</label>
                                        <input id="purchase_order_receive_date" name="purchase_order_receive_date"
                                            value="<?php echo date('Y-m-d', strtotime($receive_purchase_order_details[0]->purchase_order_receive_date)); ?>"
                                            type="date" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label text-danger">Select Supplier *</label>
                                        <input type="hidden" id="am_id_hidden" name="am_id_hidden"
                                            value="<?=$receive_purchase_order_details[0]->am_id?>"><br>
                                        <label><strong><?=$receive_purchase_order_details[0]->acc_master_name?></strong></label>
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
                                            <i class="fa fa-refresh"> Update Receive Purchase Order</i>
                                        </button>
                                    </div>
                                    <div class="col-sm-4">
                                        <button id="print_all" type="button" class="btn btn-primary">
                                            <i class="fa fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" id="purchase_order_receive_id" name="purchase_order_receive_id"
                                    class="hidden" value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                            </form>
                        </div>
                    </section>
                </div>

                <!-- Totals Panel -->
                <div class="col-md-4 hidden-xs">
                    <section class="panel">
                        <header class="panel-heading">
                            Total:
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <form id="form_edit_delivery_sgst_cgst_value" method="post"
                                action="<?=base_url('admin/form-edit-delivery-sgst-cgst-value')?>"
                                class="cmxform form-horizontal tasi-form">
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label class="control-label">Total Value (Qnty x Rate)</label>
                                        <input id="total_amount" name="total_amount"
                                            value="<?= $receive_purchase_order_details[0]->total_amount ?>"
                                            type="text" class="form-control" readonly />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label">Delivery Charge</label>
                                        <input readonly id="delivery_charge" name="delivery_charge"
                                            value="<?= $receive_purchase_order_details[0]->total_delivery_charges ?>"
                                            type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label class="control-label">Total CGST</label>
                                        <input readonly id="total_cgst_amount" name="total_cgst_amount"
                                            value="<?= $receive_purchase_order_details[0]->total_cgst_amount ?>"
                                            type="text" class="form-control" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label">Total SGST</label>
                                        <input readonly id="total_sgst_amount" name="total_sgst_amount"
                                            value="<?= $receive_purchase_order_details[0]->total_sgst_amount ?>"
                                            type="text" class="form-control" />
                                    </div>
                                </div>
                                <?php
                                $total_cgst_sgst_delivery_amount = (
                                    $receive_purchase_order_details[0]->total_amount +
                                    $receive_purchase_order_details[0]->total_delivery_charges +
                                    $receive_purchase_order_details[0]->total_cgst_amount +
                                    $receive_purchase_order_details[0]->total_sgst_amount
                                );
                                ?>
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <label class="control-label">Total Tax Amount</label>
                                        <input id="delivery_sgst_cgst_amount" name="delivery_sgst_cgst_amount"
                                            value="<?= ($receive_purchase_order_details[0]->total_cgst_amount + $receive_purchase_order_details[0]->total_sgst_amount) ?>"
                                            type="text" class="form-control" readonly />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label">Net Amount</label>
                                        <input id="net_amount" name="net_amount"
                                            value="<?= round($total_cgst_sgst_delivery_amount) ?>"
                                            type="text" class="form-control" readonly />
                                    </div>
                                </div>
                                <input type="hidden" id="purchase_order_receive_id_total"
                                    name="purchase_order_receive_id"
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
                            Add Receive purchase order details for
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
                                                <th>Challan #</th>
                                                <th>Supp. Po. Num.</th>
                                                <th>Item Name</th>
                                                <th>Color</th>
                                                <th>Qnty</th>
                                                <th>Rate</th>
                                                <th>Total</th>
                                                <th>Tax Rate (%)</th>
                                                <th>Total Tax Amnt.</th>
                                                <th>Net Total</th>
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
                                    <form id="form_add_receive_purchase_order_details" method="post"
                                        action="<?=base_url('admin/form-add-receive-purchase-order-details')?>"
                                        class="cmxform form-horizontal tasi-form">

                                        <div class="form-group">
                                            <!-- Challan Number -->
                                            <div class="col-lg-4">
                                                <label for="po_id" class="control-label text-danger">Challan Number *</label>
                                                <select id="po_id" name="po_id" class="select2 form-control round-input">
                                                    <option value="">Select Challan Number</option>
                                                    <?php foreach($challan_orders as $val) { ?>
                                                    <option value="<?= $val['purchase_order_receive_id'] ?>">
                                                        <?= $val['purchase_order_receive_bill_no'] ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!-- Receive Date -->
                                            <div class="col-lg-4">
                                                <label for="rcv_date_detail" class="control-label text-danger">Receive Date *</label>
                                                <input type="date" id="rcv_date_detail" name="rcv_date_detail"
                                                    value="<?php echo date('Y-m-d', strtotime($receive_purchase_order_details[0]->purchase_order_receive_date)); ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <!-- Item -->
                                            <div class="col-lg-4">
                                                <label for="id_id_add" class="control-label text-danger">Item *</label>
                                                <select id="id_id_add" class="select2 form-control round-input">
                                                    <option value="">Select Item</option>
                                                </select>
                                                <!-- hidden: carries real id_id to server -->
                                                <input type="hidden" id="id_id_add_real" name="id_id_add" />
                                                <!-- hidden: carries po_id to server -->
                                                <input type="hidden" id="po_id_real" name="po_id" />
                                                <input type="hidden" id="challan_detail_id" name="challan_detail_id" value="0" />
                                            </div>
                                            <!-- Colour -->
                                            <div class="col-lg-3">
                                                <label for="color_add" class="control-label text-danger">Colour *</label>
                                                <input type="text" id="color_add" name="color_add" required class="form-control" readonly />
                                            </div>
                                            <!-- Unit -->
                                            <div class="col-lg-1 border-black-bottom">
                                                <label class="control-label">Unit</label><br />
                                                <label id="pod_unit_add"></label>
                                            </div>
                                            <!-- Quantity -->
                                            <div class="col-lg-2">
                                                <label for="pod_quantity_add" class="control-label text-danger">
                                                    Quantity *
                                                    <small id="qty_remaining_label" class="text-info"></small>
                                                </label>
                                                <input type="number" step="0.01" id="pod_quantity_add"
                                                    name="pod_quantity_add" required class="form-control" />
                                                <input type="hidden" id="pod_quantity_add_hidden"
                                                    name="pod_quantity_add_hidden" class="form-control" />
                                            </div>
                                            <!-- Rate -->
                                            <div class="col-lg-2">
                                                <label for="pod_rate_add" class="control-label text-danger">Rate *</label>
                                                <input type="number" step="0.001" id="pod_rate_add"
                                                    name="pod_rate_add" required class="form-control" />
                                                <input type="hidden" id="pod_rate_add_hide" name="pod_rate_add_hide" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-2">
                                                <label class="control-label">Delivery Charges</label>
                                                <input type="number" step="0.01" id="pod_delivery_charges"
                                                    name="pod_delivery_charges" value="0" required class="form-control">
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">CGST(%)</label>
                                                <input step="0.01" id="pod_cgst_percentage" name="pod_cgst_percentage"
                                                    class="form-control" value="" type="text" placeholder="CGST Percentage" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">SGST(%)</label>
                                                <input step="0.01" id="pod_sgst_percentage" name="pod_sgst_percentage"
                                                    class="form-control" value="" type="text" placeholder="SGST Percentage" />
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="control-label">Total</label>
                                                <input type="number" step="1" id="pod_total_add"
                                                    name="pod_total_add" required class="form-control" readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">Remarks</label>
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
                                            id="purchase_order_receive_id_add"
                                            value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                                        <!-- sup_id set to 0 since removed -->
                                        <input type="hidden" name="sup_id" value="0" />
                                    </form>
                                </div>

                                <!-- EDIT TAB -->
                                <div id="po_details_edit" class="tab-pane">
                                    <br />
                                    <form id="form_edit_receive_purchase_order_details" method="post"
                                        action="<?=base_url('admin/form-edit-receive-purchase-order-details')?>"
                                        class="cmxform form-horizontal tasi-form">

                                        <div class="form-group">
                                            <!-- Challan Number (display only) -->
                                            <div class="col-lg-4">
                                                <label class="control-label text-danger">Challan Number</label>
                                                <select id="po_id_edit" name="po_id_edit"
                                                    class="select2 form-control round-input">
                                                    <option value="">Select Challan Number</option>
                                                </select>
                                            </div>
                                            <!-- Receive Date -->
                                            <div class="col-lg-4">
                                                <label class="control-label">Receive Date</label>
                                                <input type="date" id="rcv_date_detail_edit"
                                                    name="rcv_date_detail_edit" class="form-control" />
                                            </div>
                                            <!-- Item -->
                                            <div class="col-lg-4">
                                                <label class="control-label text-danger">Item *</label>
                                                <select id="id_id_edit" name="id_id_edit" required
                                                    class="select2 form-control round-input">
                                                    <option value="">Select Item</option>
                                                </select>
                                            </div>
                                            <!-- Colour -->
                                            <div class="col-lg-3">
                                                <label class="control-label text-danger">Colour *</label>
                                                <input type="text" id="color_edit" name="color_edit" required
                                                    class="form-control" readonly />
                                            </div>
                                            <!-- Unit -->
                                            <div class="col-lg-1 border-black-bottom">
                                                <label class="control-label">Unit</label><br />
                                                <label id="pod_unit_edit"></label>
                                            </div>
                                            <!-- Quantity -->
                                            <div class="col-lg-2">
                                                <label class="control-label text-danger">
                                                    Quantity *
                                                    <small id="qty_remaining_label_edit" class="text-info"></small>
                                                </label>
                                                <input type="number" step="0.01" id="pod_quantity_edit"
                                                    name="pod_quantity_edit" required class="form-control" />
                                                <input type="hidden" id="pod_quantity_edit_hidden"
                                                    name="pod_quantity_edit_hidden" class="form-control" />
                                                <input type="hidden" id="remain_item_quantity"
                                                    name="remain_item_quantity" class="form-control" />
                                            </div>
                                            <!-- Rate -->
                                            <div class="col-lg-2">
                                                <label class="control-label text-danger">Rate *</label>
                                                <input type="number" step="0.001" id="pod_rate_edit"
                                                    name="pod_rate_edit" required class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-2">
                                                <label class="control-label">Delivery Charges</label>
                                                <input type="number" step="0.01" id="pod_delivery_charges_edit"
                                                    name="pod_delivery_charges_edit" required class="form-control" />
                                                <input type="hidden" id="pod_delivery_charges_old"
                                                    name="pod_delivery_charges_old" class="form-control" readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">CGST(in %)</label>
                                                <input step="0.01" id="pod_cgst_percentage_edit"
                                                    name="pod_cgst_percentage_edit" required class="form-control"
                                                    value="" type="text" placeholder="CGST Percentage" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">SGST(in %)</label>
                                                <input step="0.01" id="pod_sgst_percentage_edit"
                                                    name="pod_sgst_percentage_edit" required class="form-control"
                                                    value="" type="text" placeholder="SGST Percentage" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">Total</label>
                                                <input type="number" step="0.01" id="pod_total_edit"
                                                    name="pod_total_edit" required class="form-control" readonly />
                                                <input type="hidden" id="pod_total_old" name="pod_total_old"
                                                    class="form-control" readonly />
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="control-label">Remarks</label>
                                                <input type="text" id="sup_pod_remarks_edit"
                                                    name="sup_pod_remarks_edit" class="form-control" />
                                            </div>
                                            <div class="col-lg-4 col-lg-offset-4">
                                                <label class="control-label">&nbsp;</label><br>
                                                <button class="btn btn-success" style="margin: auto; display:block;"
                                                    type="submit">
                                                    <i class="fa fa-plus"></i> Update details
                                                </button>
                                                <input type="hidden" id="purchase_order_receive_id_edit"
                                                    name="purchase_order_receive_id"
                                                    value="<?= $receive_purchase_order_details[0]->purchase_order_receive_id ?>" />
                                                <input type="hidden" name="purchase_order_receive_detail_id"
                                                    id="purchase_order_receive_detail_id" value="" />
                                                <!-- sup_id set to 0 since removed -->
                                                <input type="hidden" name="sup_id_edit" value="0" />
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- Item Rate Panel -->
                    <section class="panel panel_last hide">
                        <header class="panel-heading">
                            Item Rate of (<span class="item_color_rate_header"
                                style="font-size:18px;font-weight:bold;color:#fff;text-shadow:2px 2px 3px #000"></span>)
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body1">
                            <ul id="item_rate_tabs" class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#rate_list" data-toggle="tab">List</a></li>
                                <li id="rate_add_tab"><a href="#rate_add" data-toggle="tab">Add</a></li>
                                <li id="rate_edit_tab" class="disabled"><a href="#rate_edit" data-toggle="">Edit</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="rate_list" class="tab-pane fade in active">
                                    <table id="item_color_rate_table" class="table data-table dataTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Supplier Name</th>
                                                <th>Purchase Rate</th>
                                                <th>Cost Rate</th>
                                                <th>Plating Rate</th>
                                                <th>GST (%)</th>
                                                <th>Effective Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div id="rate_add" class="tab-pane fade">
                                    <br/>
                                    <form id="form_add_item_color_rate" method="post"
                                        action="<?=base_url('admin/form_add_item_color_rate')?>"
                                        class="cmxform form-horizontal tasi-form">
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 text-danger">Effective Date *</label>
                                            <div class="col-lg-2">
                                                <input id="eff_date" name="eff_date" type="date"
                                                    value="<?= date('Y-m') ?>-01" max="<?= date('Y-m-d') ?>"
                                                    required class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-2 text-danger">Purchase Rate *</label>
                                            <div class="col-lg-2">
                                                <input id="pur_rate" name="pur_rate" type="number" min="0"
                                                    placeholder="Purchase rate" required class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-2 text-danger">Cost Rate *</label>
                                            <div class="col-lg-2">
                                                <input id="cost_rate" name="cost_rate" type="number" min="0"
                                                    placeholder="Cost rate" required class="form-control round-input" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Plating Rate</label>
                                            <div class="col-lg-3">
                                                <input id="plating_rate_add1" name="plating_rate_add1" type="number"
                                                    min="0" placeholder="Plating rate" required class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-1 text-danger">GST (%) *</label>
                                            <div class="col-lg-3">
                                                <input id="gst" name="gst" type="number" min="0"
                                                    placeholder="GST percentage" required class="form-control round-input" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 text-danger">Status *</label>
                                            <div class="col-lg-4">
                                                <input type="radio" name="status" id="enable4" value="1" checked required class="iCheck-square-green">
                                                <label for="enable4" class="control-label">Enable</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="status" id="disable4" value="0" required class="iCheck-square-red">
                                                <label for="disable4" class="control-label">Disable</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item_dtl_id" id="item_dtl_id" value="">
                                        <input type="hidden" name="supplier" id="supplier" value="">
                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa fa-plus"></i> Add Item Color Rate
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="rate_edit" class="tab-pane fade">
                                    <br/>
                                    <form id="form_edit_item_color_rate" method="post"
                                        action="<?=base_url('admin/form_edit_item_color_rate')?>"
                                        class="cmxform form-horizontal tasi-form">
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 text-danger">Effective Date *</label>
                                            <div class="col-lg-2">
                                                <input id="eff_date2" name="eff_date" type="date"
                                                    max="<?= date('Y-m-d') ?>" required class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-2 text-danger">Purchase Rate *</label>
                                            <div class="col-lg-2">
                                                <input id="pur_rate2" name="pur_rate" type="number" min="0"
                                                    placeholder="Purchase rate" required class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-2 text-danger">Cost Rate *</label>
                                            <div class="col-lg-2">
                                                <input id="cost_rate2" name="cost_rate" type="number" min="0"
                                                    placeholder="Cost rate" required class="form-control round-input" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Plating Rate</label>
                                            <div class="col-lg-3">
                                                <input id="plating_rate_edit1" name="plating_rate_edit1" type="number"
                                                    min="0" placeholder="Plating rate" class="form-control round-input" />
                                            </div>
                                            <label class="control-label col-lg-2 text-danger">GST (%) *</label>
                                            <div class="col-lg-3">
                                                <input id="gst2" name="gst" type="number" min="0"
                                                    placeholder="GST percentage" required class="form-control round-input" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2 text-danger">Status *</label>
                                            <div class="col-lg-4">
                                                <input type="radio" name="status" id="enable5" value="1" required class="iCheck-square-green">
                                                <label for="enable5" class="control-label">Enable</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="status" id="disable5" value="0" required class="iCheck-square-red">
                                                <label for="disable5" class="control-label">Disable</label>
                                            </div>
                                        </div>
                                        <input type="hidden" id="item_rate_id" name="item_rate_id" value="">
                                        <input type="hidden" name="supplier2" id="supplier2" value="">
                                        <input type="hidden" name="supplier" id="supplier" value="">
                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa fa-refresh"></i> Update Item Color Rate
                                                </button>
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

<!-- JS -->
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

    // ── DataTable ────────────────────────────────────────────────────────────
    $('#supp_po_details_table').DataTable({
        "processing": true,
        "language": {
            processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
        },
        "serverSide": true,
        "ajax": {
            "url": "<?=base_url('admin/ajax-receive-purchase-order-details-table-data')?>",
            "type": "POST",
            "dataType": "json",
            data: {
                purchase_order_receive_id: function() {
                    return $("#purchase_order_receive_id").val();
                },
            },
        },
        "columns": [
            { "data": "po_number"      },
            { "data": "sup_po_number"  },
            { "data": "item_name"      },
            { "data": "item_color"     },
            { "data": "item_qty"       },
            { "data": "item_rate"      },
            { "data": "total_amount"   },
            { "data": "total_tax_rate" },
            { "data": "total_tax"      },
            { "data": "net_amount"     },
            { "data": "receive_date"   },
            { "data": "action"         },
        ],
        "columnDefs": [{ "targets": [1, 8], "orderable": false }]
    });

    $('[data-toggle="tooltip"]').tooltip();
});


// ── Challan dropdown change → load items with remaining qty ──────────────────
$("#po_id").change(function () {
    var purchase_order_receive_id = $(this).val();
    var am_id_hidden              = $('#am_id_hidden').val();

    // Clear fields
    $("#id_id_add").html("<option value=''>Select Item</option>");
    $('#color_add').val('');
    $("#pod_unit_add").html('');
    $('#pod_quantity_add').val('');
    $('#pod_quantity_add_hidden').val('');
    $('#pod_rate_add').val('');
    $('#pod_rate_add_hide').val('');
    $('#pod_total_add').val('');
    $('#pod_delivery_charges').val('0');
    $('#pod_cgst_percentage').val('');
    $('#pod_sgst_percentage').val('');
    $('#qty_remaining_label').text('');
    $('#id_id_add_real').val('');
    $('#po_id_real').val('');

    if (!purchase_order_receive_id || purchase_order_receive_id <= 0) return;

    $.ajax({
        url: "<?= base_url('admin/all-items-from-by-receive-id') ?>",
        method: "post",
        dataType: 'json',
        data: {
            purchase_order_receive_id: purchase_order_receive_id,
            am_id_hidden:              am_id_hidden
        },
        success: function (all_items) {
            if (!all_items || all_items.length === 0) {
                $("#id_id_add").html("<option value=''>No items with remaining quantity</option>");
                return;
            }

            $("#id_id_add").html("<option value=''>Select Item</option>");

            $.each(all_items, function (i, item) {
                // Show: item name [color] - Remaining: XX (Challan: YY, Received: ZZ)
                var label = item.item_name
                          + ' [' + item.color + ']'
                          + ' - Remaining: ' + item.pod_quantity
                          + ' (Challan: ' + item.challan_qty
                          + ', Rcvd: ' + item.already_received + ')';

                var opt = '<option '
                    + 'value="'             + item.purchase_order_receive_detail_id + '" '
                    + 'data-id-id="'        + item.id_id            + '" '
                    + 'data-po-id="'        + item.po_id            + '" '
                    + 'pod_quantity="'      + item.pod_quantity      + '" '
                    + 'data-challan-qty="'  + item.challan_qty       + '" '
                    + 'data-already-rcvd="' + item.already_received  + '" '
                    + 'unit="'              + item.unit              + '" '
                    + 'color="'             + item.color             + '">'
                    + label
                    + '</option>';

                $("#id_id_add").append(opt);
            });

            $('#id_id_add').select2('open');
        },
        error: function (e) { console.log(e); }
    });
});


// ── Item (ADD) change → fill fields + show remaining + fetch rate ────────────
$(document).on('change', '#id_id_add', function () {
    var selected       = $('option:selected', this);
    var pod_quantity   = parseFloat(selected.attr('pod_quantity'))       || 0;
    var challan_qty    = parseFloat(selected.attr('data-challan-qty'))   || 0;
    var already_rcvd   = parseFloat(selected.attr('data-already-rcvd')) || 0;
    var unit           = selected.attr('unit')         || '';
    var color          = selected.attr('color')        || '';
    var id_id_add      = selected.attr('data-id-id');
    var po_id_real     = selected.attr('data-po-id');
    var am_id          = $("#am_id_hidden").val();
    var purchase_date  = $("#purchase_order_receive_date").val();

    $(".panel_last").addClass("hide");

    // Clear
    $('#color_add').val('');
    $("#pod_unit_add").html('');
    $('#pod_quantity_add').val('');
    $('#pod_quantity_add_hidden').val('');
    $('#pod_rate_add').val('');
    $('#pod_rate_add_hide').val('');
    $('#pod_total_add').val('');
    $('#id_id_add_real').val('');
    $('#po_id_real').val('');
    $('#qty_remaining_label').text('');

    if (!id_id_add) return;

    // Fill fields
    $('#color_add').val(color);
    $("#pod_unit_add").html("<b>" + unit + "</b>");
    $('#pod_quantity_add').val(pod_quantity);
    $('#pod_quantity_add_hidden').val(pod_quantity);
    $('#id_id_add_real').val(id_id_add);
    $('#po_id_real').val(po_id_real);
    // option value = purchase_order_receive_detail_id from challan
    $('#challan_detail_id').val(selected.val());

    // Show remaining info below quantity label
    $('#qty_remaining_label').text(
        '(Challan: ' + challan_qty + ' | Rcvd: ' + already_rcvd + ' | Remaining: ' + pod_quantity + ')'
    );

    // Fetch rate
    $.ajax({
        url: "<?= base_url('admin/fetch-cost-rate-wrt-item') ?>",
        method: "post",
        dataType: 'json',
        data: {
            item_id:       id_id_add,
            supplier_id:   am_id,
            purchase_date: purchase_date
        },
        success: function (cost_rate) {
            var rate     = parseFloat(cost_rate) || 0;
            var delivery = parseFloat($('#pod_delivery_charges').val()) || 0;
            var cgst_pct = parseFloat($('#pod_cgst_percentage').val())  || 0;
            var sgst_pct = parseFloat($('#pod_sgst_percentage').val())  || 0;

            $('#pod_rate_add').val(rate);
            $('#pod_rate_add_hide').val(rate);

            var line_val = (pod_quantity * rate) + delivery;
            var cgst_val = line_val * (cgst_pct / 100);
            var sgst_val = line_val * (sgst_pct / 100);
            $('#pod_total_add').val(Math.round(line_val + cgst_val + sgst_val));
        },
        error: function (e) { console.log(e); }
    });
});


// ── ADD keyup → validate + recalculate ──────────────────────────────────────
$("#pod_quantity_add, #pod_rate_add, #pod_delivery_charges, #pod_cgst_percentage, #pod_sgst_percentage").on('keyup', function () {
    var qty      = parseFloat($('#pod_quantity_add').val())        || 0;
    var max_qty  = parseFloat($('#pod_quantity_add_hidden').val()) || 0;
    var rate     = parseFloat($('#pod_rate_add').val())            || 0;
    var delivery = parseFloat($('#pod_delivery_charges').val())    || 0;
    var cgst_pct = parseFloat($('#pod_cgst_percentage').val())     || 0;
    var sgst_pct = parseFloat($('#pod_sgst_percentage').val())     || 0;

    if (qty === 0) {
        alert('Quantity can not be zero');
        $('#pod_quantity_add').val('');
        $('#pod_total_add').val('');
        return false;
    }

    if (qty > max_qty) {
        alert('Cannot exceed remaining quantity: ' + max_qty);
        $('#pod_quantity_add').val(max_qty);
        qty = max_qty;
    }

    var line_val = (qty * rate) + delivery;
    var cgst_val = line_val * (cgst_pct / 100);
    var sgst_val = line_val * (sgst_pct / 100);
    $('#pod_total_add').val(Math.round(line_val + cgst_val + sgst_val));
});


// ── EDIT quantity change → validate + recalculate ───────────────────────────
$("#pod_quantity_edit, #pod_rate_edit").on('change', function() {
    var qty      = parseFloat($("#pod_quantity_edit").val())          || 0;
    var orig_qty = parseFloat($("#pod_quantity_edit_hidden").val())   || 0;
    var remain   = parseFloat($("#remain_item_quantity").val())       || 0;
    var rate     = parseFloat($("#pod_rate_edit").val())              || 0;
    var delivery = parseFloat($("#pod_delivery_charges_edit").val())  || 0;
    var cgst_pct = parseFloat($("#pod_cgst_percentage_edit").val())   || 0;
    var sgst_pct = parseFloat($("#pod_sgst_percentage_edit").val())   || 0;
    var max_limit = orig_qty + remain;

    if (qty > max_limit) {
        alert('Quantity Maximum: ' + max_limit);
        $("#pod_quantity_edit").val(max_limit);
        qty = max_limit;
    }

    var line_val = (qty * rate) + delivery;
    var cgst_val = line_val * (cgst_pct / 100);
    var sgst_val = line_val * (sgst_pct / 100);
    $("#pod_total_edit").val((line_val + cgst_val + sgst_val).toFixed(2));

    // Update remaining label
    $('#qty_remaining_label_edit').text('(Max: ' + max_limit + ')');
});


// ── EDIT keyup → recalculate ─────────────────────────────────────────────────
$("#pod_quantity_edit, #pod_rate_edit, #pod_delivery_charges_edit, #pod_cgst_percentage_edit, #pod_sgst_percentage_edit").on('keyup', function() {
    var qty      = parseFloat($("#pod_quantity_edit").val())          || 0;
    var orig_qty = parseFloat($("#pod_quantity_edit_hidden").val())   || 0;
    var remain   = parseFloat($("#remain_item_quantity").val())       || 0;
    var rate     = parseFloat($("#pod_rate_edit").val())              || 0;
    var delivery = parseFloat($("#pod_delivery_charges_edit").val())  || 0;
    var cgst_pct = parseFloat($("#pod_cgst_percentage_edit").val())   || 0;
    var sgst_pct = parseFloat($("#pod_sgst_percentage_edit").val())   || 0;
    var max_limit = orig_qty + remain;

    if (qty === 0) {
        alert('Quantity can not be zero');
        $("#pod_quantity_edit").val('');
        $("#pod_total_edit").val('');
        return false;
    }

    if (qty > max_limit) {
        alert('Quantity Maximum: ' + max_limit);
        $("#pod_quantity_edit").val(max_limit);
        qty = max_limit;
    }

    var line_val = (qty * rate) + delivery;
    var cgst_val = line_val * (cgst_pct / 100);
    var sgst_val = line_val * (sgst_pct / 100);
    $("#pod_total_edit").val((line_val + cgst_val + sgst_val).toFixed(2));
});


// ── Header form ──────────────────────────────────────────────────────────────
$("#form_edit_receive_purchase_order").validate({
    rules: { po_date: { required: true } },
    messages: {}
});
$('#form_edit_receive_purchase_order').ajaxForm({
    beforeSubmit: function() { return $("#form_edit_receive_purchase_order").valid(); },
    success: function(returnData) { notification(JSON.parse(returnData)); }
});


// ── ADD form ─────────────────────────────────────────────────────────────────
$("#form_add_receive_purchase_order_details").validate({
    rules: {
        id_id_add:        { required: true },
        pod_quantity_add: { required: true },
        pod_rate_add:     { required: true },
        pod_total_add:    { required: true },
        rcv_date_detail:  { required: true }
    },
    messages: {}
});

// ── Flag to bypass rate check on second submit ───────────────────────────────
var rate_mismatch_confirmed = false;

$('#form_add_receive_purchase_order_details').ajaxForm({
    beforeSubmit: function() {
        if (!$("#form_add_receive_purchase_order_details").valid()) return false;

        // If user already confirmed mismatch — skip check and submit
        if (rate_mismatch_confirmed) {
            rate_mismatch_confirmed = false; // reset for next time
            return true;
        }

        var current_rate = $("#pod_rate_add").val();
        var master_rate  = $("#pod_rate_add_hide").val();

        // Rates match — submit normally
        if (current_rate == master_rate) return true;

        // Rates mismatch — show confirm dialog
        $.confirm({
            title: 'Rates Mismatch!',
            content: 'Item rate does not match master rate!',
            buttons: {
                confirm: {
                    text: 'Proceed Anyway',
                    btnClass: 'btn-warning',
                    action: function() {
                        // Set flag then trigger submit
                        rate_mismatch_confirmed = true;
                        $('#form_add_receive_purchase_order_details').submit();
                    }
                },
                cancel: {
                    text: 'Cancel',
                    action: function() { $.alert('Canceled!'); }
                },
                somethingElse: {
                    text: 'Update Master',
                    btnClass: 'btn-blue',
                    action: function() {
                        $("#item_dtl_id").val($("#id_id_add_real").val());
                        $("#supplier").val($("#am_id_hidden").val());
                        $('#item_color_rate_table').DataTable().destroy();
                        $(".item_color_rate_header").html($("#id_id_add option:selected").text());
                        $('#item_color_rate_table').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "<?=base_url('ajax_item_color_rate_table_data_new')?>",
                                "type": "POST",
                                "dataType": "json",
                                data: {
                                    item_dtl_id:  function() { return $("#item_dtl_id").val(); },
                                    am_id_hidden: function() { return $("#am_id_hidden").val(); }
                                }
                            },
                            "columns": [
                                { "data": "name" }, { "data": "purchase_rate" },
                                { "data": "cost_rate" }, { "data": "plating_rate" },
                                { "data": "gst_percentage" }, { "data": "effective_date" },
                                { "data": "status" }, { "data": "action" }
                            ],
                            "columnDefs": [{ "targets": -1, "orderable": false, "className": 'nowrap' }]
                        });
                        $(".panel_last").removeClass("hide");
                        $('html, body').animate({ scrollTop: $(".panel_last").offset().top }, 1000);
                    }
                }
            }
        });

        return false; // stop normal submit — dialog handles it
    },
    success: function(returnData) {
        var obj        = JSON.parse(returnData);
        var line_items = obj.line_items;

        $("#total_amount").val((parseFloat(line_items.total_amount) || 0).toFixed(2));
        $("#delivery_charge").val((parseFloat(line_items.total_delivery_charges) || 0).toFixed(2));

        var cgst = parseFloat(line_items.total_cgst_amount) || 0;
        var sgst = parseFloat(line_items.total_sgst_amount) || 0;
        $("#total_cgst_amount").val(cgst.toFixed(2));
        $("#total_sgst_amount").val(sgst.toFixed(2));
        $("#delivery_sgst_cgst_amount").val((cgst + sgst).toFixed(2));
        $("#net_amount").val(Math.round(parseFloat(line_items.net_amount) || 0));

        // Reset fields
        $("#id_id_add").val(null).trigger("change");
        $("#id_id_add_real").val('');
        $("#po_id_real").val('');
        //$("#challan_detail_id").val('0');
        $("#color_add").val('');
        $("#pod_unit_add").html('');
        $("#pod_quantity_add").val('');
        $("#pod_quantity_add_hidden").val('');
        $("#pod_rate_add").val('');
        $("#pod_rate_add_hide").val('');
        $("#pod_total_add").val('');
        $("#pod_delivery_charges").val('0');
        $("#pod_cgst_percentage").val('');
        $("#pod_sgst_percentage").val('');
        $("#sup_pod_remarks").val('');
        $("#qty_remaining_label").text('');

        notification(obj);
        $('#supp_po_details_table').DataTable().ajax.reload();
    }
});


// ── Edit row click → populate EDIT tab ──────────────────────────────────────
$("#supp_po_details_table").on('click', '.purchase_order_receive_detail_id', function() {
    $("#pod_edit_loader").removeClass('hidden');

    var purchase_order_receive_detail_id = $(this).attr('purchase_order_receive_detail_id');

    $.ajax({
        url: "<?= base_url('admin/ajax-fetch-receive-purchase-order-details-on-pk') ?>",
        method: "post",
        dataType: 'json',
        data: { 'purchase_order_receive_detail_id': purchase_order_receive_detail_id },
        success: function(result) {
            var d = result.oreder_receive_details;

            $("#po_id_edit").html("<option value='" + d.po_id + "'>" + d.po_number + "</option>").trigger('change');
            $("#id_id_edit").html("<option value='" + d.id_id + "'>" + d.item + "</option>").trigger('change');
            $("#pod_unit_edit").html('<b>' + d.unit + '</b>');
            $("#color_edit").val(d.color);
            $("#pod_quantity_edit").val(d.item_quantity);
            $("#pod_quantity_edit_hidden").val(d.item_quantity);
            $("#remain_item_quantity").val(result.remain_item_quantity);
            $("#pod_rate_edit").val(d.item_rate);

            var delivery = parseFloat(d.delivery_charges)     || 0;
            var cgst_pct = parseFloat(d.pod_cgst_percentage)  || 0;
            var sgst_pct = parseFloat(d.pod_sgst_percentage)  || 0;
            var qty      = parseFloat(d.item_quantity)        || 0;
            var rate     = parseFloat(d.item_rate)            || 0;
            var max_limit = qty + (parseFloat(result.remain_item_quantity) || 0);

            $("#pod_delivery_charges_edit").val(delivery.toFixed(2));
            $("#pod_delivery_charges_old").val(delivery.toFixed(2));
            $("#pod_cgst_percentage_edit").val(cgst_pct);
            $("#pod_sgst_percentage_edit").val(sgst_pct);

            var line_val = (qty * rate) + delivery;
            var total    = (line_val + line_val*(cgst_pct/100) + line_val*(sgst_pct/100)).toFixed(2);
            $("#pod_total_edit").val(total);
            $("#pod_total_old").val(total);

            // Show max in label
            $('#qty_remaining_label_edit').text('(Max: ' + max_limit + ')');

            $("#sup_pod_remarks_edit").val(d.remarks);
            $("#purchase_order_receive_detail_id").val(d.purchase_order_receive_detail_id);
            $("#rcv_date_detail_edit").val(d.receive_date);

            $('#supp_po_details_edit_tab').removeClass('disabled');
            $('#supp_po_details_edit_tab').children("a").attr("data-toggle", 'tab');
            $('a[href="#po_details_edit"]').tab('show');
            $("#pod_edit_loader").addClass('hidden');
        }
    });
});


// ── EDIT form ────────────────────────────────────────────────────────────────
$("#form_edit_receive_purchase_order_details").validate({
    rules: {
        pod_quantity_edit: { required: true },
        pod_rate_edit:     { required: true }
    },
    messages: {}
});
$('#form_edit_receive_purchase_order_details').ajaxForm({
    beforeSubmit: function() {
        return $("#form_edit_receive_purchase_order_details").valid();
    },
    success: function(returnData) {
        var obj        = JSON.parse(returnData);
        var line_items = obj.line_items;

        $("#total_amount").val((parseFloat(line_items.total_amount) || 0).toFixed(2));
        $("#delivery_charge").val((parseFloat(line_items.total_delivery_charges) || 0).toFixed(2));

        var cgst = (parseFloat(line_items.total_cgst_amount) || 0).toFixed(2);
        var sgst = (parseFloat(line_items.total_sgst_amount) || 0).toFixed(2);
        $("#total_cgst_amount").val(cgst);
        $("#total_sgst_amount").val(sgst);
        $("#delivery_sgst_cgst_amount").val((parseFloat(cgst) + parseFloat(sgst)).toFixed(2));
        $("#net_amount").val(Math.round(parseFloat(line_items.net_amount) || 0));

        notification(obj);
        $('#supp_po_details_table').DataTable().ajax.reload();
    }
});


// ── Total panel form ─────────────────────────────────────────────────────────
$("#form_edit_delivery_sgst_cgst_value").validate({
    rules: {
        total_amount: { required: true }, delivery_charge: { required: true },
        delivery_sgst_cgst_amount: { required: true }, net_amount: { required: true }
    },
    messages: {}
});
$('#form_edit_delivery_sgst_cgst_value').ajaxForm({
    beforeSubmit: function() { return $("#form_edit_delivery_sgst_cgst_value").valid(); },
    success: function(returnData) { notification(JSON.parse(returnData)); }
});


// ── Item rate edit ────────────────────────────────────────────────────────────
$("#item_color_rate_table").on('click', '.item_rate_edit_btn', function() {
    var item_rate_id = $(this).attr('item_rate_id');
    $.ajax({
        url: "<?= base_url('ajax_fetch_item_rate') ?>",
        method: "post", dataType: 'json',
        data: { 'item_rate_id': item_rate_id },
        success: function(data) {
            $("#item_rate_id").val(data.ir_id);
            $(".supplier2").val(data.am_id);
            $(".supplier").val(data.am_id);
            $("#gst2").val(data.gst_percentage);
            $("#pur_rate2").val(data.purchase_rate);
            $("#cost_rate2").val(data.cost_rate);
            $("#plating_rate_edit1").val(data.plating_rate);
            $("#eff_date2").val(data.effective_date);
            if (data.status == '1') { $("#enable5").iCheck('check'); }
            else { $("#disable5").iCheck('check'); }
            $('#rate_edit_tab').removeClass('disabled');
            $('#rate_edit_tab').children("a").attr("data-toggle", 'tab');
            $('#item_rate_tabs li:eq(2) a').tab('show');
        }
    });
});


// ── Add item color rate ───────────────────────────────────────────────────────
$("#form_add_item_color_rate").validate({
    rules: {
        eff_date: { required: true, remote: {
            url: "<?=base_url('ajax_unique_supp_item_color_rate_eff_date')?>", type: "post",
            data: { item_dtl_id: function(){ return $("#item_dtl_id").val(); }, supplier: function(){ return $("#supplier").val(); }, item_rate_id: '' }
        }}
    }, messages: {}
});
$('#form_add_item_color_rate').ajaxForm({
    beforeSubmit: function() { return $("#form_add_item_color_rate").valid(); },
    success: function(returnData) {
        var obj = JSON.parse(returnData);
        $('#form_add_item_color_rate')[0].reset();
        $("#form_add_item_color_rate select").select2("val", "");
        $('#form_add_item_color_rate :radio').iCheck('update');
        $("#form_add_item_color_rate").validate().resetForm();
        notification(obj);
        $('#item_color_rate_table').DataTable().ajax.reload();
    }
});


// ── Edit item color rate ──────────────────────────────────────────────────────
$("#form_edit_item_color_rate").validate({
    rules: {
        eff_date: { required: true, remote: {
            url: "<?=base_url('ajax_unique_supp_item_color_rate_eff_date')?>", type: "post",
            data: { item_dtl_id: function(){ return $("#item_dtl_id").val(); }, supplier: function(){ return $("#supplier2").val(); }, item_rate_id: function(){ return $("#item_rate_id").val(); } }
        }}
    }, messages: {}
});
$('#form_edit_item_color_rate').ajaxForm({
    beforeSubmit: function() { return $("#form_edit_item_color_rate").valid(); },
    success: function(returnData) {
        var obj = JSON.parse(returnData);
        notification(obj);
        $('#item_color_rate_table').DataTable().ajax.reload();
    }
});


// ── Delete detail row ─────────────────────────────────────────────────────────
$(document).on('click', '.delete1', function() {
    var $this = $(this);
    if (confirm("Are You Sure?")) {
        $.ajax({
            url: "<?= base_url('admin/del-receive-purchase-order-details-list') ?>",
            dataType: 'json', type: 'POST',
            data: {
                tab: $(this).attr('tab'), tab_pk: $(this).attr('tab-pk'),
                data_pk: $(this).attr('data-pk'), reference_tab: $(this).attr('reference-tab'),
                reference_pk: $(this).attr('reference-pk'), reference_data_pk: $(this).attr('reference-data-pk'),
                pod_total_add: $(this).attr('pod-total-add')
            },
            success: function(returnData) {
                $this.closest('tr').remove();
                notification(returnData);
                var li = returnData.line_items || {};
                $("#total_amount").val((parseFloat(li.total_amount)||0).toFixed(2));
                $("#delivery_charge").val((parseFloat(li.total_delivery_charges)||0).toFixed(2));
                var cgst = (parseFloat(li.total_cgst_amount)||0).toFixed(2);
                var sgst = (parseFloat(li.total_sgst_amount)||0).toFixed(2);
                $("#total_cgst_amount").val(cgst);
                $("#total_sgst_amount").val(sgst);
                $("#delivery_sgst_cgst_amount").val((parseFloat(cgst)+parseFloat(sgst)).toFixed(2));
                $("#net_amount").val(Math.round(parseFloat(li.net_amount)||0));
                $("#supp_po_details_table").DataTable().ajax.reload();
            },
            error: function(e) { console.log(e); }
        });
    }
});


// ── Delete generic ────────────────────────────────────────────────────────────
$(document).on('click', '.delete', function() {
    if (confirm('Are you sure?')) {
        $.ajax({
            url: "<?= base_url('ajax-del-row-on-table-and-pk') ?>",
            type: 'POST', dataType: 'json',
            data: {
                tab: $(this).attr('tab'), pk_name: $(this).attr('pk-name'),
                pk_value: $(this).attr('pk-value'), child: $(this).attr('child'),
                ref_table: $(this).attr('ref-table'), ref_pk_name: $(this).attr('ref-pk-name')
            },
            success: function(returnData) {
                notification(returnData);
                $('#item_color_rate_table').DataTable().ajax.reload();
                $('#item_color_table').DataTable().ajax.reload();
                $('#item_buy_code_table').DataTable().ajax.reload();
            },
            error: function(e, v) { console.log(e + v); }
        });
    }
});


// ── Print ─────────────────────────────────────────────────────────────────────
$("#print_all").click(function() {
    var poi = $("#supp_purchase_order_id").val();
    $.confirm({
        title: 'Choose!',
        content: 'Choose printing method',
        buttons: {
            printwithcode: {
                text: 'With code', btnClass: 'btn-blue',
                action: function() { window.open("<?= base_url() ?>admin/purchase-order-print-with-code/" + poi, "_blank"); }
            },
            printwithoutcode: {
                text: 'Without code', btnClass: 'btn-blue',
                action: function() { window.open("<?= base_url() ?>admin/purchase-order-print-without-code/" + poi, "_blank"); }
            },
            cancel: function() {}
        }
    });
});


// ── Notification ──────────────────────────────────────────────────────────────
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