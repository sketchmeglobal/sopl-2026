<?php //echo '<pre>', print_r($data), '</pre>';die(); ?>
<?php
    $mont = $data['mont'];
    //$month = $data['month'];
    $result = $data['result'];

?>
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
            .sheet.padding-10mm {
                padding: 10mm;
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
       <section class="sheet padding-10mm" style="height: auto">
		<div>
			<!--<header class="pull-right">-->
			<!--    <small>Page No. </small>-->
			<!--</header>-->
			<div class="clearfix"></div>
			<div class="container">
				<div class="row border_all text-center text-uppercase mar_bot_3">
					<h3 class="mar_0 head_font">P.F. DETAILS</h3>
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
                                    <th colspan="5">SHILPA OVERSEAS PVT. LTD. <br /> 51, Mahanirban Road, Kolkata-700029</th>
                                    <th colspan="3">
                                        Month: <?=$mont ?><br />
                                        Date: <?=date('d-m-Y') ?><br />
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Sr. #</th>
                                    <th rowspan="2">Emp. Name</th>
                                    <th rowspan="2">P.F. A/C No.</th>
                                    <th rowspan="2">Actual Days Worked</th>
                                    <th rowspan="2" style="text-align: right;">Wages Eor EPF</th>
                                    <th class="text-center" rowspan="1">Employee</th>
                                    <th class="text-center" colspan="2" rowspan="1">Employer</th>
                                </tr>
                                <tr>
                                    <th rowspan="1" style="text-align: right;">P.F. @ 12%</th>
                                    <th rowspan="1" style="text-align: right;">P.F. @ 8.33%</th>
                                    <th rowspan="1" style="text-align: right;">P.F. @ 3.67%</th>
                                </tr>
                </thead>
                <tbody>
                            <?php
    $iter = 1;
    $total11 = 0;
    $total12 = 0;
    $total13 = 0;
    $total14 = 0;
    foreach ($result as $res)
    {
        foreach ($res as $a)
        {
?>
                                    <tr>
                                        <td><?=$iter++
?></td>
                                        <td><?=$a->name . '[' . $a->e_code . ']' ?></td>
                                        <td><?=$a->pf_acc_no ?></td>
                                        <td><?=($a->T1 - $a->T7) ?></td>
                                        <?php if ($a->pf_percentage_calculation != 'contractual')
            { ?>
                                        <td style="text-align: right;">
                                        <?php 
                                            echo $a->TOTAL2;
                                            $total11 += $a->TOTAL2; 
                                        ?>
                                        </td>
                                        <td style="text-align: right;">
                                        <?php 
                                            echo $val = floor(($a->TOTAL2 * (12 / 100)) > 1800) ? 1800 : floor($a->TOTAL2 * (12 / 100));
                                            $total12 += $val; 
                                        ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php 
                                                echo $pf2 = floor(($a->TOTAL2 * (8.33 / 100)) > 1250) ? 1250 : floor($a->TOTAL2 * (8.33 / 100));
                                                $total13 += $pf2; 
                                            ?>
                                        </td>
                                        <td style="text-align: right;">
                                        <?php 
                                            echo $pf3 = floor(($a->TOTAL2 * (3.67 / 100)) > 550) ? 550 : floor($a->TOTAL2 * (3.67 / 100));
                                            $total14 += $pf3; 
                                        ?>
                                        </td>
                                    <?php
            }
            else
            { ?>
                                        <td style="text-align: right;"><?php echo $a->TOTAL3;
                $total11 += $a->TOTAL3; ?></td>
                                        <td style="text-align: right;">
                                        <?php 
                                            echo $val = floor(($a->TOTAL3 * (12 / 100)) > 1800) ? 1800 : floor($a->TOTAL3 * (12 / 100));
                                            $total12 += $val; 
                                        ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php 
                                                echo $pf2 = floor(($a->TOTAL3 * (8.33 / 100)) > 1250) ? 1250 : floor($a->TOTAL3 * (8.33 / 100));
                                                $total13 += $pf2; 
                                            ?>
                                        </td>
                                        <td style="text-align: right;">
                                        <?php 
                                            echo $pf3 = floor(($a->TOTAL3 * (3.67 / 100)) > 550) ? 550 : floor($a->TOTAL3 * (3.67 / 100));
                                            $total14 += $pf3; 
                                        ?>
                                        </td>
                                    <?php
            } ?>
                                        
                                    </tr>
                                    <?php
        }
    }
?>
                                
                                <tr>
                                    <th colspan = "4"> Grand Total </th>
                                    <th style="text-align: right;"><?=$total11
?></th>
                                    <th style="text-align: right;"><?=$total12
?></th>
                                    <th style="text-align: right;"><?=$total13
?></th>
                                    <th style="text-align: right;"><?=$total14
?></th>
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
    </body>
</html>
