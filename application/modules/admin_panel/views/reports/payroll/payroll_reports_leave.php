<?php //echo '<pre>', print_r($data), '</pre>';die(); ?>

<?php

function fetch_name($eid){
    $CI = & get_instance();
    return $CI->db->get_where('employees', array('e_id' => $eid))->row()->name;
}

function fetch_total_granted($eid){
    $CI = & get_instance();
    return $CI->db->get_where('employees', array('e_id' => $eid))->row()->cl_granted;
}

function fetch_leave($eid, $mon){
    $CI = & get_instance();
    $sql = "SELECT T4 FROM salary WHERE MON LIKE '".$mon."%' AND EMPCODE = $eid";
    $res = $CI->db->query($sql)->row();
    if(count($res) > 0){
        return $res->T4; // casual leave    
    } else{
        return '-';
    }
}

function fetch_total_granted_el($eid){
    $CI = & get_instance();
    return $CI->db->get_where('employees', array('e_id' => $eid))->row()->el_granted;
}

function fetch_leave_el($eid, $mon){
    $CI = & get_instance();
    $sql = "SELECT T5 FROM salary WHERE MON LIKE '".$mon."%' AND EMPCODE = $eid";
    $res = $CI->db->query($sql)->row();
    if(count($res) > 0){
        return $res->T5; // Earned leave    
    } else{
        return '-';
    }
}

function fetch_total_granted_esi($eid){
    $CI = & get_instance();
    return $CI->db->get_where('employees', array('e_id' => $eid))->row()->ol_granted;
}

function fetch_leave_esi($eid, $mon){
    $CI = & get_instance();
    $sql = "SELECT T6 FROM salary WHERE MON LIKE '".$mon."%' AND EMPCODE = $eid";
    $res = $CI->db->query($sql)->row();
    if(count($res) > 0){
        return $res->T6; // Earned leave    
    } else{
        return '-';
    }
}


