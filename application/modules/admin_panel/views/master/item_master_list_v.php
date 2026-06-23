<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 24-02-2020
 * Time: 12:38
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Item Master | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="item master">

    <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">
    <!--iCheck-->
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start (Menu)-->
    <?php $this->load->view('components/left_sidebar'); //left side menu ?>
    <!-- sidebar left end (Menu)-->

    <!-- body content start-->
    <div class="body-content" style="min-height: 1500px;">

        <!-- header section start-->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end-->

        <!-- page head start-->
        <div class="page-head">
            <h3 class="m-b-less">Item Master</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Item Master </li>
                </ol>
            </div>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <div class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <?php
                                if($view_permission != 'block'){
                                    ?>
                                    <a href="<?= base_url('admin/add_item') ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add Item</a>
                                <?php
                            }
                            ?>


                            <table id="item_master_table" class="table data-table dataTable">
                                <thead>
                                <tr>
                                    <th>Item Group</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Hsn Code</th>
                                    <th>Item Unit</th>
                                    <th>Item Type</th>
                                    <th>Exist in Costing</th>
                                    <th>Img</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Quick Edit Item Section -->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Quick Edit Item
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">

                            <div class="form-group">
                                <div class="col-lg-5">
                                    <label class="control-label"><b>Item Code</b></label>
                                    <input type="hidden" id="qe_code_select" style="width:100%">
                                    <p class="help-block">Type item code or name to search.</p>
                                </div>
                            </div>

                            <div id="qe_form_section" style="display:none">
                                <hr>
                                <h5 id="qe_form_label" class="text-primary" style="font-weight:bold; margin-bottom:15px;"></h5>

                                <form id="form_quick_edit_item" method="post" enctype="multipart/form-data" action="<?=base_url('admin/form_edit_item')?>" class="cmxform form-horizontal tasi-form">

                                    <div class="form-group">
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Item Group *</label>
                                            <select id="qe_item_group" name="item_group" required class="qe-select2 form-control round-input">
                                                <option value="" group_code="">Select Item Group</option>
                                                <?php foreach($item_groups as $val): ?>
                                                    <option value="<?=$val['ig_id']?>" group_code="<?=$val['ig_code']?>"><?=$val['group_name']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Item Code *</label>
                                            <input id="qe_item_code" name="item_code" type="text" placeholder="Item code" required class="form-control round-input"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Size *</label>
                                            <select id="qe_size" name="size" required class="qe-select2 form-control round-input">
                                                <option value="">Select size</option>
                                                <?php foreach($sizes as $val): ?>
                                                    <option value="<?=$val['sz_id']?>"><?=$val['size']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Shape *</label>
                                            <select id="qe_shape" name="shape" required class="qe-select2 form-control round-input">
                                                <option value="">Select shape</option>
                                                <?php foreach($shapes as $val): ?>
                                                    <option value="<?=$val['sh_id']?>"><?=$val['shape']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Unit *</label>
                                            <select id="qe_unit" name="unit" required class="qe-select2 form-control round-input">
                                                <option value="">Select unit</option>
                                                <?php foreach($units as $val): ?>
                                                    <option value="<?=$val['u_id']?>"><?=$val['unit'].' - '.$val['info']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">Description I</label>
                                            <input id="qe_desc1" name="desc1" type="text" placeholder="First description" class="form-control round-input"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">Description II</label>
                                            <input id="qe_desc2" name="desc2" type="text" placeholder="Second description" class="form-control round-input"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Item Name *</label>
                                            <input id="qe_item_name" name="item_name" type="text" placeholder="Item name" required class="form-control round-input"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Item Type *</label>
                                            <select id="qe_item_type" name="item_type" required class="qe-select2 form-control round-input">
                                                <option value="">Select type</option>
                                                <option value="None">None</option>
                                                <option value="Local">Local</option>
                                                <option value="Import">Import</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">Thickness</label>
                                            <input id="qe_thick" name="thick" type="text" placeholder="Thickness" class="form-control round-input"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">Buy Code</label>
                                            <input id="qe_buy_code" name="buy_code" type="text" placeholder="Buy code" class="form-control round-input"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">HSN Code</label>
                                            <input id="qe_hsn_code" name="hsn_code" type="text" placeholder="HSN Code" class="form-control round-input"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Enlist in Jobber? *</label><br>
                                            <input type="radio" name="jobber" id="qe_jobber_yes" value="1" required class="iCheck-square-green">
                                            <label for="qe_jobber_yes" class="control-label">Yes</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="jobber" id="qe_jobber_no" value="0" required class="iCheck-square-red">
                                            <label for="qe_jobber_no" class="control-label">No</label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Enlist in Costing? *</label><br>
                                            <input type="radio" name="show_in_costing" id="qe_costing_yes" value="1" required class="iCheck-square-green">
                                            <label for="qe_costing_yes" class="control-label">Yes</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="show_in_costing" id="qe_costing_no" value="0" required class="iCheck-square-red">
                                            <label for="qe_costing_no" class="control-label">No</label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label text-danger">Status *</label><br>
                                            <input type="radio" name="status" id="qe_status_enable" value="1" required class="iCheck-square-green">
                                            <label for="qe_status_enable" class="control-label">Enable</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="status" id="qe_status_disable" value="0" required class="iCheck-square-red">
                                            <label for="qe_status_disable" class="control-label">Disable</label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label">Image</label>
                                            <input type="file" id="qe_img" name="img" accept=".jpg,.jpeg,.png" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-12 text-center">
                                            <input type="hidden" id="qe_item_id" name="item_id" value="">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> Update Item</button>
                                        </div>
                                    </div>

                                </form>
                            </div><!-- /#qe_form_section -->

                        </div><!-- /.panel-body -->
                    </section>
                </div>
            </div>
            <!-- /Quick Edit Item Section -->

        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <?php $this->load->view('components/footer'); ?>
        <!--footer section end-->

    </div>
    <!-- body content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?=base_url()?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>

