<?php 
// update total row for po pending
if(isset($status_details)){
    foreach($status_details as $key=>$inner_leather){
        foreach($inner_leather as $sd){ 
            if($search_type == 'print_po' and (round($sd->cut_rcvd_qnty, 2) == round($sd->cut_issue_qnty, 2))){
                if(!empty($sd->cut_issue_qnty) or !empty($sd->cut_rcvd_qnty)){
                    $status_row[$key] = $status_row[$key]-1;
                }
            }
        }
    }
}
if(isset($status_details_comb)){
    foreach($status_details_comb as $key=>$inner_leather){
        foreach($inner_leather as $sd){ 
            if($search_type == 'print_po' and (round($sd->cut_rcvd_qnty, 2) == round($sd->cut_issue_qnty, 2))){
                if(!empty($sd->cut_issue_qnty) or !empty($sd->cut_rcvd_qnty)){
                    $status_row_comb[$key] = $status_row_comb[$key]-1;
                }
            }
        }
    }
}

function fetch_item_details($id_id){
    $CI =& get_instance();
    return $CI->db
        ->select("CONCAT(item_master.item, ' (' ,colors.color, ')') AS item_dtls")
        ->join('item_master','item_master.im_id = item_dtl.im_id','left')
        ->join('colors','colors.c_id = item_dtl.c_id','left')
        ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))->row()->item_dtls;
}

function fetch_current_stock($id_id){
    $CI =& get_instance();

    $opening_stock = $CI->db->select('opening_stock')
        ->get_where('item_dtl', array('id_id' => $id_id))
        ->row()->opening_stock;

    $pur_rcv_row = $CI->db->select('SUM(item_quantity) AS item_quantity')
        ->group_by('id_id')
        ->get_where('purchase_order_receive_detail', array('id_id' => $id_id))
        ->row();
    $pur_rcv = ($pur_rcv_row !== null) ? $pur_rcv_row->item_quantity : 0;

    $stock_in_row = $CI->db->select('SUM(item_quantity) AS item_quantity')
        ->group_by('id_id')
        ->get_where('stock_in_detail', array('id_id' => $id_id))
        ->row();
    $stock_in = ($stock_in_row !== null) ? $stock_in_row->item_quantity : 0;

    $challan_row = $CI->db->select_sum('purchase_challan_order_receive_detail.item_quantity')
        ->from('purchase_challan_order_receive_detail')
        ->join('purchase_challan_order_receive',
            'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left')
        ->where('purchase_challan_order_receive_detail.id_id', $id_id)
        ->where('purchase_challan_order_receive_detail.status', 1)
        ->where('purchase_challan_order_receive.status', 1)
        ->get()->row();
    $challan = (!empty($challan_row) && $challan_row->item_quantity !== null) ? (float)$challan_row->item_quantity : 0;

    $material_issue_row = $CI->db->select('SUM(issue_quantity) AS issue_quantity')
        ->group_by('id_id')
        ->get_where('material_issue_detail', array('id_id' => $id_id))
        ->row();
    $material_issue = ($material_issue_row !== null) ? $material_issue_row->issue_quantity : 0;

    return round(($opening_stock + $challan + $stock_in) - $material_issue, 2);
}

