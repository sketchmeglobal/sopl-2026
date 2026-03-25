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
        

            	<section class="sheet padding-10mm" style="height: auto">
		<div>
			<!--<header class="pull-right">-->
			<!--    <small>Page No. </small>-->
			<!--</header>-->
			<div class="clearfix"></div>
			<div class="container">
				<div class="row border_all text-center text-uppercase mar_bot_3">
					<h3 class="mar_0 head_font">ADVANCE LEDGER</h3>
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
                                    <th>Status</th>
                                    <th style="text-align: right;">Advance Amount</th>
                                    <th>Advance Adjusted on</th>
                                    <th style="text-align: right;">Advance Adjusted Amount</th>
                                    <th style="text-align: right;">Balance</th>
                                </tr>
                </thead>
                <tbody>
                            <?php
                                    $count = 0;
                                    $i = 1;
                                    $iter = 1;
                                    $balance = 0;
                                    $total_balance = 0;
                                    $show_array_new1 = array();
                                    foreach ($data['result'] as $res)
                                    {
                                        $count = 0;
                                        $i = 1;
                                        $iter = 1;
                                        $balance = 0;
                                        $total_balance = 0;
                                        $new_iter11 = 0;
                                        $show_array_new1 = array();
                                        foreach ($res as $a)
                                        {
                                ?>
                                                                    <tr>
                                                                        <?php
                                            $a->date;
                                            $month_new = strtotime($a->date);
                                            $new_date = date("Y-m-d", strtotime($a->date));
                                            $show_array = array();
                                            $dates = array();
                                            $balance = ($a->ADV + $balance);
                                            $new_iter11 = 0;
                                
                                            $new_sql = "SELECT advance.emp_id,advance.amount AS ADV, MONTHNAME(advance.date) AS MONNAME, advance.date, employees.name, employees.e_code, 1 AS TAG
                                        FROM advance
                                        INNER JOIN(employees)
                                        ON(advance.emp_id=employees.e_id)
                                        WHERE advance.emp_id = $a->emp_id AND STR_TO_DATE(advance.date, '%Y-%m-%d') > '" . $new_date . "'
                                        ORDER BY advance.date asc";
                                
                                            $new_res = $this
                                                ->db
                                                ->query($new_sql)->result();
                                
                                            $extra_sql = "SELECT advance.emp_id,advance.amount AS ADV, MONTHNAME(advance.date) AS MONNAME, advance.date, employees.name, employees.e_code, 1 AS TAG
                                        FROM advance
                                        INNER JOIN(employees)
                                        ON(advance.emp_id=employees.e_id)
                                        WHERE advance.emp_id = $a->emp_id AND STR_TO_DATE(advance.date, '%Y-%m-%d') > '" . $new_date . "'
                                        ORDER BY advance.date asc
                                        ";
                                            $salary_details_extra = $this
                                                ->db
                                                ->query($extra_sql)->num_rows();
                                
                                            // echo $this->db->last_query();
                                            if (count($new_res) > 0)
                                            {
                                                //  echo $new_res[0]->date;
                                                if ($salary_details_extra == 1)
                                                {
                                                    $month_new_next_extra = date("d-m-Y", strtotime("-1 months", strtotime($new_res[0]->date)));
                                                    $month_new_next = strtotime($month_new_next_extra);
                                                }
                                                else
                                                {
                                                    $month_new_next = strtotime($new_res[0]->date);
                                                }
                                                $stepVal = '+1 month';
                                                while ($month_new < $month_new_next)
                                                {
                                                    $dates[] = date('M', $month_new);
                                                    $month_new = strtotime($stepVal, $month_new);
                                                }
                                
                                                //   echo '<pre>', print_r($dates), '</pre>';
                                                
                                
                                                foreach ($dates as $d)
                                                {
                                                    $sql = "SELECT employees.name,e_code,employees.pf_acc_no, salary.LOAN, salary.MON, employees.esi_acc_no,salary.T4,salary.T5,CAST((salary.BASIC+salary.DA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL2,salary.GROSS
                                            FROM salary
                                            INNER JOIN(employees)
                                            ON(salary.EMPCODE=employees.e_id)
                                            WHERE salary.MON LIKE '" . $d . "%' AND employees.e_id='" . $a->emp_id . "'
                                            ORDER BY employees.e_code";
                                                    $salary_details = $this
                                                        ->db
                                                        ->query($sql)->row();
                                                    if (count($salary_details) > 0 && $salary_details->LOAN != 0)
                                                    {
                                                        $balance = $balance - $salary_details->LOAN;
                                                        $last_balance = $balance;
                                                        $total_balance += $salary_details->LOAN;
                                                        $arr = array(
                                                            'loan' => $salary_details->LOAN,
                                                            'mon' => $salary_details->MON,
                                                            'balance' => $balance
                                                        );
                                                        array_push($show_array, $arr);
                                
                                                    }
                                
                                                }
                                
                                            }
                                            else
                                            {
                                                $month_new_next = strtotime(date("Y-m-d"));
                                                $stepVal = '+1 month';
                                                while ($month_new <= $month_new_next)
                                                {
                                                    $dates[] = date('M', $month_new);
                                                    $month_new = strtotime($stepVal, $month_new);
                                                }
                                
                                                foreach ($dates as $d)
                                                {
                                                    $sql = "SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                                    CAST((employees.basic_pay+employees.da_amout) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                                    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC, salary.MON,salary.NET
                                        FROM salary
                                        INNER JOIN(employees)
                                        ON(salary.EMPCODE=employees.e_id)
                                        WHERE salary.MON LIKE '" . $d . "%' AND employees.e_id = $a->emp_id
                                        GROUP BY salary.CODE
                                        ";
                                                    $salary_details = $this
                                                        ->db
                                                        ->query($sql)->row();
                                
                                                    if (count($salary_details) > 0 && $salary_details->LOAN != 0)
                                                    {
                                                        $balance = $balance - $salary_details->LOAN;
                                                        $last_balance = $balance;
                                
                                                        $arr = array(
                                                            'loan' => $salary_details->LOAN,
                                                            'mon' => $salary_details->MON,
                                                            'balance' => $balance
                                                        );
                                                        array_push($show_array, $arr);
                                                    }
                                                }
                                
                                            }
                                
                                            $sql = "SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                                    CAST((employees.basic_pay+employees.da_amout) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                                    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC, salary.MON,salary.NET
                                        FROM salary
                                        INNER JOIN(employees)
                                        ON(salary.EMPCODE=employees.e_id)
                                        WHERE employees.e_id = $a->emp_id
                                        GROUP BY salary.CODE
                                        ";
                                            $res = $this
                                                ->db
                                                ->query($sql)->result();
                                            $count = count($show_array);
                                ?>
                                                                        <?php if ($i == 1)
                                            { ?>
                                                                        <td rowspan="<?=$count + 1 ?>"><?=$a->name . '[' . $a->e_code . ']' ?></td>
                                                                        <td rowspan="<?=$count + 1 ?>"><?='Advance Taken on: ' . $a->MONNAME ?></td>
                                                                        <td rowspan="<?=$count + 1 ?>" style="text-align: right;">
                                                                            <?=$a->ADV
                                ?>
                                                                            <?php
                                                $new_sqll = "SELECT advance.emp_id,advance.amount AS ADV, MONTHNAME(advance.date) AS MONNAME, advance.date
                                        FROM advance
                                        WHERE advance.emp_id = $a->emp_id AND STR_TO_DATE(advance.date, '%Y-%m-%d') < '" . $new_date . "'";
                                                $new_ress = $this
                                                    ->db
                                                    ->query($new_sqll)->num_rows();
                                                if ($new_ress > 0)
                                                {
                                
                                                    $new_sql = "SELECT advance.emp_id, SUM(advance.amount) AS new_adv, MONTHNAME(advance.date) AS MONNAME, advance.date, employees.name, employees.e_code, 1 AS TAG
                                        FROM advance
                                        INNER JOIN(employees)
                                        ON(advance.emp_id=employees.e_id)
                                        WHERE advance.emp_id = $a->emp_id AND STR_TO_DATE(advance.date, '%Y-%m-%d') < '" . $new_date . "'
                                        ORDER BY advance.date";
                                                    $new_res = $this
                                                        ->db
                                                        ->query($new_sql)->row();
                                                        
                                                    echo ' <br/> + <br/> ' . number_format(abs($new_res->new_adv - $total_balance), 2) . '(Prev)';
                                                    $last_balance = 0;
                                
                                                } ?>
                                                                            </td>
                                                                        <?php
                                            } ?>
                                            
                                
                                                                        <?php 
                                                                        
                                                                        // echo '<pre>', print_r($show_array), '</pre>';
                                                                        
                                                                        foreach ($show_array as $r)
                                            {
                                                $show_month_new_array = explode("~", $r['mon'])
                                ?>
                                                                        <tr>
                                                                        <td><?=$show_month_new_array[0] ?></td>
                                                                        <td style="text-align: right;"><?=$r['loan'] ?></td>
                                                                        <td style="text-align: right;"><?=$r['balance'] ?></td>
                                                                        </tr>
                                                                        <?php
                                                $arr = array(
                                                    'loan' => $r['loan'],
                                                    'mon' => $r['mon']
                                                );
                                                array_push($show_array_new1, $arr);
                                                $i++;
                                            }
                                            //   echo '<pre>', print_r($show_array_new1), '</pre>';
                                            $count = 0;
                                            $i = 1;
                                ?>
                                                                    </tr>
                                                                    <?php
                                        }
                                        $iter++;
                                    }
                                ?>
                            
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
