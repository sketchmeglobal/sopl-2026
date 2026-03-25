<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dropdown Restriction | <?= WEBSITE_NAME ?></title>
        <meta name="description" content="Dropdown Restriction Settings">
        
        <!--Data Table-->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin_panel/js/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin_panel/js/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/admin_panel/js/DataTables/Responsive-2.2.2/css/responsive.bootstrap.min.css"/>
        
        <!-- Multi-select CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css">
        
        <!-- Multi-select CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css">
        
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- common head -->
        <?php $this->load->view("components/_common_head"); ?>
        <!-- /common head -->
        
        <style>
            .page-head{padding:15px 20px;background:#f5f5f5;position:relative;top:-48px;border-bottom:2px solid #4bc17d;}
            .panel{box-shadow:0 1px 3px rgba(0,0,0,0.1);margin-bottom:15px;}
            .panel-heading{padding:10px 15px;background:#fff;border-bottom:1px solid #e7e7e7;}
            .panel-body{padding:15px;}
            h3{font-size:20px;margin:0;font-weight:600;color:#333;}
            h4{font-size:14px;}
            .btn-success{margin-top:10px;}
            
            /* Compact form styling */
            .form-group{margin-bottom:12px;}
            .form-group label{margin-bottom:5px;font-weight:600;color:#555;display:block;}
            .form-control{height:36px;font-size:13px;padding:6px 12px;}
            
            /* Multi-select compact */
            .ms-container{width:100%;margin-top:8px;}
            .ms-container .ms-list{height:280px;}
            .ms-container .ms-selectable li.ms-elem-selectable,
            .ms-container .ms-selection li.ms-elem-selection{font-size:12px;padding:4px 8px;}
            .ms-container .ms-selectable li.ms-elem-selectable{background:#f8f8f8;color:#333;}
            .ms-container .ms-selection li.ms-elem-selection{background:#4bc17d;color:#fff;}
            .custom-header{background:#4bc17d;color:#fff;padding:6px;text-align:center;font-weight:600;font-size:12px;}
            
            #searchInput{margin-bottom:8px;padding:6px 10px;width:100%;border:1px solid #ddd;border-radius:3px;font-size:12px;}
            
            /* Status badges compact */
            .status-box{text-align:center;padding:12px;background:#f9f9f9;border-radius:4px;}
            .status-box strong{display:block;font-size:11px;color:#666;margin-bottom:5px;text-transform:uppercase;}
            .status-box .label{font-size:18px;padding:5px 12px;}
            
            /* Table styling */
            .table{font-size:12px;margin-bottom:0;}
            .table th{font-size:12px;font-weight:600;background:#f5f5f5;padding:8px;}
            .table td{padding:8px;vertical-align:middle;}
            
            /* Buttons */
            .btn-sm{padding:4px 10px;font-size:11px;}
            .dt-buttons{display:inline-block;float:right;margin-bottom:10px;}
            .buttons-excel,.buttons-pdf{font-size:11px;padding:5px 10px;border-radius:3px;}
            .buttons-excel{background:#9c78cd;color:#fff;border:none;margin-left:5px;}
            .buttons-pdf{background:#5cc691;color:#fff;border:none;margin-left:5px;}
            
            /* DataTable elements */
            .dataTables_wrapper{font-size:12px;}
            .dataTables_filter{float:right;margin-bottom:10px;}
            .dataTables_filter input{height:30px;padding:5px 10px;font-size:12px;}
            .dataTables_length{float:left;}
            .dataTables_length select{height:30px;padding:5px;font-size:12px;}
            .dataTables_info{float:left;padding:8px 0;font-size:12px;}
            .dataTables_paginate{float:right;font-size:12px;}
            
            .sub-title{font-size:12px;color:#777;margin-top:3px;display:block;}
            .alert{padding:10px 15px;font-size:13px;margin-bottom:15px;}
            
            /* Add to your existing style section */
            #no_articles_msg {
                font-size: 12px;
                padding: 10px 12px;
                border-radius: 3px;
            }
            /* Loading Overlay */
            .loading-overlay{
                display:none;
                position:fixed;
                top:0;
                left:0;
                width:100%;
                height:100%;
                background:rgba(0,0,0,0.6);
                z-index:9999;
                justify-content:center;
                align-items:center;
            }
            .loading-overlay.show{display:flex;}
            .loading-content{
                background:#fff;
                padding:30px 40px;
                border-radius:8px;
                text-align:center;
                box-shadow:0 4px 20px rgba(0,0,0,0.3);
            }
            .spinner{
                border:4px solid #f3f3f3;
                border-top:4px solid #4bc17d;
                border-radius:50%;
                width:50px;
                height:50px;
                animation:spin 1s linear infinite;
                margin:0 auto 15px;
            }
            @keyframes spin{
                0%{transform:rotate(0deg);}
                100%{transform:rotate(360deg);}
            }
            .loading-text{
                font-size:14px;
                color:#333;
                font-weight:600;
            }
            
            /* Compact panels */
            .panel-heading .tools{margin-top:-2px;}
            .panel-heading h4{margin:0;font-size:14px;font-weight:600;color:#333;}
            
            /* Better spacing */
            .wrapper{padding:0 20px 20px;}
            .row{margin:0 -10px;}
            .col-md-4{padding:0 10px;}
            
            /* Select2 Custom Styling */
            .select2-container{width:100%!important;}
            .select2-container .select2-selection--single{
                height:36px;
                border:1px solid #ddd;
                border-radius:3px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height:34px;
                color:#555;
                font-size:13px;
                padding-left:12px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow{
                height:34px;
            }
            .select2-container--default .select2-results__option{
                font-size:13px;
                padding:6px 12px;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected]{
                background-color:#4bc17d;
            }
            .select2-container--default .select2-search--dropdown .select2-search__field{
                border:1px solid #ddd;
                font-size:13px;
                padding:6px 10px;
            }
            .select2-dropdown{
                border:1px solid #ddd;
                border-radius:3px;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder{
                color:#999;
            }
        </style>
    </head>
    
    <body class="sticky-header">
    
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-content">
                <div class="spinner"></div>
                <div class="loading-text">Loading Articles...</div>
            </div>
        </div>
    
        <section>
        
            <?php $this->load->view("components/left_sidebar");?>
        
            <!-- body content start-->
            <div class="body-content" style="min-height: 1500px;">
        
                <?php $this->load->view("components/top_menu"); ?>
               
                <div class="page-head">
                    <h3><i class="fa fa-filter"></i> Dropdown Restriction Settings</h3>
                    <span class="sub-title">Manage articles visibility in costing dropdown</span>
                </div>
                
                <div class="wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                                </div>
                            <?php } ?>
                            
                            <?php if($this->session->flashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                                </div>
                            <?php } ?>
                            
                            <!-- Status Panel -->
                            <section class="panel">
                                <header class="panel-heading">
                                    <h4><i class="fa fa-info-circle"></i> Current Status</h4>
                                    <!--<span class="tools pull-right">-->
                                    <!--    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>-->
                                    <!--</span>-->
                                </header>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="status-box">
                                                <strong>Total Articles</strong>
                                                <span class="label label-default"><?= count($articles) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="status-box">
                                                <strong>Hidden from Dropdown</strong>
                                                <span class="label label-danger" id="hidden_count">
                                                    <?php 
                                                    $hidden_count = 0;
                                                    $hidden_articles = array();
                                                    foreach($articles as $article) {
                                                        if($article->show_on_costing_dropdown == 0) {
                                                            $hidden_count++;
                                                            $hidden_articles[] = $article;
                                                        }
                                                    }
                                                    echo $hidden_count;
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="status-box">
                                                <strong>Visible in Dropdown</strong>
                                                <span class="label label-success" id="visible_count"><?= count($articles) - $hidden_count ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            
                            <!-- Currently Hidden Articles Table -->
                            <?php if($hidden_count > 0) { ?>
                            <section class="panel">
                                <header class="panel-heading">
                                    <h4><i class="fa fa-eye-slash"></i> Currently Hidden Articles</h4>
                                    <!--<span class="tools pull-right">-->
                                    <!--    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>-->
                                    <!--</span>-->
                                </header>
                                <div class="panel-body">
                                    <table class="table table-bordered table-striped" id="hidden_articles_table">
                                        <thead>
                                            <tr>
                                                <th width="60">Sr No.</th>
                                                <th>Art No</th>
                                                <th>Alt Art No</th>
                                                <th>Description</th>
                                                <th width="120">Group</th>
                                                <th width="80">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sr = 1;
                                            foreach($hidden_articles as $article) { ?>
                                            <tr id="row_<?= $article->am_id ?>">
                                                <td class="text-center"><?= $sr++ ?></td>
                                                <td><strong><?= $article->art_no ?></strong></td>
                                                <td><?= $article->alt_art_no ?></td>
                                                <td><?= $article->info ?></td>
                                                <td>
                                                    <?php
                                                    if(isset($article->ag_id) && $article->ag_id > 0) {
                                                        $group = $this->db->get_where('article_groups', array('ag_id' => $article->ag_id))->row();
                                                        echo $group ? $group->group_name : '-';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-success btn-sm remove-restriction" 
                                                            data-id="<?= $article->am_id ?>"
                                                            data-art="<?= $article->art_no ?>"
                                                            title="Make Visible">
                                                        <i class="fa fa-eye"></i> Show
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                            <?php } else { ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No articles are currently hidden. Use the form below to hide articles.
                            </div>
                            <?php } ?>
                            
                            
                            <!-- Add New Restrictions Form -->
                            <section class="panel">
                                <header class="panel-heading">
                                    <h4><i class="fa fa-plus-circle"></i> Add Articles to Hide</h4>
                                    <!--<span class="tools pull-right">-->
                                    <!--    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>-->
                                    <!--</span>-->
                                </header>
                                <div class="panel-body">
                                    <form method="post" action="<?= base_url('admin/dropdown-restriction') ?>" id="restriction_form">
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- Buyer Dropdown -->
                                                <div class="form-group">
                                                    <label><i class="fa fa-user"></i> Select Buyer *</label>
                                                    <select class="form-control" id="buyer_select" name="buyer_id" required>
                                                        <option value="">-- Select Buyer --</option>
                                                        <?php foreach($buyers as $buyer) { ?>
                                                            <option value="<?= $buyer->am_id ?>">
                                                                <?= $buyer->name ?> 
                                                                <?= !empty($buyer->am_code) ? "({$buyer->am_code})" : '' ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Articles Multi-select (hidden initially) -->
                                        <div id="articles_container" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><i class="fa fa-list"></i> Select Articles to Hide from Costing Dropdown *</label>
                                                        <input type="text" id="searchInput" placeholder="🔍 Type to search articles..." />
                                                        <select id="article_select" name="articles[]" multiple="multiple">
                                                            <!-- Articles will be loaded via AJAX -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="submit_container" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-save"></i> Hide Selected Articles
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-sm" id="clear_all">
                                                        <i class="fa fa-times"></i> Clear Selections
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" id="reset_form">
                                                        <i class="fa fa-refresh"></i> Reset Form
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    
        <!-- Placed js at the end of the document so the pages load faster -->
        <script src="<?= base_url() ?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>
        
        <!-- common js -->
        <?php $this->load->view("components/_common_js"); ?>
        
        <!--Data Table-->
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/JSZip-2.5.0/jszip.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/admin_panel/js/DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
        
        <!-- Multi-select JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
        
        <!-- Multi-select JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
        
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
        $(document).ready(function(){
            // Initialize Select2 for buyer dropdown
            $('#buyer_select').select2({
                placeholder: "🔍 Search and select a buyer...",
                allowClear: true,
                width: '100%',
                theme: 'default',
                matcher: function(params, data) {
                    // If there are no search terms, return all data
                    if ($.trim(params.term) === '') {
                        return data;
                    }
        
                    // Search in both text and code
                    var original = data.text.toLowerCase();
                    var term = params.term.toLowerCase();
        
                    // Check if the term exists in the text
                    if (original.indexOf(term) > -1) {
                        return data;
                    }
        
                    // Return null if the term should not be displayed
                    return null;
                }
            });
        
            // Initialize DataTable
            var hiddenTable = $('#hidden_articles_table').DataTable({
                "pageLength": 10,
                "dom": 'Blfrtip',
                "order": [[1, 'asc']],
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        className: 'buttons-excel',
                        title: 'Hidden Articles - ' + new Date().toLocaleDateString(),
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        className: 'buttons-pdf',
                        title: 'Hidden Articles - ' + new Date().toLocaleDateString(),
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            });
            
            var multiSelectInitialized = false;
            
            // Show loading overlay
            function showLoading() {
                $('#loadingOverlay').addClass('show');
            }
            
            // Hide loading overlay
            function hideLoading() {
                $('#loadingOverlay').removeClass('show');
            }
            
            // Buyer dropdown change event
            $('#buyer_select').on('change', function(){
    var buyerId = $(this).val();
    
    if(buyerId) {
        // Show loading overlay
        showLoading();
        
        // Destroy existing multiselect if initialized
        if(multiSelectInitialized) {
            $('#article_select').multiSelect('destroy');
            multiSelectInitialized = false;
        }
        
        // Show containers and clear
        $('#articles_container').show();
        $('#article_select').empty().html('<option>Loading articles...</option>');
        $('#submit_container').hide(); // Hide submit button while loading
        
        // Fetch articles for selected buyer
        $.ajax({
            url: '<?= base_url("admin/get-articles-by-buyer") ?>',
            type: 'POST',
            data: { buyer_id: buyerId },
            dataType: 'json',
            success: function(response) {
                // Clear the select first
                $('#article_select').empty();
                
                if(response.success) {
                    // Populate with fetched articles
                    if(response.articles && response.articles.length > 0) {
                        $.each(response.articles, function(index, article) {
                            var optionText = article.art_no + ' - ' + article.alt_art_no;
                            if(article.info) {
                                optionText += ' (' + article.info + ')';
                            }
                            $('#article_select').append(
                                '<option value="' + article.am_id + '">' + optionText + '</option>'
                            );
                        });
                        
                        // Initialize multi-select
                        $('#article_select').multiSelect({
                            selectableHeader: "<div class='custom-header'>Available Articles (" + response.articles.length + ")</div>",
                            selectionHeader: "<div class='custom-header'>Articles to Hide (0)</div>",
                            afterSelect: function(){
                                updateSelectionCount();
                            },
                            afterDeselect: function(){
                                updateSelectionCount();
                            }
                        });
                        multiSelectInitialized = true;
                        
                        $('#submit_container').show();
                    } else {
                        // No articles found - show message and keep containers visible
                        $('#article_select').html('<option disabled>No visible articles found for this buyer</option>');
                        $('#submit_container').hide();
                        
                        // Show info message
                        if(!$('#no_articles_msg').length) {
                            $('#articles_container').append(
                                '<div id="no_articles_msg" class="alert alert-warning" style="margin-top:10px;margin-bottom:0;">' +
                                '<i class="fa fa-info-circle"></i> No visible articles found for the selected buyer. ' +
                                'All articles may already be hidden or this buyer has no articles assigned.' +
                                '</div>'
                            );
                        }
                    }
                } else {
                    $('#article_select').html('<option disabled>Error loading articles</option>');
                    $('#submit_container').hide();
                    alert('Error loading articles: ' + (response.message || 'Unknown error'));
                }
                
                // Hide loading overlay
                hideLoading();
            },
            error: function(xhr, status, error) {
                // Clear and show error
                $('#article_select').empty().html('<option disabled>Error loading articles</option>');
                $('#submit_container').hide();
                alert('An error occurred while loading articles. Please try again.');
                console.error('AJAX Error:', status, error);
                hideLoading();
            }
        });
    } else {
        // No buyer selected - clear everything
        $('#articles_container').hide();
        $('#submit_container').hide();
        $('#no_articles_msg').remove();
        
        if(multiSelectInitialized) {
            $('#article_select').multiSelect('destroy');
            multiSelectInitialized = false;
        }
        
        $('#article_select').empty();
    }
});
            
            // Update selection count in header
            function updateSelectionCount() {
                var selectedCount = $('.ms-selection li.ms-elem-selection').length;
                var availableCount = $('.ms-selectable li.ms-elem-selectable').length;
                $('.ms-selection .custom-header').text('Articles to Hide (' + selectedCount + ')');
                $('.ms-selectable .custom-header').text('Available Articles (' + availableCount + ')');
            }
            
            // Search functionality
            $(document).on('keyup', '#searchInput', function() {
                var searchTerm = $(this).val().toLowerCase();
                var $selectableItems = $('.ms-selectable li.ms-elem-selectable');
                
                $selectableItems.each(function() {
                    var text = $(this).text().toLowerCase();
                    if(text.indexOf(searchTerm) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Remove restriction (make article visible again)
            $(document).on('click', '.remove-restriction', function(){
                var btn = $(this);
                var articleId = btn.data('id');
                var articleNo = btn.data('art');
                
                if(confirm('Are you sure you want to make article "' + articleNo + '" visible in dropdown?')) {
                    $.ajax({
                        url: '<?= base_url("admin/remove-dropdown-restriction") ?>',
                        type: 'POST',
                        data: {
                            article_id: articleId
                        },
                        beforeSend: function() {
                            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Wait...');
                        },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if(res.success) {
                                // Reload to refresh everything
                                location.reload();
                            } else {
                                alert('Error: ' + res.message);
                                btn.prop('disabled', false).html('<i class="fa fa-eye"></i> Show');
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                            btn.prop('disabled', false).html('<i class="fa fa-eye"></i> Show');
                        }
                    });
                }
            });
            
            // Clear all selections
            $('#clear_all').click(function(){
                if(multiSelectInitialized) {
                    $('#article_select').multiSelect('deselect_all');
                    updateSelectionCount();
                }
            });
            
            // Reset form
            $('#reset_form').click(function(){
                if(confirm('Are you sure you want to reset the form? All selections will be cleared.')) {
                    // Clear Select2
                    $('#buyer_select').val(null).trigger('change');
                    
                    // Hide containers
                    $('#articles_container').hide();
                    $('#submit_container').hide();
                    
                    // Remove no articles message if exists
                    $('#no_articles_msg').remove();
                    
                    // Destroy multiselect if initialized
                    if(multiSelectInitialized) {
                        $('#article_select').multiSelect('destroy');
                        multiSelectInitialized = false;
                    }
                    
                    // Clear the select element
                    $('#article_select').empty();
                }
            });
            
            // Panel collapse
            $('.t-collapse').click(function(){
                $(this).parents('.panel').find('.panel-body').slideToggle();
                $(this).toggleClass('fa-chevron-down fa-chevron-up');
            });
            
            // Form validation
            $('#restriction_form').on('submit', function(e){
                var buyerId = $('#buyer_select').val();
                if(!buyerId) {
                    e.preventDefault();
                    alert('⚠️ Please select a buyer first');
                    return false;
                }
                
                var selectedArticles = $('#article_select').val();
                if(!selectedArticles || selectedArticles.length === 0) {
                    e.preventDefault();
                    alert('⚠️ Please select at least one article to hide');
                    return false;
                }
                
                // Show confirmation
                if(!confirm('Are you sure you want to hide ' + selectedArticles.length + ' article(s) for the selected buyer?')) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading while submitting
                showLoading();
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function(){
                $('.alert').fadeOut('slow');
            }, 5000);
        });
        </script>
               

    </body>
</html>