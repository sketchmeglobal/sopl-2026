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
		<body class="A4 landscape" style="overflow-x: auto; padding-top: 20px">
        <div id="page-content">
		<section class="sheet padding-10mm" style="height: auto;">
		<div>
			<!--<header class="pull-right">-->
			<!--    <small>Page No. </small>-->
			<!--</header>-->
			<div class="clearfix"></div>
			<div class="container">
				<div class="row border_all text-center text-uppercase mar_bot_3">
					<!--<h3 class="mar_0 head_font"> BONUS SHEET </h3>-->
					<h3 class="mar_0 head_font"> EX Gratia SHEET </h3>
				</div>
				<!-- <div class="row mar_bot_3">
					<div class="col-sm-6 border_all header_left">
						<h4 class=""><strong>SHILPA OVERSEAS PVT. LTD. </strong></h4>
						<p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136</p>
					</div>
					<div class="col-sm-6 border_all header_right">
						<br />
					</div>
				</div> -->
				<!--table data-->
				<div class="row">
					<div class="container">
						<div class="row">
							<div class="table-responsive">
								<!--<h5>Retrieve Table</h5>-->
								<table id="all_det" class="table table-bordered">
									<thead>

									    <?php
									    
									    //print_r($data);
									    //$did = $data[''];
									    if($data['d_id'] == 5) { ?>
                                        <tr>
                                            <th colspan="4">SHILPA OVERSEAS PVT. LTD. <br /> 51, Mahanirban Road, Kolkata-700029</th>
                                            <th colspan="13">
                                                Year: <?= date('Y') . ' - ' . (date('Y') + 1) ?> <br />
                                                Department: <?= $data['departments_lists'] ?> <br />
                                                <!--Bonus: 20%<br />-->
                                            </th>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <th colspan="2">SHILPA OVERSEAS PVT. LTD. <br /> 51, Mahanirban Road, Kolkata-700029</th>
                                            <th colspan="2">
                                                Year:  <?= date('Y') . ' - ' . (date('Y') + 1) ?> <br />
                                                Department: <?= $data['departments_lists'] ?> <br />
                                                <!--Bonus: 20%<br />-->
                                            </th>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <th>Sl <br/> No</th>
                                            <th>Name</th>
                                            <?php if($data['d_id'] == 5) { ?>
                                            <th style="text-align: right;">April</th>
                                            <th style="text-align: right;">May</th>
                                            <th style="text-align: right;">June</th>
                                            <th style="text-align: right;">July</th>
                                            <th style="text-align: right;">August</th>
                                            <th style="text-align: right;">September</th>
                                            <th style="text-align: right;">October</th>
                                            <th style="text-align: right;">November</th>
                                            <th style="text-align: right;">December</th>
                                            <th style="text-align: right;">January</th>
                                            <th style="text-align: right;">February</th>
                                            <th style="text-align: right;">March</th>
                                            <th style="text-align: right;">Total</th>
                                            <th style="text-align: right;">Bonus</th>
                                            <?php } else { ?>
                                            <th style="text-align: right;">Ex gratia</th>
                                            <?php } ?>
                                            <th>Signature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    
                                    $total_salary = 0;
                                    $iter = 1;
                                    $grand_total=0;
                                    $grand_bonus = 0;
                                    $grand_ex_gratia = 0;
                            
                                    if (count($data['result']) > 0)
                                    {
                                        foreach ($data['result'] as $res)
                                        {
                                            
                                            $total_april = 0;
                                            $total_may = 0;
                                            $total_june = 0; 
                                            $total_july = 0;
                                            $total_august = 0;
                                            $total_september = 0;
                                            $total_october = 0; 
                                            $total_november = 0;
                                            $total_december = 0;
                                            $total_january = 0;
                                            $total_february = 0;
                                            $total_march = 0;
                                            foreach ($res as $key=>$a)
                                            {
                                            ?>          
                                            <tr>
                                                <td style=""><?=$iter++?></td>
                                                <td style=><?=$a['name']?></td>
                                                <?php if($data['d_id'] == 5) { ?>
                                                <td><?php if(isset($a['April~30~4'])) {echo $a['April~30~4']; $total_april = $a['April~30~4'];  } ?></td>
                                                <td><?php if(isset($a['May~31~5'])) {echo $a['May~31~5']; $total_may = $a['May~31~5']; } ?></td>
                                                <td><?php if(isset($a['June~30~6'])) {echo $a['June~30~6']; $total_june = $a['June~30~6']; } ?></td>
                                                <td><?php if(isset($a['July~31~7'])) {echo $a['July~31~7']; $total_july = $a['July~31~7']; } ?></td>
                                                <td><?php if(isset($a['August~31~8'])) {echo $a['August~31~8']; $total_august = $a['August~31~8']; } ?></td>
                                                <td><?php if(isset($a['September~30~9'])) {echo $a['September~30~9']; $total_september = $a['September~30~9']; } ?></td>
                                                <td><?php if(isset($a['October~31~10'])) {echo $a['October~31~10']; $total_october = $a['October~31~10']; } ?></td>
                                                <td><?php if(isset($a['November~30~11'])) {echo $a['November~30~11']; $total_november = $a['November~30~11']; } ?></td>
                                                <td><?php if(isset($a['December~31~12'])) {echo $a['December~31~12']; $total_december = $a['December~31~12']; } ?></td>
                                                <td><?php if(isset($a['January~31~1'])) {echo $a['January~31~1']; $total_january = $a['January~31~1']; } ?></td>
                                                <td>
                                                    <?php if(isset($a['February~28~2'])) {echo $a['February~28~2']; $total_february = $a['February~28~2']; } ?>
                                                    <?php if(isset($a['February~29~2'])) {echo $a['February~29~2']; $total_february = $a['February~29~2']; } ?>
                                                </td>
                                                <td><?php if(isset($a['March~31~3'])) {echo $a['March~31~3']; $total_march = $a['March~31~3']; } ?></td>
                                                <td style="text-align:right">
                                                    <?php 
                                                        echo $all_total = $total_april + $total_may + $total_june + $total_july + $total_august + $total_september + $total_october + $total_november + $total_december + $total_january + $total_february + $total_march;
                                                        $grand_total += $all_total;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        echo $all_bonus = round(($total_april + $total_may + $total_june + $total_july + $total_august + $total_september + $total_october + $total_november + $total_december + $total_january + $total_february + $total_march) * 20/100);
                                                        $grand_bonus += $all_bonus;
                                                    ?></td>
                                                <?php } else { ?>
                                                <td style="text-align:right"><?php echo $a['TOTAL1']; $grand_ex_gratia+=$a['TOTAL1'] ?></td>
                                                <?php } ?>
                                                <td></td>
                                            </tr>
                                            <?php
                                            }
                                            $total_salary = 0;
                                        }
                                    }
                                    if($data['d_id'] == 5) {
                                    ?>
                                        <tr>
                                            <th colspan="14">Total</th>
                                            <th style="text-align:right"><?=$grand_total?></th>
                                            <th style="text-align:right"><?=$grand_bonus?></th>
                                        </tr>
                                    <?php } else{
                                        ?>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th style="text-align:right"><?=$grand_ex_gratia?></th>
                                            <th></th>
                                        </tr>
                                        <?php
                                        } ?>        
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