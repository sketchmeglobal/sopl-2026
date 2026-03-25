<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 11-03-2020
 * Time: 09:55
 */
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Splitting Bill | <?=WEBSITE_NAME;?></title>
    <meta name="description" content="Edit Splitting Bill">

     <!--Data Table-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>

    <!--Select2-->
    <link href="<?=base_url();?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?=base_url();?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!--iCheck-->
    <link href="<?=base_url();?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        text-align: right;
        }

        /* Firefox */
        input[type=number] {
            text-align: right;
            -moz-appearance: textfield;
            width: 100px;
        }

        .border-black-bottom{border-bottom: 1px dotted #000}
    </style>
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
            <h3 class="m-b-less">Edit Splitting Bill</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?=base_url('admin/dashboard');?>">Home</a></li>
                    <li class="active"> Edit Splitting Bill </li>
                </ol>
            </div>
        </div>
        <!-- page head end-->

        <!--body wrapper start-->
        <div class="wrapper">

            <!--Edit Article Costing-->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Edit <?= $splitting_bill_details[0]->bill_number ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                        <?php #print_r($purchase_order_details); die;?>
                            <form id="form_edit_splitting_bill" method="post" action="<?=base_url('admin/form-edit-splitting-bill')?>" class="cmxform form-horizontal tasi-form">
                                <div class="form-group ">
                                    <div class="col-lg-4">
                                        <label for="splitting_bill_number" class="control-label text-danger">Splitting Bill Number *</label>
                                        <input value="<?=$splitting_bill_details[0]->bill_number?>" id="splitting_bill_number" required name="splitting_bill_number" type="text" placeholder="Splitting Bill Number" class="form-control round-input" />
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="splitting_bill_date" class="control-label text-danger">Splitting Bill Date *</label>
                                        <input value="<?=date('Y-m-d', strtotime($splitting_bill_details[0]->bill_date))?>" id="splitting_bill_date" required name="splitting_bill_date" type="date" placeholder="Splitting Bill Date" class="form-control round-input" />
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <label for="splitting_bill_type" class="control-label text-danger">Select Type *</label>
                                        <select name="splitting_bill_type" id="splitting_bill_type" class="form-control">
                                            <option <?= ($splitting_bill_details[0]->bill_rate_type == 'sample') ? 'selected' : '' ?> value="sample">Sample rate</option>
                                            <option <?= ($splitting_bill_details[0]->bill_rate_type == 'production') ? 'selected' : '' ?> value="production">Production rate</option>
                                        </select>
                                    </div>
                                </div>
                                                               
                                <div class="form-group ">
                                    <div class="col-lg-4">
                                        <label for="skiver_name" class="control-label text-danger">Select Employee*</label>
                                        <select name="skiver_name" id="skiver_name" class="form-control">
                                            <!--<option value="">Select Cutter</option>-->
                                            <?php
                                            foreach($all_employees as $ac){
                                                ?>
                                                <option <?= ($splitting_bill_details[0]->bill_employee_id == $ac->e_id) ? 'selected' : '' ?> value="<?= $ac->e_id ?>"><?= $ac->name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="splitting_bill_reamark" class="control-label">Remarks</label>
                                        <textarea id="splitting_bill_remark" name="splitting_bill_remark" placeholder="Remark, if any" class="form-control round-input"><?=$splitting_bill_details[0]->bill_remarks?></textarea>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="control-label text-danger">Status *</label><br />
                                        <input type="radio" name="status" id="enable" value="1" checked required class="iCheck-square-green">
                                        <label for="enable" class="control-label">Enable</label>

                                        <input type="radio" name="status" id="disable" value="0" required class="iCheck-square-red">
                                        <label for="disable" class="control-label">Disable</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-6">
                                    <input type="hidden" id="splitting_bill_id" name="splitting_bill_id" class="hidden" value="<?=$splitting_bill_id?>" />
                                    <button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus"> Update Splitting Bill</i></button>
                                    </div>
                                    <!--<div class="col-lg-6">-->
                                    <!--    <a id="edit_btn" href="javascript:void(0)" class="hidden btn btn-info"><i class="fa fa-pencil"> Edit Splitting Issue Challan</i></a>-->
                                    <!--</div>-->
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
           
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Add Splitting Bill details for: <?= $splitting_bill_details[0]->bill_number ?>
                            <span class="tools pull-right">
                                <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <!--Tabs-->
                            <ul id="splitting_bill_details_tabs" class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#splitting_bill_details_list" data-toggle="tab">List</a></li>
                                <li><a href="#splitting_bill_details_add" data-toggle="tab">Add</a></li>
                                <!--<li id="splitting_bill_edit_tab" class="disabled"><a href="#splitting_bill_edit" data-toggle="">Edit</a></li>-->
                            </ul>
                            <!--Tab Content-->
                            <div class="tab-content">
                                <img id="edit_loader" class="hidden" style="display:block; margin: auto" src="<?= base_url('assets/img/ellipsis.gif') ?>" alt="" />
                                <div id="splitting_bill_details_list" class="tab-pane fade in active">
                                    <table id="splitting_bill_details_table" class="table data-table dataTable">
                                        <thead>
                                            <tr>
                                                <th>Bill Number</th>
                                                <th>Order Number</th>
                                                <!--<th>Splitting Issue Number</th>-->
                                                <th>Article Number</th>
                                                <th>Leather Color</th>
                                                <th>Paid Quantity</th>
                                                <th>Rate</th>
                                                <th>Part</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div id="splitting_bill_details_add" class="tab-pane fade">
                                    <br/>
                                    <div class="form">
                                        <form id="form_add_splitting_bill_details" method="post" action="<?=base_url('admin/form-add-splitting-bill-details')?>" class="cmxform form-horizontal tasi-form">
                                            <div class="form-group ">
                                                <div class="col-lg-3">
                                                    <label for="co_id" class="control-label text-danger">Customer Order *</label>
                                                    <select name="co_id" id="co_id" class="form-control select2">
                                                        <option value="">Select from the list</option>
                                                        <?php foreach($co_ids as $co){ ?>
                                                        <option value="<?=$co->co_id?>"><?=$co->co_no?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="article_id" class="control-label text-danger">Select Article No.</label>
                                                    <select name="article_id" id="article_id" class="form-control"></select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="rcv_qnty" class="control-label text-danger">Original Quantity</label>
                                                    <input readonly type="text" class="form-control" id="rcv_qnty" value="" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="pending_qnty" class="control-label text-danger">Pending Quantity</label>
                                                    <input type="text" name="pending_qnty" class="form-control" id="pending_qnty" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-lg-3">
                                                    <label for="splitting_rate" class="control-label text-danger">Rate</label>
                                                    <input type="text" name="splitting_rate" class="form-control" id="splitting_rate" value="" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="splitting_part" class="control-label text-danger">Part</label>
                                                    <input type="text" name="splitting_part" class="form-control" id="splitting_part" value="1" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="splitting_total" class="control-label text-danger">Total</label>
                                                    <input readonly type="text" class="form-control" id="splitting_total" name="splitting_total" value="" />
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-lg-7">
                                                            <label for="master_part_update" class="control-label text-danger">Update part master</label><br>
                                                            <select name="master_part_update" id="master_part_update" class="form-control">
                                                                <option value="yes">Yes</option>
                                                                <option selected value="no">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <label for="submit" class="control-label text-danger">Action</label><br>
                                                            <input type="hidden" name="sb_id_val" id="sb_id_val">
                                                            <input type="hidden" name="color_val" id="color_val">
                                                            <button class="btn btn-success" type="submit"><i class="fa fa-plus"> Submit</i></button>
                                                        </div>
                                                    </div>
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
<!--Select2-->
<script src="<?=base_url();?>assets/admin_panel/js/select2.js" type="text/javascript"></script>
<script>
    $('.select2').select2();
</script>
<!--Icheck-->
<script src="<?=base_url();?>assets/admin_panel/js/icheck/skins/icheck.min.js"></script>
<script src="<?=base_url();?>assets/admin_panel/js/icheck-init.js"></script>
<!--form validation-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.validate.min.js"></script>
<!--ajax form submit-->
<script src="<?=base_url();?>assets/admin_panel/js/jquery.form.min.js"></script>


<script>
    
    $(document).ready(function() {

        $("#form_edit_splitting_bill").validate({
            rules: {
                splitting_bill_number: {
                    required: true
                },
                splitting_bill_date: {
                    required: true
                },
                splitting_bill_type:{
                    required: true
                },
                skiver_name: {
                    required: true
                }
            },
            messages: {}
        });
        $('#form_edit_splitting_bill').ajaxForm({
            beforeSubmit: function () {
                return $("#form_edit_splitting_bill").valid(); // TRUE when form is valid, FALSE will cancel submit
            },
            success: function (returnData) {
                obj = JSON.parse(returnData);
                notification(obj);
                //console.log(returnData);
            }
        });

        $('#splitting_bill_details_table').DataTable( {
            dom: 'Bfrltip',
            buttons: [ 
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }
                }, 
                {
                    extend: 'pdfHtml5', 
                    title: 'SHILPA OVERSEAS PVT. LTD. || Splitting Bill', 
                    exportOptions: { columns: [0,1,2,3,4,5,6,7] },
                    customize: function(doc) {
                        doc.styles.title = { bold: !0, color: 'black', fontSize: '11', background: 'white', alignment: 'center' }  
                        doc.styles.tableHeader.fontSize = 8;
                        doc.styles.tableFooter.fontSize = 8;
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center'; 
                    },
                    footer: true,
                    
                }
            ],
            "processing": true,
            "language": {
                processing: '<img src="<?=base_url('assets/img/ellipsis.gif')?>"><span class="sr-only">Processing...</span>',
            },
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('admin/ajax-splitting-bill-details-table-data')?>",
                "type": "POST",
                "dataType": "json",
                "data": {
                    splitting_bill_id: function () {
                        return $("#splitting_bill_id").val();
                    },
                },
            },
            //will get these values from JSON 'data' variable
            "columns": [
                { "data": "bill_no" },
                { "data": "co_no" },
                { "data": "am_id" },
                { "data": "lc_id" },
                { "data": "qnty" },
                { "data": "rate" },
                { "data": "splitting_part" },
                { "data": "total" },
                { "data": "action" }
            ],
            //column initialisation properties
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };
        
                totalqnty = api.column(7).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                // pageTotalqnty = api.column(7, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                api.column(7).footer().innerHTML = ' (Page Total ' + totalqnty + ')';

            }
        } );  
    });
    
    $("#form_add_splitting_bill_details").validate({
        rules: {
            pending_qnty: {
                required: true,
            },
            splitting_rate: {
                required: true,
            },
            splitting_total: {
                required: true,
            }           
        },
        messages: {

        }
    });
    $('#form_add_splitting_bill_details').ajaxForm({
        beforeSubmit: function () {
            return $("#form_add_splitting_bill_details").valid(); // TRUE when form is valid, FALSE will cancel submit
        },
        success: function (returnData) {
            console.log('RD => ' + returnData);
            obj = JSON.parse(returnData);
            notification(obj);

            //refresh table
            $('#splitting_bill_details_table').DataTable().ajax.reload();
            $("#add_details").attr("disabled", true);

            // $('#splitting_issue_id').val('first').trigger('change.select2');
            // $('#article_id').val('first').trigger('change.select2');
            $("#article_id").select2('open')

            $("#rcv_qnty").val("")
            $("#pending_qnty").val("")
            $("#splitting_rate").val("")
            $("#splitting_total").val("")
        }
    });


    $(document).on('change','#co_id', function(){
        $("#article_id").select2()
        co_id = $("#co_id").val()
        // cut_rcv_id = $(this).val()
        $.ajax({
            "async": false,
            "url": "<?=base_url('admin/ajax-article-dtl-on-co_id-splitting')?>",
            "type": "POST",
            "dataType": "json",
            "data": {co_id: co_id},
            success: function(rdata){
                console.log("Success: ");
                console.log(rdata);
                $("#article_id").html("<option value=''>Select from the list</option>");
                $.each(rdata, function(index, item) {
                    unique_am_id = item.am_id+'#'+item.lc_id // unique value for am_id for select2 to work
                    $html = "<option data-color="+item.lc_id+" data-qnty ="+item.co_quantity+" data-am_id="+item.am_id+" data-splitting_part="+item.splitting_part+" value="+unique_am_id+">"+item.art_no+ " - " + item.color +"</option>";
                    $("#article_id").append($html)
                });
                $("#article_id").select2('open')
            },
            error: function(rdata){
                console.log("Error: ");
                console.log(rdata);
            },
            done: function(){}
        })
    })

    $(document).on('change','#article_id', function(){

        // $this = $(this);
        co_id = $("#co_id").find("option:selected").val()
        am_id = $(this).find(":selected").data('am_id')
        color = $(this).find("option:selected").data('color')
        original_qnty = $(this).find("option:selected").data('qnty')
        splitting_part = $(this).find("option:selected").data('splitting_part')

        $("#sb_id_val").val(<?=$this->uri->segment(3)?>)
        $("#color_val").val(color)
        bill_type = $("#splitting_bill_type").find("option:selected").val()
        // if(bill_type == 'sample'){
        //     rate = $(this).find("option:selected").data('sample-rate')
        // }else{
        //     rate = $(this).find("option:selected").data('production-rate')
        // }
        
        rate = 0.10;

        $("#rcv_qnty").val(original_qnty)
        $("#splitting_rate").val(rate)
        $("#splitting_part").val(splitting_part);
        
        $.ajax({
            "async": false,
            "url": "<?=base_url('admin/ajax-fetch-splitting-bill-pending-qnty')?>",
            "type": "POST",
            "dataType": "json",
            "data": {co_id: co_id, am_id: am_id, color:color},
            success: function(used_qnty){
                // console.log("Success: ");
                console.log(' used -> ' + used_qnty + ' original -> ' + original_qnty);
                pending_qnty = (parseInt(original_qnty) - parseInt(used_qnty))
                $("#pending_qnty").attr('max', pending_qnty)
                $("#pending_qnty").attr('min', 1)
                $("#pending_qnty").val(pending_qnty)

                total = parseInt(pending_qnty) * parseFloat(rate) * parseInt(splitting_part)
                $("#splitting_total").val(total)
                $("#pending_qnty").focus().select()
            },
            error: function(rdata){
                console.log("Error: ");
                console.log(rdata);
            },
            done: function(){
                find_total()
            }
        })
        
    })
    
    $(document).on('blur','#pending_qnty', function(){
        find_total();
    })
    $(document).on('blur','#splitting_rate', function(){
        find_total();
    })
    $(document).on('blur','#splitting_part', function(){
        find_total();
    })

    function find_total(){
        qnty = $("#pending_qnty").val()
        rate = $("#splitting_rate").val()
        splitting_part = $("#splitting_part").val()
        total = parseInt(qnty) * parseFloat(rate) * parseInt(splitting_part)
        $("#splitting_total").val(total)
    }

    //toastr notification
    function notification(obj) {
        toastr[obj.type](obj.msg, obj.title, {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "7000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        })
    }

// delete area 
    
    $(document).on('click', '.delete', function(){
        
       $this = $(this);
        if(confirm("Are You Sure?")){
            $tab = $(this).attr('tab');
            $tab_pk = $(this).attr('pk-name');
            $tab_val = $(this).attr('pk-value');

            $.ajax({
                url: "<?= base_url('admin/del-splitting-bill-details') ?>",
                dataType: 'json',
                type: 'POST',
                data: {tab: $tab, tab_pk : $tab_pk, tab_val: $tab_val},
                success: function (returnData) {
                    console.log(returnData);
                    $this.closest('tr').remove();
                    //obj = JSON.parse(returnData);
                    notification(returnData);
                    
                    //refresh table
                    $("#splitting_bill_details_table").DataTable().ajax.reload();

                },
                error: function (returnData) {
                    obj = JSON.parse(returnData);
                    notification(obj);
                }
            });
        }  
    });
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });    

</script>

</body>
</html>