function fetch_po_pending($id_id){
    $CI =& get_instance();

    $pur_order_row = $CI->db->select('SUM(pod_quantity) AS pod_quantity')
        ->group_by('id_id')
        ->get_where('purchase_order_details', array('id_id' => $id_id))
        ->row();
    $pur_order = ($pur_order_row !== null) ? $pur_order_row->pod_quantity : 0;

    $supp_pur_order_row = $CI->db->select('SUM(item_qty) AS item_qty')
        ->group_by('id_id')
        ->get_where('supp_purchase_order_detail', array('id_id' => $id_id))
        ->row();
    $supp_pur_order = ($supp_pur_order_row !== null) ? $supp_pur_order_row->item_qty : 0;

    $challan_rcv_row = $CI->db->select_sum('purchase_challan_order_receive_detail.item_quantity')
        ->from('purchase_challan_order_receive_detail')
        ->join('purchase_challan_order_receive', 'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left')
        ->where('purchase_challan_order_receive_detail.id_id', $id_id)
        ->where('purchase_challan_order_receive_detail.status', 1)
        ->where('purchase_challan_order_receive.status', 1)
        ->get()->row();
    $pur_rcv = (!empty($challan_rcv_row) && $challan_rcv_row->item_quantity !== null) ? (float)$challan_rcv_row->item_quantity : 0;

    return round(($supp_pur_order + $pur_order) - $pur_rcv, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Leather Status | Shilpa Overseas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">
    <style>
        body{ font-family: Calibri; }
        p { margin: 0 0 5px; }
        table{ border: 1px solid #777; }
        .table{ margin-bottom: 3px; }
        .head_font{ font-family: Calibri; }
        .container{width: 100%}
        .border_all{ border: 1px solid #000; }
        .border_bottom{ border-bottom: 1px solid #000; }
        .mar_0{ margin: 0 }
        .mar_bot_3{ margin-bottom: 3px }
        .header_left, .header_right{ height: 150px }
        .width-100{width: 100%}
        .height_60{ height: 60px }
        .height_42{ height: 42px }
        .height_63{ height: 63px }
        .height_21{ height: 21px }
        .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td, .table-bordered>thead>tr>th
            { border: 1px solid #000!important; text-align: center; }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td,
        .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
            { padding: 5px; text-align: left; font-size: 16px }
        .border-bottom{ border-bottom: 1px solid #000 }
        .text-right{ text-align: right!important; }
        @page { size: A4 }
        @media print{
            .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th,
            .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th,
            .table-bordered>thead>tr>td, .table-bordered>thead>tr>th
                { border: 1px solid #000; text-align: center; }
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td,
            .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
                { padding: 5px; text-align: left; font-size: 16px }
            .col-sm-6{ width: 50%!important; float:left; }
            .col-sm-5{ width: 41.66666667%; float:left; }
            .col-sm-7{ width: 58.33333333%; float:left; }
            .border-bottom{ border-bottom: 1px solid #000 }
            .text-right{ text-align: right!important; }
            .no-print{ display: none }
        }
    </style>
</head>
<body class="A4" id="page-content">
    <section class="sheet padding-10mm" style="height: auto;">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row border_all text-center text-uppercase mar_bot_3">
                <h3 class="mar_0 head_font">Leather Status</h3>
            </div>
            <div class="row mar_bot_3">
                <div class="col-sm-6 border_all header_left">
                    <h4><strong>SHILPA OVERSEAS PVT. LTD.</strong></h4>
                    <p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136</p>
                    <p class="mar_0">PHONE: +91 2573-3470/71/72/2405</p>
                </div>
                <div class="col-sm-6 header_right">
                    <div class="row mar_bot_3">
                        <div class="col-sm-12 border_all height_60">
                            <p><strong>Date:</strong> <?=date('d-m-Y')?></p>
                            <p><strong>Search Type:</strong> <?=($search_type == 'print_po') ? 'Print Pending Orders' : 'Print All' ?></p>
                        </div>
                    </div>
                    <div class="row border_all height_63 mar_bot_3"><div class="col-sm-12"></div></div>
                    <div class="row border_all height_21"><div class="col-sm-12"></div></div>
                </div>
            </div>

            <!-- MAIN TABLE -->
            <div class="row">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order No.</th>
                            <th class="text-right">Order Pending</th>
                            <th class="text-right">Cutting Issue</th>
                            <th class="text-right">Cutting Rcvd.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $co_array     = array();
                        $iter         = 1;
                        $fin_cur_stock = $fin_po_pend = 0;
                        $fin_ord_pend  = $fin_cut_iss = $fin_cut_rcv = 0;

                        if(isset($status_details)){
                            foreach($status_details as $key => $inner_leather){

                                // ── reset sub-totals per item group ──────────
                                $inner_iter   = 0;
                                $sub_ord_pend = $sub_cut_iss = $sub_cut_rcv = 0;
                                $last_org_id  = null;

                                foreach($inner_leather as $sd){

                                    // skip if print_po and fully matched
                                    if($search_type == 'print_po'){
                                        if((round($sd->co_qnty,2) - round($sd->cut_issue_qnty,2) == 0)
                                            and (round($sd->cut_rcvd_qnty,2) - round($sd->cut_issue_qnty,2) == 0)){
                                            continue;
                                        }
                                    }

                                    $last_org_id = $sd->org_id_id;

                                    // item group header
                                    if(!in_array($sd->org_id_id, $co_array)){
                                        array_push($co_array, $sd->org_id_id);
                                        ?>
                                        <tr style="background-color: #b6d4ed">
                                            <th colspan="5"><?=fetch_item_details($sd->org_id_id)?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-right"><?=$iter++?>.</td>
                                        <td><?=$sd->co_no?></td>
                                        <td class="text-right"><?php
                                            echo $row_ord_pend = round($sd->co_qnty,2) - round($sd->cut_issue_qnty,2);
                                            $sub_ord_pend += $row_ord_pend;
                                        ?></td>
                                        <td class="text-right"><?php
                                            echo $row_cut_iss = $sd->cut_issue_qnty;
                                            $sub_cut_iss += $row_cut_iss;
                                        ?></td>
                                        <td class="text-right"><?php
                                            echo $row_cut_rcv = $sd->cut_rcvd_qnty;
                                            $sub_cut_rcv += $row_cut_rcv;
                                        ?></td>
                                    </tr>
                                    <?php
                                    $inner_iter++;
                                }

                                // ── summary row after each item group ────────
                                if($last_org_id !== null){
                                    ?>
                                    <tr style="background: beige;">
                                        <td colspan="2">
                                            <b>Current Stock:</b> <?php
                                                echo $row_cur_stock = fetch_current_stock($last_org_id);
                                                $fin_cur_stock += $row_cur_stock;
                                            ?> ||
                                            <b>P.O. Pending:</b> <?php
                                                echo $row_po_pend = fetch_po_pending($last_org_id);
                                                $fin_po_pend += $row_po_pend;
                                            ?> ||
                                            <b>Balance:</b>
                                            <?php
                                                $rv = ($sub_ord_pend + $sub_cut_iss) - ($sub_cut_rcv + $row_cur_stock + $row_po_pend);
                                                if($rv > 0){
                                                    echo '<label style="color: red">'.$rv.'</label>';
                                                }else{
                                                    echo '<label style="color: blue">'.$rv.'</label>';
                                                }
                                            ?>
                                        </td>
                                        <th class="text-right"><?php echo $sub_ord_pend; $fin_ord_pend += $sub_ord_pend; ?></th>
                                        <th class="text-right"><?php echo $sub_cut_iss;  $fin_cut_iss  += $sub_cut_iss;  ?></th>
                                        <th class="text-right"><?php echo $sub_cut_rcv;  $fin_cut_rcv  += $sub_cut_rcv;  ?></th>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- COMBINATION AREA -->
            <div class="row" style="margin-top:20px">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-right">#</th>
                            <th>Order No.</th>
                            <th class="text-right">Order Pending</th>
                            <th class="text-right">Cutting Issue</th>
                            <th class="text-right">Cutting Rcvd.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $co_array      = array();
                        $iter          = 1;
                        $fin_cur_stock = $fin_po_pend = 0;
                        $fin_ord_pend  = $fin_cut_iss = $fin_cut_rcv = 0;

                        if(isset($status_details_comb)){
                            foreach($status_details_comb as $key => $inner_leather){

                                $inner_iter   = 0;
                                $sub_ord_pend = $sub_cut_iss = $sub_cut_rcv = 0;
                                $last_org_id  = null;

                                foreach($inner_leather as $sd){

                                    if($search_type == 'print_po'){
                                        if((round($sd->co_qnty,2) - round($sd->cut_issue_qnty,2) == 0)
                                            and (round($sd->cut_rcvd_qnty,2) - round($sd->cut_issue_qnty,2) == 0)){
                                            continue;
                                        }
                                    }

                                    $last_org_id = $sd->org_id_id;

                                    if(!in_array($sd->org_id_id, $co_array)){
                                        array_push($co_array, $sd->org_id_id);
                                        ?>
                                        <tr style="background-color: #b6d4ed">
                                            <th colspan="5"><?=fetch_item_details($sd->org_id_id)?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-right"><?=$iter++?>.</td>
                                        <td><?=$sd->co_no?></td>
                                        <td class="text-right"><?php
                                            echo $row_ord_pend = round($sd->co_qnty,2) - round($sd->cut_issue_qnty,2);
                                            $sub_ord_pend += $row_ord_pend;
                                        ?></td>
                                        <td class="text-right"><?php
                                            echo $row_cut_iss = $sd->cut_issue_qnty;
                                            $sub_cut_iss += $row_cut_iss;
                                        ?></td>
                                        <td class="text-right"><?php
                                            echo $row_cut_rcv = $sd->cut_rcvd_qnty;
                                            $sub_cut_rcv += $row_cut_rcv;
                                        ?></td>
                                    </tr>
                                    <?php
                                    $inner_iter++;
                                }

                                if($last_org_id !== null){
                                    ?>
                                    <tr style="background: beige;">
                                        <td colspan="2">
                                            <b>Current Stock:</b> <?php
                                                echo $row_cur_stock = fetch_current_stock($last_org_id);
                                                $fin_cur_stock += $row_cur_stock;
                                            ?> ||
                                            <b>P.O. Pending:</b> <?php
                                                echo $row_po_pend = fetch_po_pending($last_org_id);
                                                $fin_po_pend += $row_po_pend;
                                            ?>
                                            <br>
                                            <b>Balance:</b>
                                            <?php
                                                $rv = ($sub_ord_pend + $sub_cut_iss) - ($sub_cut_rcv + $row_cur_stock + $row_po_pend);
                                                if($rv > 0){
                                                    echo '<label style="color: red">'.$rv.'</label>';
                                                }else{
                                                    echo '<label style="color: blue">'.$rv.'</label>';
                                                }
                                            ?>
                                        </td>
                                        <th class="text-right"><?php echo $sub_ord_pend; $fin_ord_pend += $sub_ord_pend; ?></th>
                                        <th class="text-right"><?php echo $sub_cut_iss;  $fin_cut_iss  += $sub_cut_iss;  ?></th>
                                        <th class="text-right"><?php echo $sub_cut_rcv;  $fin_cut_rcv  += $sub_cut_rcv;  ?></th>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</body>
</html>
