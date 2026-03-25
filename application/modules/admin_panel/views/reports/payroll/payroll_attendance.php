<?php //echo '<pre>', print_r($data), '</pre>';die(); ?>

<?php

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
    <body class="A4 landscape" id="page-content">
    	<section class="sheet padding-10mm" style="height: auto">
		<div>
			<!--<header class="pull-right">-->
			<!--    <small>Page No. </small>-->
			<!--</header>-->
			<div class="clearfix"></div>
			<div class="container">
				<div class="row border_all text-center text-uppercase mar_bot_3">
					<h3 class="mar_0 head_font">PAYROLL ATTENDANCE DETAILS</h3>
				</div>
				<div class="row mar_bot_3">
					<div class="col-sm-6 border_all header_left">
						<h4 class=""><strong>SHILPA OVERSEAS PVT. LTD. </strong></h4>
						<p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136</p>
					</div>
					<div class="col-sm-6 border_all header_right">
						<br />
					</div>
				</div>
				<!--table data-->
				<div class="row">
					<div class="container">
						<div class="row">
							<div class="table-responsive">
								<!--<h5>Retrieve Table</h5>-->
								<table id="all_det" class="table table-bordered">
									<thead>
                                        <tr>
                                    		<th>Emp. Name</th>
                                    		<th>Particulars</th>
                                    		<!--<th style="text-align: right;">Total <br/> Granted</th>-->
                                        	<?php $dates = array();
                                            $total_cl = 0;
                                            $total_el = 0;
                                            $total_esil = 0;
                                            $current = strtotime('2022-04-01');
                                            $total_salarys = 0;
                                            $total_leave = 0;
                                            $total_leaves_days = 0;
                                            $rate_total = 0;
                                            $total_amounts = 0;
                                            $date2 = strtotime('2023-03-01');
                                            $stepVal = '+1 month';
                                            $total_absent = 0;
                                            while ($current <= $date2) {
                                                $dates[] = date('M', $current);
                                                $dates1[] = date('m', $current);
                                                $current = strtotime($stepVal, $current);
                                            }
                                            foreach ($dates as $d) {
                                            ?>
                                                <th style="text-align: right;"><?=$d?></th>
                                            <?php
                                            }
                                            ?>
                            			   <th style="text-align: right;">Total</th>
                            			   <!--<th style="text-align: right;">Leave <br/> Blnc.</th>-->
                            			</tr>
                                    </thead>
                                    <tbody> 
                                	<?php
                                    foreach ($data['result'] as $res)
                                    {
                                        // foreach ($res as $a)
                                        // {
                                    ?>
                                    <tr>
                                        <td rowspan="5"><?=$res[0]->name . '[' . $res[0]->e_code . ']' ?></td>
                                        <td>Working days</td>
                                        
                                        <?php 
                                        
                                        $wd = array();
                                        $dw = array();
                                        $hol = array();
                                        $lv = array();
                                        $abs = array();
                                        
                                        for($month_iter = 0; $month_iter < 12; $month_iter++){
                                            if(isset($res[$month_iter])){
                                                ?>
                                                <td style="text-align: right;">
                                                    <?php 
                                                        array_push($wd, $res[$month_iter]->T1);
                                                        echo $res[$month_iter]->T1;
                                                    ?>
                                                </td>    
                                                <?php    
                                            }else{
                                                echo '<td>0</td>';
                                            }
                                        }
                                        ?>
                                        
                                        <td style="text-align: right;"><?=array_sum($wd)?></td>
                                    </tr>
                                    <tr>
                                        <td>Days worked</td>
                                        
                                        <?php 
                                        for($month_iter = 0; $month_iter < 12; $month_iter++){
                                            if(isset($res[$month_iter])){
                                                ?>
                                                <td style="text-align: right;">
                                                    <?php 
                                                        array_push($dw, $res[$month_iter]->T2);
                                                        echo $res[$month_iter]->T2;
                                                    ?>
                                                </td>    
                                                <?php    
                                            }else{
                                                echo '<td>0</td>';
                                            }
                                        }
                                        ?>
                                        
                                        <td style="text-align: right;"><?=array_sum($dw)?></td>
                                    </tr>
                                    <tr>
                                        <td>Holidays</td>
                                        
                                        <?php 
                                        for($month_iter = 0; $month_iter < 12; $month_iter++){
                                            if(isset($res[$month_iter])){
                                                ?>
                                                <td style="text-align: right;">
                                                    <?php 
                                                        array_push($hol, $res[$month_iter]->T3);
                                                        echo $res[$month_iter]->T3;
                                                    ?>
                                                </td>
                                                <?php    
                                            }else{
                                                echo '<td>0</td>';
                                            }
                                        }
                                        ?>
                                        
                                        <td style="text-align: right;"><?=array_sum($hol)?></td>
                                    </tr>
                                    <tr>
                                        <td>Leave</td>
                                        
                                        <?php 
                                        for($month_iter = 0; $month_iter < 12; $month_iter++){
                                            if(isset($res[$month_iter])){
                                                ?>
                                                <td style="text-align: right;">
                                                    <?php 
                                                        $lvs = $res[$month_iter]->T4 + $res[$month_iter]->T5 + $res[$month_iter]->T6;
                                                        array_push($lv, $lvs);
                                                        echo $lvs;
                                                    ?>
                                                </td>
                                                <?php    
                                            }else{
                                                echo '<td>0</td>';
                                            }
                                        }
                                        ?>
                                        
                                        <td style="text-align: right;"><?=array_sum($lv)?></td>
                                    </tr>
                                    <tr>
                                        <td>Absent </td>
                                        
                                        <?php 
                                        for($month_iter = 0; $month_iter < 12; $month_iter++){
                                            if(isset($res[$month_iter])){
                                                ?>
                                                <td style="text-align: right;">
                                                    <?php 
                                                        array_push($abs, $res[$month_iter]->T7);
                                                        echo $res[$month_iter]->T7;
                                                    ?>
                                                </td>  
                                                <?php    
                                            }else{
                                                echo '<td>0</td>';
                                            }
                                        }
                                        ?>
                                        
                                        <td style="text-align: right;"><?=array_sum($abs)?></td>
                                    </tr>
                                    <?php
                                    // }
                                }
                                ?>
                                
                                <!--<tr>-->
                                <!--    <td colspan="14"><b>Total</b></td>-->
                                <!--    <td style="text-align: right;"><b><?= number_format($total_amounts, 2) ?></b></td>-->
                                <!--</tr>-->

                        </tbody>
            </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    
      </body>
</html>
