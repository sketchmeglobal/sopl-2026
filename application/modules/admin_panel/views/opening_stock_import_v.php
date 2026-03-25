<?php
/**
    * Coded by: Pran Krishna Das
    * Social: www.fb.com/pran93
    * CI: 3.0.6
    * Date: 09-07-17
    * Time: 14:00
    */
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <title>  Excel Import | <?= WEBSITE_NAME ?>  </title>
    <meta name="keyword" content="profile">
    <meta name="description" content="update profile">
    
    <!--Form Wizard-->
    <link href="<?= base_url() ?>assets/admin_panel/css/jquery.steps.css" rel="stylesheet" type="text/css" />
    
    <!--iCheck-->
    <link href="<?= base_url() ?>assets/admin_panel/js/icheck/skins/all.css" rel="stylesheet">
    
    <!--bootstrap picker-->
    <link href="<?= base_url() ?>assets/admin_panel/js/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
    
    <!--Select2-->
    <link href="<?= base_url() ?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">
    
    <!-- common head -->
    <?php $this->load->view("components/_common_head"); ?>
    <!-- /common head -->
    
    
    <style>
        .upload_excel,.upload_excel_virtual { width: 20%;border: none;padding: 1%;}
        p{margin:0}
    </style>
    
    
    </head>
    
    <body class="sticky-header">
    
        <noscript>
        <meta http-equiv="refresh" content="0; URL=<?= base_url() ?>js_disabled">
        </noscript>
    
        <section>
            <!-- sidebar left start (Menu)-->
            <?php $this->load->view("components/left_sidebar"); ?>
            <!-- sidebar left end (Menu)-->
        
            <!-- body content start-->
            <div class="body-content" style="min-height: 1500px;">
        
                <!-- header section start-->
                <?php $this->load->view("components/top_menu"); ?>
                <!-- header section end-->
        
                <!-- page head start-->
                <div class="page-head">
                    <h3 class="m-b-less">Excel Import</h3>
                </div>

                <div class="wrapper">

                    <!-- IMPORT EXCEL FILE -->
                    <div class="row">
                        <div class="col-lg-12">
                            <section class="panel">
                                <header class="panel-heading">
                                    <p>Import Excel File</p>
                                    <span class="tools pull-right">
                                        <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    </span>
                                </header>
                                <div class="panel-body">
                                    <div class="col-sm-12 text-center">
                                    <?php if ($this->session->flashdata("success_message") != "") { ?>
                                    <br/><br/>
                                    <h4 class="alert alert-success">
                                        <?= $this->session->flashdata("success_message") ?>
                                    </h4>
                                    <?php } ?>
                                </div>
                                <div class="form">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="col-sm-12">
                                            <label >Upload Excel Sheet : <span style="color: red;">*</span></label>
                                            <input type="file" name="uploadFile" value="" required/>
                                            <p style="color: red;">[Note: Excel File Structure Should Be Same As Downloaded From Stock Summary Report(2021-22) Folder]</p>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <input type="submit" class="btn-success upload_excel" name="submit" value="Upload" />         
                                        </div>
                                    </form>
                                </div>
                            <!-- </div> -->
                            </section>
                        </div>
                    </div>

                    <!-- IMPORT VIRTUAL/SUPLIMENTARY AREA -->
                    <div class="row">
                        <div class="col-lg-12">
                            <section class="panel">
                                <header class="panel-heading">
                                    <p>Import Excel File (Supplementary)</p>
                                    <span class="tools pull-right">
                                        <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    </span>
                                </header>
                                <div class="panel-body">
                                    <div class="col-sm-12 text-center">
                                    <?php if ($this->session->flashdata("virtual_success_message") != "") { ?>
                                        <br/><br/>
                                        <h4 class="alert alert-success">
                                            <?= $this->session->flashdata("virtual_success_message") ?>
                                        </h4>
                                    <?php } ?>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                    <?php if ($this->session->flashdata("virtual_error_message") != "") { ?>
                                        <br/><br/>
                                        <h4 class="alert alert-danger">
                                            <?= $this->session->flashdata("virtual_error_message") ?>
                                        </h4>
                                    <?php } ?>
                                    </div>
                                </div>  
                                <div class="form">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="col-sm-12">
                                            <label >Upload Excel Sheet : <span style="color: red;">*</span></label>
                                            <input type="file" name="uploadFile" value="" required/>
                                            <p style="color: red;">[Note: Excel File Structure Should Be Same As Downloaded From Stock Summary Report(<?=(date('Y') - 1)?> - <?=date('y')?>) Folder]</p>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <input type="submit" class="btn-success upload_excel_virtual" name="submit_virtual" value="Upload" />         
                                        </div>
                                    </form>
                                </div>
                            <!-- </div> -->
                            </section>
                        </div>
                    </div>

                </div>
                <!--body wrapper end-->
            
                <!--footer section start-->
                <?php $this->load->view("components/footer"); ?>
                <!--footer section end-->
        
            </div>
        <!-- body content end-->
        </section>
    
        <!-- Placed js at the end of the document so the pages load faster -->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery-migrate.js"></script>
        
        <!--form validation-->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery.validate.min.js" type="text/javascript"></script>
        <!--form validation init-->
        <script src="<?= base_url() ?>assets/admin_panel/js/form-validation-init.js"></script>
        
        <!--Form Wizard-->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery.steps.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery.validate.min.js" type="text/javascript"></script>
        <!--wizard initialization-->
        <script src="<?= base_url() ?>assets/admin_panel/js/wizard-init.js" type="text/javascript"></script>
        
        <!--ajax form submit-->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery.form.min.js"></script>
        <!--ajax form submit init-->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery.form-init.js"></script>
    
        <!-- common js -->
        <?php $this->load->view("components/_common_js");?>
        <!-- /common js -->
        
    </body>
</html>