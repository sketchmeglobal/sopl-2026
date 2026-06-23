<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bulk Edit Master | <?= WEBSITE_NAME; ?></title>
    <meta name="description" content="Bulk Edit Master">

    <!--Select2-->
    <link href="<?= base_url(); ?>assets/admin_panel/css/select2.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/admin_panel/css/select2-bootstrap.css" rel="stylesheet">

    <!-- common head -->
    <?php $this->load->view('components/_common_head'); ?>
    <!-- /common head -->

    <style>
        .filter-panel { background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 18px 18px 8px; margin-bottom: 18px; }
        .filter-section-wrap { display: none; }
        .master-select-wrap { max-width: 340px; margin-bottom: 20px; }
        .results-table-wrap { overflow-x: auto; }
        .results-table th { background: #f5f5f5; white-space: nowrap; font-size: 13px; }
        .results-table td { vertical-align: middle !important; padding: 5px 7px !important; }
        .results-table .form-control { padding: 3px 6px; height: 28px; font-size: 12px; border-radius: 3px; }
        .results-table select.form-control { height: 28px; }
        .results-table input[type="number"] { text-align: right; }
        .row-changed { background-color: #fffde7 !important; }
        .btn-save-row { padding: 2px 10px; font-size: 12px; }
        #results_section { display: none; }
        .total-count-badge { font-size: 13px; margin-left: 10px; color: #777; font-weight: 400; }
        .filter-group-item { display: none; }
    </style>
</head>

<body class="sticky-header">

<section>
    <!-- sidebar left start -->
    <?php $this->load->view('components/left_sidebar'); ?>
    <!-- sidebar left end -->

    <!-- body content start -->
    <div class="body-content" style="min-height: 1500px;">

        <!-- header section start -->
        <?php $this->load->view('components/top_menu'); ?>
        <!-- header section end -->

        <!-- page head start -->
        <div class="page-head">
            <h3 class="m-b-less">Bulk Edit Master</h3>
            <div class="state-information">
                <ol class="breadcrumb m-b-less bg-less">
                    <li><a href="<?= base_url('admin/dashboard'); ?>">Home</a></li>
                    <li class="active">Bulk Edit Master</li>
                </ol>
            </div>
        </div>
        <!-- page head end -->

        <!-- body wrapper start -->
        <div class="wrapper">

            <!-- ===== STEP 1: SELECT MASTER MENU ===== -->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading"><b>Select Master Menu</b></header>
                        <div class="panel-body">

                            <div class="master-select-wrap">
                                <label><b>Select Master</b></label>
                                <select id="master_menu_select" class="form-control" style="width:100%;">
                                    <option value="">-- Select Master --</option>
                                    <option value="item_master">Item Master</option>
                                    <option value="article_master">Article Master</option>
                                </select>
                            </div>

                            <!-- ===== STEP 2: FILTERS ===== -->
                            <div class="filter-section-wrap" id="filter_section">
                                <div class="filter-panel">
                                    <div class="row">

                                        <!-- Filter slot 1: Item Group (item_master) OR Article Group (article_master) -->
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">

                                                <!-- Item Group -->
                                                <div id="wrap_item_group" class="filter-group-item">
                                                    <label><b>Select Item Group</b></label>
                                                    <select id="filter_ig_id" class="form-control filter-select2" style="width:100%;">
                                                        <option value="">-- All Item Groups --</option>
                                                        <?php foreach ($item_groups as $ig): ?>
                                                            <option value="<?= $ig['ig_id']; ?>"><?= htmlspecialchars($ig['group_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Article Group -->
                                                <div id="wrap_article_group" class="filter-group-item">
                                                    <label><b>Select Article Group</b></label>
                                                    <select id="filter_ag_id" class="form-control filter-select2" style="width:100%;">
                                                        <option value="">-- All Article Groups --</option>
                                                        <?php foreach ($article_groups as $ag): ?>
                                                            <option value="<?= $ag['ag_id']; ?>"><?= htmlspecialchars($ag['group_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <label><b>Select Customer</b></label>
                                                <select id="filter_customer_id" class="form-control filter-select2" style="width:100%;">
                                                    <option value="">-- All Customers --</option>
                                                    <?php foreach ($customers as $cust): ?>
                                                        <option value="<?= $cust['am_id']; ?>"><?= htmlspecialchars($cust['name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <label><b>Select Customer Order</b></label>
                                                <select id="filter_co_id" class="form-control filter-select2" style="width:100%;">
                                                    <option value="">-- All Orders --</option>
                                                    <?php foreach ($customer_orders as $co): ?>
                                                        <option value="<?= $co['co_id']; ?>"><?= htmlspecialchars($co['co_no']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <label><b>Select Packing List</b></label>
                                                <select id="filter_packing_id" class="form-control filter-select2" style="width:100%;">
                                                    <option value="">-- All Packing Lists --</option>
                                                    <?php foreach ($packing_lists as $pl): ?>
                                                        <option value="<?= $pl['packing_shipment_id']; ?>"><?= htmlspecialchars($pl['package_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div><!-- /row -->

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button id="btn_submit_filter" class="btn btn-primary" style="min-width:110px;">
                                                <i class="fa fa-search"></i> Submit
                                            </button>
                                            <button id="btn_reset_filter" class="btn btn-default" style="margin-left:8px;">
                                                <i class="fa fa-refresh"></i> Reset
                                            </button>
                                            <span id="loading_spinner" style="display:none; margin-left:12px; color:#555;">
                                                <i class="fa fa-spinner fa-spin"></i> Loading...
                                            </span>
                                        </div>
                                    </div>

                                </div><!-- /filter-panel -->
                            </div><!-- /filter-section-wrap -->

                        </div><!-- /panel-body -->
                    </section>
                </div>
            </div>
            <!-- /STEP 1+2 -->

            <!-- ===== STEP 3: EDITABLE RESULTS TABLE ===== -->
            <div id="results_section">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                <b id="results_title">Editable Results</b>
                                <span class="total-count-badge" id="results_count"></span>
                                <span class="tools pull-right">
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">

                                <div class="alert alert-info" style="font-size:13px; padding:7px 12px; margin-bottom:10px;">
                                    <i class="fa fa-info-circle"></i>
                                    Edit any field and click <b>Save</b> on that row. Rows with changes are highlighted in yellow.
                                </div>

                                <div class="results-table-wrap">
                                    <table class="table table-bordered table-hover results-table" id="bulk_edit_table">
                                        <thead>
                                            <tr id="bulk_edit_thead_row">
                                                <th style="width:36px;">#</th>
                                                <th>Group</th>
                                                <th>Code / Art No</th>
                                                <th>Name / Description</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bulk_edit_tbody">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted" style="padding:20px 0;">
                                                    No data loaded yet. Select filters and click Submit.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div><!-- /panel-body -->
                        </section>
                    </div>
                </div>
            </div>
            <!-- /STEP 3 -->

        </div>
        <!-- /wrapper -->

        <!-- footer section start -->
        <?php $this->load->view('components/footer'); ?>
        <!-- footer section end -->

    </div>
    <!-- /body content end -->

</section>

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/admin_panel/js/jquery-1.10.2.min.js"></script>

<!-- common js -->
<?php $this->load->view('components/_common_js'); ?>

<!-- Select2 -->
<script src="<?= base_url(); ?>assets/admin_panel/js/select2.js" type="text/javascript"></script>

<script>
var BASE_URL       = '<?= base_url(); ?>';
var itemGroups     = <?= json_encode($item_groups); ?>;
var articleGroups  = <?= json_encode($article_groups); ?>;
var sizes          = <?= json_encode($sizes); ?>;
var shapes         = <?= json_encode($shapes); ?>;
var units          = <?= json_encode($units); ?>;

var currentMasterType = '';
var allRows           = [];
var renderedCount     = 0;
var batchSize         = 50;
var isRendering       = false;

$(document).ready(function () {

    $('.filter-select2').select2({ theme: 'bootstrap', allowClear: true, placeholder: '-- Select --', width: '100%' });

    // ── Master type change ──
    $('#master_menu_select').on('change', function () {
        currentMasterType = $(this).val();

        if (!currentMasterType) {
            $('#filter_section').hide();
            $('#results_section').hide();
            return;
        }

        if (currentMasterType === 'article_master') {
            $('#wrap_item_group').hide();
            $('#wrap_article_group').show();
        } else {
            $('#wrap_article_group').hide();
            $('#wrap_item_group').show();
        }

        updateTableHeaders(currentMasterType);
        $('#filter_section').show();
        $('#results_section').hide();
        clearTbody();
    });

    // ── Submit filter ──
    $('#btn_submit_filter').on('click', function () {
        if (!currentMasterType) { alert('Please select a master first.'); return; }

        var groupId = currentMasterType === 'article_master'
            ? $('#filter_ag_id').val()
            : $('#filter_ig_id').val();

        var payload = {
            master_type         : currentMasterType,
            ig_id               : groupId,
            customer_id         : $('#filter_customer_id').val(),
            co_id               : $('#filter_co_id').val(),
            packing_shipment_id : $('#filter_packing_id').val()
        };

        $('#loading_spinner').show();
        $('#btn_submit_filter').prop('disabled', true);

        $.post(BASE_URL + 'admin/ajax-bulk-edit-get-items', payload, function (res) {
            $('#loading_spinner').hide();
            $('#btn_submit_filter').prop('disabled', false);

            if (res.type === 'error') { alert(res.msg); return; }

            initTable(res.data);
            $('#results_section').show();
            $('html, body').animate({ scrollTop: $('#results_section').offset().top - 15 }, 350);
        }, 'json').fail(function () {
            $('#loading_spinner').hide();
            $('#btn_submit_filter').prop('disabled', false);
            alert('Server error. Please try again.');
        });
    });

    // ── Reset filters ──
    $('#btn_reset_filter').on('click', function () {
        $('#filter_ig_id, #filter_ag_id, #filter_customer_id, #filter_co_id, #filter_packing_id').val('').trigger('change');
        $('#results_section').hide();
        allRows = []; renderedCount = 0;
    });

    // ── Scroll → load more ──
    $(window).on('scroll.bulkedit', function () {
        if (!$('#scroll_sentinel').length) return;
        var sentinelTop = $('#scroll_sentinel').offset().top;
        var viewBottom  = $(window).scrollTop() + $(window).height();
        if (viewBottom >= sentinelTop - 300) {
            renderNextBatch();
        }
    });

    // ── Init table with data ──
    function initTable(rows) {
        allRows       = rows || [];
        renderedCount = 0;
        var colspan   = currentMasterType === 'article_master' ? 15 : 11;

        $('#results_title').text(
            currentMasterType === 'article_master'
                ? 'Article Master — Editable Results'
                : 'Item Master — Editable Results'
        );

        $('#bulk_edit_tbody').empty();

        if (!allRows.length) {
            $('#bulk_edit_tbody').append('<tr><td colspan="' + colspan + '" class="text-center text-muted" style="padding:20px 0;">No records found for the selected filters.</td></tr>');
            $('#results_count').text('');
            return;
        }

        $('#results_count').text('(' + allRows.length + ' record' + (allRows.length !== 1 ? 's' : '') + ')');
        renderNextBatch();
    }

    // ── Render next chunk of rows ──
    function renderNextBatch() {
        if (isRendering || renderedCount >= allRows.length) return;
        isRendering = true;

        var $tbody  = $('#bulk_edit_tbody');
        var colspan = currentMasterType === 'article_master' ? 15 : 11;

        // Remove old sentinel before appending new rows
        $('#scroll_sentinel').remove();

        var end = Math.min(renderedCount + batchSize, allRows.length);
        var fragment = document.createDocumentFragment();

        for (var i = renderedCount; i < end; i++) {
            var $tr = currentMasterType === 'article_master'
                ? buildArticleRow(i + 1, allRows[i])
                : buildItemRow(i + 1, allRows[i]);

            $tr.find('input, select').on('change input', function () {
                $(this).closest('tr').addClass('row-changed');
            });
            fragment.appendChild($tr[0]);
        }
        $tbody.append(fragment);
        renderedCount = end;

        // Add sentinel if more rows remain
        if (renderedCount < allRows.length) {
            $tbody.append(
                '<tr id="scroll_sentinel"><td colspan="' + colspan + '" class="text-center text-muted" style="padding:8px; font-size:12px;">' +
                '<i class="fa fa-spinner fa-spin"></i> Showing ' + renderedCount + ' of ' + allRows.length + ' records — scroll down to load more' +
                '</td></tr>'
            );
        }

        isRendering = false;
    }

    function clearTbody() {
        allRows = []; renderedCount = 0;
        $('#bulk_edit_tbody').html('<tr><td colspan="11" class="text-center text-muted" style="padding:20px 0;">No data loaded yet. Select filters and click Submit.</td></tr>');
        $('#results_count').text('');
    }

    // ── Dynamic table headers ──
    function updateTableHeaders(masterType) {
        var $tr = $('#bulk_edit_thead_row');
        if (masterType === 'article_master') {
            $tr.html(
                '<th style="width:36px;">#</th>' +
                '<th style="min-width:145px;">Article Group <span class="text-danger">*</span></th>' +
                '<th style="min-width:110px;">Art No <span class="text-danger">*</span></th>' +
                '<th style="min-width:110px;">Alt Art No</th>' +
                '<th style="min-width:180px;">Description</th>' +
                '<th style="min-width:90px;">Size</th>' +
                '<th style="min-width:110px;">Leather Type</th>' +
                '<th style="min-width:100px;">HSN Code</th>' +
                '<th style="min-width:90px;">ExWorks</th>' +
                '<th style="min-width:90px;">C&amp;F</th>' +
                '<th style="min-width:90px;">FOB</th>' +
                '<th style="min-width:95px;">Cut Rate B</th>' +
                '<th style="min-width:95px;">Fab Rate B</th>' +
                '<th style="min-width:90px;">Status</th>' +
                '<th style="min-width:75px; text-align:center;">Action</th>'
            );
        } else {
            $tr.html(
                '<th style="width:36px;">#</th>' +
                '<th style="min-width:145px;">Item Group <span class="text-danger">*</span></th>' +
                '<th style="min-width:115px;">Item Code <span class="text-danger">*</span></th>' +
                '<th style="min-width:170px;">Item Name <span class="text-danger">*</span></th>' +
                '<th style="min-width:110px;">Size</th>' +
                '<th style="min-width:110px;">Shape</th>' +
                '<th style="min-width:110px;">Unit</th>' +
                '<th style="min-width:100px;">HSN Code</th>' +
                '<th style="min-width:105px;">Type</th>' +
                '<th style="min-width:90px;">Status</th>' +
                '<th style="min-width:75px; text-align:center;">Action</th>'
            );
        }
    }

    // ── Item Master row builder ──
    function buildItemRow(idx, r) {
        var typeOpts = optionList([
            { v: '',              l: '-- Select --' },
            { v: 'Raw Material',   l: 'Raw Material' },
            { v: 'Consumable',     l: 'Consumable' },
            { v: 'Finished Goods', l: 'Finished Goods' }
        ], r.type);

        var statusOpts = optionList([
            { v: '1', l: 'Enable' },
            { v: '0', l: 'Disable' }
        ], r.status);

        return $('<tr data-master_type="item_master" data-im_id="' + r.im_id + '">'
            + '<td class="text-center">' + idx + '</td>'
            + '<td><select class="form-control be-ig_id">'   + buildOptions(itemGroups, 'ig_id', 'group_name', r.ig_id) + '</select></td>'
            + '<td><input  class="form-control be-im_code"  type="text" value="' + esc(r.im_code) + '"></td>'
            + '<td><input  class="form-control be-item"     type="text" value="' + esc(r.item) + '"></td>'
            + '<td><select class="form-control be-sz_id">'   + buildOptions(sizes,      'sz_id', 'size',       r.sz_id) + '</select></td>'
            + '<td><select class="form-control be-sh_id">'   + buildOptions(shapes,     'sh_id', 'shape',      r.sh_id) + '</select></td>'
            + '<td><select class="form-control be-u_id">'    + buildOptions(units,      'u_id',  'unit',       r.u_id)  + '</select></td>'
            + '<td><input  class="form-control be-hsn_code" type="text" value="' + esc(r.hsn_code || '') + '"></td>'
            + '<td><select class="form-control be-type">'    + typeOpts   + '</select></td>'
            + '<td><select class="form-control be-status">'  + statusOpts + '</select></td>'
            + '<td class="text-center"><button class="btn btn-success btn-save-row" type="button"><i class="fa fa-save"></i> Save</button></td>'
            + '</tr>');
    }

    // ── Article Master row builder ──
    function buildArticleRow(idx, r) {
        var leatherOpts = optionList([
            { v: '',         l: '-- Select --' },
            { v: 'None',     l: 'None' },
            { v: 'Cow',      l: 'Cow' },
            { v: 'Buff',     l: 'Buff' },
            { v: 'Goat',     l: 'Goat' },
            { v: 'Hair-On',  l: 'Hair-On' },
            { v: 'Print',    l: 'Print' }
        ], r.leather_type);

        var statusOpts = optionList([
            { v: '1', l: 'Enable' },
            { v: '0', l: 'Disable' }
        ], r.status);

        return $('<tr data-master_type="article_master" data-am_id="' + r.am_id + '">'
            + '<td class="text-center">' + idx + '</td>'
            + '<td><select class="form-control be-ag_id">' + buildOptions(articleGroups, 'ag_id', 'group_name', r.ag_id) + '</select></td>'
            + '<td><input  class="form-control be-art_no"             type="text"   value="' + esc(r.art_no || '') + '"></td>'
            + '<td><input  class="form-control be-alt_art_no"         type="text"   value="' + esc(r.alt_art_no || '') + '"></td>'
            + '<td><input  class="form-control be-info"               type="text"   value="' + esc(r.info || '') + '"></td>'
            + '<td><input  class="form-control be-size"               type="text"   value="' + esc(r.size || '') + '"></td>'
            + '<td><select class="form-control be-leather_type">' + leatherOpts + '</select></td>'
            + '<td><input  class="form-control be-remark"             type="text"   value="' + esc(r.remark || '') + '"></td>'
            + '<td><input  class="form-control be-exworks_amt"        type="number" step="0.01" value="' + esc(r.exworks_amt || '') + '"></td>'
            + '<td><input  class="form-control be-cf_amt"             type="number" step="0.01" value="' + esc(r.cf_amt || '') + '"></td>'
            + '<td><input  class="form-control be-fob_amt"            type="number" step="0.01" value="' + esc(r.fob_amt || '') + '"></td>'
            + '<td><input  class="form-control be-cutting_rate_b"     type="number" step="0.01" value="' + esc(r.cutting_rate_b || '') + '"></td>'
            + '<td><input  class="form-control be-fabrication_rate_b" type="number" step="0.01" value="' + esc(r.fabrication_rate_b || '') + '"></td>'
            + '<td><select class="form-control be-status">' + statusOpts + '</select></td>'
            + '<td class="text-center"><button class="btn btn-success btn-save-row" type="button"><i class="fa fa-save"></i> Save</button></td>'
            + '</tr>');
    }

    // ── Save row handler ──
    $(document).on('click', '.btn-save-row', function () {
        var $btn = $(this);
        var $tr  = $btn.closest('tr');
        var masterType = $tr.data('master_type');

        if (masterType === 'article_master') {
            saveArticleRow($tr, $btn);
        } else {
            saveItemRow($tr, $btn);
        }
    });

    function saveItemRow($tr, $btn) {
        var im_id   = $tr.data('im_id');
        var ig_id   = $tr.find('.be-ig_id').val();
        var im_code = $.trim($tr.find('.be-im_code').val());
        var item    = $.trim($tr.find('.be-item').val());

        if (!ig_id || !im_code || !item) {
            alert('Item Group, Item Code, and Item Name are required.');
            return;
        }

        var payload = {
            im_id    : im_id,
            ig_id    : ig_id,
            im_code  : im_code,
            item     : item,
            sz_id    : $tr.find('.be-sz_id').val(),
            sh_id    : $tr.find('.be-sh_id').val(),
            u_id     : $tr.find('.be-u_id').val(),
            hsn_code : $.trim($tr.find('.be-hsn_code').val()),
            type     : $tr.find('.be-type').val(),
            status   : $tr.find('.be-status').val()
        };

        postSave(BASE_URL + 'admin/ajax-bulk-edit-update-item', payload, $tr, $btn);
    }

    function saveArticleRow($tr, $btn) {
        var am_id   = $tr.data('am_id');
        var ag_id   = $tr.find('.be-ag_id').val();
        var art_no  = $.trim($tr.find('.be-art_no').val());

        if (!ag_id || !art_no) {
            alert('Article Group and Art No are required.');
            return;
        }

        var payload = {
            am_id              : am_id,
            ag_id              : ag_id,
            art_no             : art_no,
            alt_art_no         : $.trim($tr.find('.be-alt_art_no').val()),
            info               : $.trim($tr.find('.be-info').val()),
            size               : $.trim($tr.find('.be-size').val()),
            leather_type       : $tr.find('.be-leather_type').val(),
            remark             : $.trim($tr.find('.be-remark').val()),
            exworks_amt        : $tr.find('.be-exworks_amt').val(),
            cf_amt             : $tr.find('.be-cf_amt').val(),
            fob_amt            : $tr.find('.be-fob_amt').val(),
            cutting_rate_b     : $tr.find('.be-cutting_rate_b').val(),
            fabrication_rate_b : $tr.find('.be-fabrication_rate_b').val(),
            status             : $tr.find('.be-status').val()
        };

        postSave(BASE_URL + 'admin/ajax-bulk-edit-update-article', payload, $tr, $btn);
    }

    function postSave(url, payload, $tr, $btn) {
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post(url, payload, function (res) {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save');

            if (res.type === 'success') {
                $tr.removeClass('row-changed').css('background-color', '#dff0d8');
                setTimeout(function () { $tr.css('background-color', ''); }, 1500);
            } else {
                alert(res.msg || 'Update failed. Please try again.');
            }
        }, 'json').fail(function () {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save');
            alert('Server error. Please try again.');
        });
    }

    // ── Helpers ──
    function buildOptions(arr, valKey, labelKey, selected) {
        var html = '<option value="">-- Select --</option>';
        $.each(arr, function (i, item) {
            var sel = String(item[valKey]) === String(selected) ? ' selected' : '';
            html += '<option value="' + item[valKey] + '"' + sel + '>' + esc(item[labelKey]) + '</option>';
        });
        return html;
    }

    function optionList(arr, selected) {
        var html = '';
        $.each(arr, function (i, item) {
            var sel = String(item.v) === String(selected) ? ' selected' : '';
            html += '<option value="' + item.v + '"' + sel + '>' + esc(item.l) + '</option>';
        });
        return html;
    }

    function esc(str) {
        if (str == null) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

});
</script>

</body>
</html>