function fetch_absent($eid, $mon){
    $CI = & get_instance();
    $sql = "SELECT T7 FROM salary WHERE MON LIKE '".$mon."%' AND EMPCODE = $eid";
    $res = $CI->db->query($sql)->row();
    if(count($res) > 0){
        return $res->T7; // absent
    } else{
        return 0;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title><?=$data['segment']?> | <?=WEBSITE_NAME?></title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Normalize or reset CSS with your favorite library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
        <!-- Load paper.css for happy printing -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
        <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">
        <!-- Set page size here: A5, A4 or A3 -->
        <!-- Set also "landscape" if you need -->
        <style>
            body{ 
                font-family: 'Signika', sans-serif;
            }
            p {
                margin: 0 0 5px;
            }
            table{ border: 1px solid #777; }
            .table{
                margin-bottom: 3px;
            }
            .head_font{
                font-family: 'Signika', sans-serif;
                
            }
            .container{width: 100%}
            .border_all{
                border: 1px solid #000;
            }
            .border_bottom{
                border-bottom: 1px solid #000;
            }
            .mar_0{
                margin: 0
            }
            .mar_bot_3{
                margin-bottom: 3px
            }

            .header_left, .header_right{
                height: 150px
            }

            .width-100{width: 100%}

            .height_60{ height: 60px }
            .height_86{height: 86px;}
            .height_42{ height: 42px }
            .height_135{height: 150px}
            .height_90{height: 90px}
            .height_100{height: 100px}
            .height_110{height: 110px}
            
            
            
            .height_21{ height: 21px }
            .height_23{ height: 23px }
            .height_41{ height: 41px }

            .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000!important;  text-align: center;}
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}

            .border-bottom{border-bottom:  1px solid #000}
            
            .text-right{text-align: right!important;}
            .cell-middle{text-align: center!important;vertical-align: middle!important;}
            
            @page { size: A4 }

            @media print{
                .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000;  text-align: center;}
                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}
                .col-sm-6{ width: 50%!important;float:left; }.col-sm-5 { width: 41.66666667%;float:left; }.col-sm-7 { width: 58.33333333%;float:left; }
                .border-bottom{border-bottom:  1px solid #000} .text-right{text-align: right!important;}
                .cell-middle{text-align: center!important;vertical-align: middle!important;}
                .no-print{display: none}
            }
        </style>
    </head>

    <body class="A4 landscape" id="page-content" >
        
        <section class="sheet padding-10mm" style="height: auto;">
                <header class="pull-right">
                    <!-- <small>Page No. 2</small> -->
                </header>
                <div class="clearfix"></div>
                <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                        <h3 class="mar_0 head_font"><?=$data['segment']?></h3>
                    </div>
                    <div class="row mar_bot_3">
                        <div class="col-sm-6 border_all header_left">
                            <h4  class=""><strong><?=COMPANY_NAME?></strong></h4>
                            <p class="mar_0"><?=COMPANY_ADDRESS?></p>
                            <p class="mar_0">PHONE: <?=COMPANY_PHONE?></p>
                        </div>
                        <div class="col-sm-6 border_all header_right">
						<br>
					</div>
                    </div>
                    
                    <!--table data-->
                    <div class="row">
                        <!--<div class=""-->
                        <table id="empTable" class="table table-bordered table-hovered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Emp. Name</th>
                                    <th rowspan="2">Leave</th>
                                    <th rowspan="2">Total Granted</th>
                                    <th>Apr</th> <th>May</th> <th>Jun</th> <th>Jul</th> <th>Aug</th> <th>Sep</th>
                                    <th>Oct</th> <th>Nov</th> <th>Dec</th> <th>Jan</th> <th>Feb</th> <th>Mar</th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">Leave Blnc.</th>
                                    <th rowspan="2">Wages Rate</th>
                                    <th rowspan="2">Amount</th>
                                    <th rowspan="2">Signature/Thumb</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                               $employee_data = [];
                               foreach($data['result'] as $res){
                                   $total_causal_leave = $total_earn_leave = $total_esi_leave = $total_absent = 0; 
                               ?>
                               <tr>
                                    <td rowspan="5" class="emp-name"><?=fetch_name($res->e_id)?></td>
                                    <td nowrap>Casual Leave</td> 
                                    <td class="text-right">
                                        <?php
                                        $total_granted = fetch_total_granted($res->e_id);
                                        echo $total_granted;
                                        ?>
                                    </td> 
                                    <?php
                                    
                                   
                                     //FOR AMOUNT CALCULATION  TANAY
                                    $total_earn_leave_for_amount = 0;
                                    $total_granted_el_for_amount = fetch_total_granted_el($res->e_id);
                                    
                                    // Apr to Dec
                                    
                                    for($month_num1 = 4; $month_num1 < 13; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave($res->e_id, $dateObj->format('F'));
                                        $total_causal_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    // Jan - Mar
                                    
                                    for($month_num1 = 1; $month_num1 < 4; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave($res->e_id, $dateObj->format('F'));
                                        $total_causal_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    //Fetch Leave EL
                                    
                                    // Apr to Dec
                                    
                                    for($month_num1 = 4; $month_num1 < 13; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);
                                        $leave = fetch_leave_el($res->e_id, $dateObj->format('F'));
                                        $total_earn_leave_for_amount+=$leave;
                                    }
                                    
                                    // Jan - Mar
                                    
                                    for($month_num1 = 1; $month_num1 < 4; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);
                                        $leave = fetch_leave_el($res->e_id, $dateObj->format('F'));
                                        $total_earn_leave_for_amount+=$leave;
                                        
                                    }
                                     
                                    
                                    
                                    
                                    ?>
                                    
                                    <td class="text-right"><?=$total_causal_leave?></td> 
                                    <td class="text-right"><b><?= ($total_granted - $total_causal_leave) ?></b></td> 
                                    <td class="cell-middle" rowspan="4"><?= round(($res->basic_pay + $res->da_amout + $res->hra_amount)/30) ?></td>
                                    <!--<td class="cell-middle" rowspan="4">< ?= number_format((round(($res->basic_pay + $res->da_amout + $res->hra_amount)/30) * $total_causal_leave), 2); ?></td>-->
                                    
                                    <td class="cell-middle" rowspan="4"><?= number_format((round(($res->basic_pay + $res->da_amout + $res->hra_amount)/30) * ($total_granted_el_for_amount - $total_earn_leave_for_amount)), 2); ?></td>
                                </tr>
                                <tr>
                                    <td>Earn Leave</td> 
                                    <td class="text-right">
                                        <?php
                                        $total_granted_el = fetch_total_granted_el($res->e_id);
                                        echo $total_granted_el;
                                        ?>
                                    </td> 
                                    <?php
                                    
                                    // Apr to Dec
                                    
                                    for($month_num1 = 4; $month_num1 < 13; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave_el($res->e_id, $dateObj->format('F'));
                                        $total_earn_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    // Jan - Mar
                                    
                                    for($month_num1 = 1; $month_num1 < 4; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave_el($res->e_id, $dateObj->format('F'));
                                        $total_earn_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    ?>
                                    
                                    <td class="text-right"><?=$total_earn_leave?></td> 
                                    <td class="text-right"><?= ($total_granted_el - $total_earn_leave) ?></td> 
                                </tr>
                                <tr>
                                    <td>E.S.I. Leave</td> 
                                    <td class="text-right">
                                        <?php
                                        $total_granted_esi = fetch_total_granted_esi($res->e_id);
                                        echo $total_granted_esi;
                                        ?>
                                    </td> 
                                    <?php
                                    
                                    // Apr to Dec
                                    
                                    for($month_num1 = 4; $month_num1 < 13; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave_esi($res->e_id, $dateObj->format('F'));
                                        $total_esi_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    // Jan - Mar
                                    
                                    for($month_num1 = 1; $month_num1 < 4; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_leave_esi($res->e_id, $dateObj->format('F'));
                                        $total_esi_leave+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    ?>
                                    
                                    <td class="text-right"><?=$total_esi_leave?></td> 
                                    <td class="text-right"><?= ($total_granted_esi - $total_esi_leave) ?></td>
                                </tr>
                                <tr>
                                    <td>Absent</td> 
                                    <td class="cell-middle">-</td> 
                                    <?php
                                    
                                    // Apr to Dec
                                    
                                    for($month_num1 = 4; $month_num1 < 13; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_absent($res->e_id, $dateObj->format('F'));
                                        $total_absent+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    // Jan - Mar
                                    
                                    for($month_num1 = 1; $month_num1 < 4; $month_num1++){
                                        $dateObj = DateTime::createFromFormat('!m', $month_num1);     
                                        echo '<td class="text-right">';
                                        $leave = fetch_absent($res->e_id, $dateObj->format('F'));
                                        $total_absent+=$leave;
                                        echo $leave;
                                        echo '</td>';
                                    }
                                    
                                    ?>
                                    
                                    <td class="text-right"><?=$total_absent?></td> 
                                    <td class="cell-middle">-</td>
                                    
                                </tr>
                                <tr class="net_leave">
                                    <td><b>Total Leave</b></td> 
                                    <td class="text-right"><?php
                                        $granted_el_esi_leave_total = $total_granted + $total_granted_el + $total_granted_esi;
                                        echo $granted_el_esi_leave_total;
                                        ?></td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                    
                                    <td class="text-right"><?= $total_net_leave = $total_absent + $total_esi_leave + $total_earn_leave + $total_causal_leave;?></td>
                                    <td class="text-right"> 0 </td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right" ><small>(<?=fetch_name($res->e_id)?>)</small></td>
                                </tr>
                                <?php
                              
                                $name = fetch_name($res->e_id);
                                $total_net_leave = $total_absent + $total_esi_leave + $total_earn_leave + $total_causal_leave;

                                $employee_data[] = [
                                    'name' => $name,
                                    'total_granted_leave' => $total_granted,
                                    'granted_el_esi_leave_total' => $granted_el_esi_leave_total,
                                    'total_net_leave' => $total_net_leave
                                         ];
                                //print_r($employee_data); die();
                                ?>
                                
                               <?php       
                               }
                               ?>
                            </tbody>
                        </table>
                        
                          
                    </div>
                </div>
            </section>
            
            <!--NEW-->
               <section class="sheet padding-10mm" style="height: auto;">
                <header class="pull-right">
                    <!-- <small>Page No. 2</small> -->
                </header>
                <div class="clearfix"></div>
                <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                        <h3 class="mar_0 head_font"><?=$data['segment']?></h3>
                    </div>
                    <div class="row mar_bot_3">
                         <div class="col-sm-6 border_all header_left">
                            <h4  class=""><strong><?=COMPANY_NAME?></strong></h4>
                            <p class="mar_0"><?=COMPANY_ADDRESS?></p>
                            <p class="mar_0">PHONE: <?=COMPANY_PHONE?></p>
                        </div>
                        <div class="col-sm-6 border_all header_right">
						<br>
					</div>
                    </div>
                    
                    <!--table data-->
                    <div class="row">
                             <table id="empTable" class="table table-bordered table-hovered mt-5">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th rowspan="2">Emp. Name</th>
                                    <th rowspan="2" style="text-align:center;">Total Granted Leave</th>
                                    <th rowspan="2" style="text-align:center;">Total Leave</th>
                                    <th rowspan="2" style="text-align:center;">Balance Leave</th>
                                </tr>
                            </thead>
                            <tbody id="leaveTableBody">
                                  <?php $iter = 1; foreach ($employee_data as $emp): ?>
                                        <tr>
                                            <td><?= $iter++;  ?></td>
                                            <td><?= $emp['name']; ?></td>
                                            <td style="text-align:center;"><?= $emp['granted_el_esi_leave_total']; ?></td>
                                            <td style="text-align:center;"><?= $emp['total_net_leave']; ?></td>
                                                <?php
                                                    $granted_leave = $emp['granted_el_esi_leave_total'];
                                                    $net_leave = $emp['total_net_leave'];
                                                    $granted_net_leave = $granted_leave - $net_leave;
                                                
                                                    if ($granted_net_leave < 0) {
                                                        echo '<td style="color:red; text-align:center;">' . $granted_net_leave . '</td>';
                                                    } else {
                                                        echo '<td style="text-align:center;">' . $granted_net_leave . '</td>';
                                                    }
                                                ?>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        </table>
            
            </div>
                </div>
            </section>
            <script>
                function sortTable() {
                    let tbody = document.getElementById("leaveTableBody");
                    let rows = Array.from(tbody.getElementsByTagName("tr"));
            
                    rows.sort((a, b) => {
                        let leaveA = parseInt(a.cells[3].innerText);
                        let leaveB = parseInt(b.cells[3].innerText);
                        return leaveB - leaveA;
                    });
                    
                    rows.forEach((row, index) => {
                        row.cells[0].innerText = index + 1;
                        tbody.appendChild(row);
                    });
                    //rows.forEach(row => tbody.appendChild(row));
                }
            
                sortTable();
            </script>
            
    </body>
</html>
