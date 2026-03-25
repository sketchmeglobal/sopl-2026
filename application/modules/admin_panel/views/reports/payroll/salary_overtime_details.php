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
        <div id="page-content">
		<section class="sheet padding-10mm" style="height: auto;">
		<div>
			<!--<header class="pull-right">-->
			<!--    <small>Page No. </small>-->
			<!--</header>-->
			<div class="clearfix"></div>
			<div class="container">
				<div class="row border_all text-center text-uppercase mar_bot_3">
					<h3 class="mar_0 head_font">SALARY - OVERTIME DETAILS</h3>
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
                                    <th colspan="4">SHILPA OVERSEAS PVT. LTD. <br /> 51, Mahanirban Road, Kolkata-700029</th>
                                    <th colspan="3">
                                        Month: <?=$data['mont'] ?><br />
                                        Date: <?=date('d-m-Y') ?><br />
                                    </th>
                                </tr>
                                <tr>
                                    <th>SL <br/> NO</th>
                                    <th>NAME</th>
                                    <th style="text-align: right;">OT. <br/> RATE</th>
                                    <th style="text-align: right;">OT. <br/> HOURS</th>
                                    <th style="text-align: right;">OT. <br/> Total</th>
                                    <th style="text-align: right;">FACT <br/> ACT <br/> OT <br/> Hrs. <br/> MAX</th>
                                    <th style="text-align: right;">BALANCE</th>
                                    <th style="text-align: right;">FACTORY OT.</th>
                                    <th style="text-align: right;">PROD. <br/> BONUS</th>
                                    <th style="text-align: right;">TOTAL</th>
                                    <th style="text-align: right;">ESI <br/> DEDUCT</th>
                                    <th style="text-align: right;">OTHRS/ <br/> ADVNC</th>
                                    <th style="text-align: right;">TOTAL <br/> DEDUCT.</th>
                                    <th style="text-align: right;">NET PAY</th>
                                    <th style="text-align: right;">SALARY</th>
                                    <th style="text-align: right;">GRAND TOTAL</th>
                                </tr>
                </thead>
                <tbody>
                            <?php
    $iter = 1;
    $ot_rate = 0;
    $ot_hours = 0;
    $ot_total = 0;
    $factory_act_ot_hrs_max = 0;
    $balance_ot_hrs = 0;
    $factory_ot = 0;
    $p_bonus = 0;
    $bonus = 0;
    $with_bonus_amnt = 0;
    $esi = 0;
    $advance = 0;
    $grand_total = 0;
    $net_salary_pay = 0;
    $deduction_total = 0;
    $net_pay = 0;
    if (count($data['result']) > 0)
    {
        foreach ($data['result'] as $res)
        {
            foreach ($res as $a)
            {
?>
                                    <tr>
                                        <td style="width: 20px;"><?=$iter++
?></td>
                                        <td style="width: 140px;"><?=$a->name
?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo $a->ot_rate;
                $ot_rate += $a->ot_rate; ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo $a->ot_hours;
                $ot_hours += $a->ot_hours; ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->ot_total) , 2);
                $ot_total += round($a->ot_total); ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo $a->factory_act_ot_hrs_max;
                $factory_act_ot_hrs_max += $a->factory_act_ot_hrs_max; ?></td>
                                        <td style="text-align: right; width: 20px;"><?php if ($a->balance_ot_hrs > 0)
                {
                    echo $a->balance_ot_hrs;
                    $balance_ot_hrs += $a->balance_ot_hrs;
                }
                else
                {
                    echo 0;
                    $balance_ot_hrs += 0;
                } ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->factory_ot) , 2);
                $factory_ot += round($a->factory_ot); ?></td>
                                        <td style="text-align: right; width: 20px;"><?php $p_bonus = (round($a->ot_total) - round($a->factory_ot));
                echo number_format(round($p_bonus) , 2);
                $bonus += round($p_bonus); ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->with_bonus_amnt) , 2);
                $with_bonus_amnt += round($a->with_bonus_amnt) ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->esi) , 2);
                $esi += round($a->esi) ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->advance) , 2);
                $advance += round($a->advance) ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(round($a->deduction_total) , 2);
                $deduction_total += round($a->deduction_total); ?></td>
                                        <td style="text-align: right; width: 20px;"><?php $new_payable = (round($a->with_bonus_amnt) - round($a->deduction_total)); echo number_format(round($new_payable) , 2);
                $net_pay += round($new_payable); ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format($a->NET, 2); $net_salary_pay += $a->NET;  ?></td>
                                        <td style="text-align: right; width: 20px;"><?php echo number_format(($a->NET + $new_payable), 2); $grand_total += ($a->NET + $new_payable) ?></td>
                                    </tr>
                                    <?php
            }
        }
    }
?>
                                <tr>
                                        <th colspan="2">Grand Total</th>
                                        <td style="text-align: right;"><?=$ot_rate
?></td>
                                        <td style="text-align: right;"><?=$ot_hours
?></td>
                                        <td style="text-align: right;"><?=number_format(round($ot_total) , 2) ?></td>
                                        <td style="text-align: right;"><?=$factory_act_ot_hrs_max ?></td>
                                        <td style="text-align: right;"><?=$balance_ot_hrs ?></td>
                                        <td style="text-align: right;"><?php echo number_format(round($factory_ot) , 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format(round($bonus) , 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format(round($with_bonus_amnt), 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($esi, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($advance, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($deduction_total, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($net_pay, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($net_salary_pay, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($grand_total, 2); ?></td>
                                    </tr>
                            
                        </tbody>
            </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	</div>
            
            
    </body>
</html>