<!-- common js -->
<?php $this->load->view('components/_common_js'); //left side menu ?>

<!--Data Table-->
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
<!--data table init-->
<script src="<?=base_url()?>assets/admin_panel/js/data-table-init.js"></script>

<script>
    $(document).ready(function() {
        $('#item_master_table').DataTable( {
            "stateSave": true,
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('ajax_item_master_table_data')?>",
                "type": "POST",
                "dataType": "json",
            },
            //will get these values from JSON 'data' variable
            "columns": [
                { "data": "group_name" },
                { "data": "im_code" },
                { "data": "item" },
                { "data": "hsn_code" },
				{ "data": "item_unit" },
				{ "data": "item_type" },
				{ "data": "exist_in_costing" },
				{ "data": "img" },
                { "data": "status" },
                { "data": "action" },
            ],
            //column initialisation properties
            "columnDefs": [{
                "targets": [9], //disable 'Actions' column sorting
                "orderable": false,
            }]
        } );
    } );
    // delete area
    $(document).on('click', '.delete', function(){
        if(confirm('Are you sure?')){
            $tab = $(this).attr('tab');
            $pk_name = $(this).attr('pk-name');
            $pk_value = $(this).attr('pk-value');
            $child = $(this).attr('child');
            $ref_table = $(this).attr('ref-table');
            $ref_pk_name = $(this).attr('ref-pk-name');

            $.ajax({
                url: "<?= base_url('ajax-del-row-on-table-and-pk') ?>",
                type: 'POST',
                dataType: 'json',
                data:{tab: $tab, pk_name: $pk_name, pk_value: $pk_value, child: $child, ref_table: $ref_table, ref_pk_name: $ref_pk_name},
                success: function(returnData){
                    // console.log(returnData);
                    notification(returnData);
                    $('#item_master_table').DataTable().ajax.reload();
                },
                error: function(e,v){
                    console.log(e + v);
                }
            });
        }
    })
    // delete area ends
     //toastr notification
     function notification(obj) {
        // console.log(obj);
        toastr[obj.type](obj.msg, obj.title, {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "500",
            "timeOut": "10000",
            "extendedTimeOut": "5000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        })
    }
</script>

<!--Select2-->
<script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
<!--Icheck-->
<script src="<?=base_url();?>assets/admin_panel/js/icheck/skins/icheck.min.js"></script>
<script src="<?=base_url();?>assets/admin_panel/js/icheck-init.js"></script>
<!--form validation-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.validate.min.js"></script>
<!--ajax form submit-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.form.min.js"></script>

<script>
    // Init select2 for form dropdowns (select2 v3 — no dropdownParent needed)
    $('.qe-select2').select2();

    // Item code search — select2 v3 AJAX
    $('#qe_code_select').select2({
        placeholder: 'Type item code or name to search...',
        minimumInputLength: 1,
        ajax: {
            url: '<?= base_url("ajax_search_item_codes_for_quick_edit") ?>',
            type: 'POST',
            dataType: 'json',
            data: function(term, page) {
                return { q: term };
            },
            results: function(data) {
                return { results: data };
            }
        }
    });

    // When an item is chosen — fetch full record and populate the edit form
    $('#qe_code_select').on('change', function() {
        var im_id = $(this).select2('val');
        if (!im_id) { $('#qe_form_section').hide(); return; }

        $.ajax({
            url: '<?= base_url("ajax_fetch_item_master_for_quick_edit") ?>',
            type: 'POST',
            dataType: 'json',
            data: { im_id: im_id },
            success: function(d) {
                if (!d || !d.im_id) { $('#qe_form_section').hide(); return; }

                $('#qe_form_label').text('Editing: ' + d.item + ' [' + d.im_code + ']');
                $('#qe_item_id').val(d.im_id);
                $('#qe_item_code').val(d.im_code);
                $('#qe_desc1').val(d.info_1);
                $('#qe_desc2').val(d.info_2);
                $('#qe_item_name').val(d.item);
                $('#qe_thick').val(d.thick);
                $('#qe_buy_code').val(d.buy_code);
                $('#qe_hsn_code').val(d.hsn_code);

                // select2 v3: set value with select2('val', id)
                $('#qe_item_group').select2('val', d.ig_id);
                $('#qe_size').select2('val', d.sz_id);
                $('#qe_shape').select2('val', d.sh_id);
                $('#qe_unit').select2('val', d.u_id);
                $('#qe_item_type').select2('val', d.type);

                // iCheck radios
                if (d.enlist_jobber == '1')  { $('#qe_jobber_yes').iCheck('check');    } else { $('#qe_jobber_no').iCheck('check');      }
                if (d.enlist_costing == '1') { $('#qe_costing_yes').iCheck('check');   } else { $('#qe_costing_no').iCheck('check');     }
                if (d.status == '1')         { $('#qe_status_enable').iCheck('check'); } else { $('#qe_status_disable').iCheck('check'); }

                $('#qe_img').val('');
                $('#qe_form_section').show();

                $('html, body').animate({ scrollTop: $('#qe_form_section').offset().top - 60 }, 400);
            }
        });
    });

    // Quick edit form submit via AJAX (multipart for image upload support)
    $('#form_quick_edit_item').ajaxForm({
        beforeSubmit: function() {
            if ($('#qe_item_code').val() == '' || $('#qe_item_name').val() == '') {
                alert('Item Code and Item Name are required.');
                return false;
            }
            return true;
        },
        success: function(returnData) {
            var obj = JSON.parse(returnData);
            notification(obj);
            if (obj.type == 'success') {
                $('#item_master_table').DataTable().ajax.reload();
            }
        }
    });
</script>

</body>
</html>
