
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Stock Data Sheet || <?=WEBSITE_NAME;?></title>
        <meta name="description" content="Order Status">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
        <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">
        <style>
            body{
                font-family: 'Signika', sans-serif;
                /*font-size: 12.5px;*/
                font-family: Calibri;
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
                font-family: Calibri;
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
            
            @media print{@page {size: landscape}}
			
            @media print{
                .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000;  text-align: center;}
                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}
                .col-sm-6{ width: 50%!important;float:left; }.col-sm-5 { width: 41.66666667%;float:left; }.col-sm-7 { width: 58.33333333%;float:left; }
                .border-bottom{border-bottom:  1px solid #000} .text-right{text-align: right!important;}
                .print-me, .no-print{display: none}
            }
            
            .padding-5mm{padding: 5mm;}
        </style>
    </head>

    <?php 
    // echo '<pre>',print_r($result),'</pre>';
    $bal_qnty = 0;
    $temp_co_name_array1 = array();
    foreach ($result as $co_name){
        if (!in_array($co_name['group_name'], $temp_co_name_array1)){
            array_push($temp_co_name_array1, $co_name['group_name']);
        }
    }
    
    function dateRange($from, $to){
        return array_map(function($arg) {
             return date('Y-m-d', $arg);
        }, range(strtotime($from), strtotime($to), 86400));
    }
    
    $dates = dateRange($from, $to);
    $segments = array_chunk($dates, 11);
    
    // echo '<pre>', print_r($segments), '</pre>'; die;
    ?>
		<body class="A4 landscape" style="height:auto">
            <!--<div class="text-center">-->
            <!--    <a id="dlink" style="display:none;"></a>-->
            <!--    <input type="button" onclick="tablesToExcel(array1, 'Sheet1', 'SOPL-<?=mt_rand()?>.xls')" value="Export to Excel" class="btn btn-success print-me" >-->
            <!--</div>-->
            
            <?php foreach($segments as $segment){ ?>
            
            <section class="sheet padding-5mm" style="height:auto">
    
            <?php $table_no=1; ?>
            
            <!--<header class="pull-right">-->
            <!--    <small>Page No. </small>-->
            <!--</header>-->

            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h3 class="mar_0 head_font">Stock Data Sheet</h3>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-6 border_all header_left">
                        <h4 class=""><strong>SHILPA OVERSEAS PVT. LTD. </strong></h4>
                        <p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136</p>
                    </div>
                    <div class="col-sm-6 border_all header_right">
                      <b><?=implode(', ', $temp_co_name_array1) ?></b>
                      <br/>
                      From <b><?=date("d-m-Y", strtotime($from)) ?></b> To <b><?=date("d-m-Y", strtotime($to)) ?></b>
                    </div>
                </div>
                <!--table data-->
                <div class="row">
                    <div class="container">
                        <!-- Local area -->
                        <div class="row">
                            <div class="table-responsive">
                                <table id="export_table_to_excel<?=$table_no?>" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:right">#</th>
                                            <th style="width:70mm;text-align:center">Item</th>
                                            <th style="text-align:center">Unit</th>
                                            <?php foreach($segment as $seg){?>
                                            <th><?=date('d-M', strtotime($seg))?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $iter = $all_bal_qty = $all_opn_qty = $all_pur_qty = $all_issue_qty = $all_plating_qty = $all_stockin_qty = $all_bal_rate = $all_opn_rate = $all_pur_rate = $all_issue_rate = $all_plating_rate = $all_stockin_rate = $closing_rate = 0;
                                        foreach ($result as $f){
                                            // if($f['type'] == 'Import'){
                                            //     continue;
                                            // }
                                            if ($f['opening_val'] == 0 && $f['opening_qnty'] == 0 && $f['purchase_qnty'] == 0 && $f['issue_qnty'] == 0) {
                                                continue;
                                            }

                                            $bal_qty = $f['opening_qnty'] + $f['purchase_qnty'] - ($f['issue_qnty'] + $f['plating_qnty']) + $f['stock_in_qnty'];
                                            if(($bal_qnty == 0) and ($bal_qty == 0)){
                                                // continue;
                                            }

                                            $bal_rate = $f['opening_val'] + $f['purchase_val'] - ($f['issue_val'] + $f['plating_val']) + $f['stock_in_val'];

                                            $all_opn_qty += $f['opening_qnty'];
                                            $all_opn_rate += $f['opening_val'];
                                            $all_pur_qty += $f['purchase_qnty'];
                                            $all_pur_rate += $f['purchase_val'];
                                            $all_issue_qty += $f['issue_qnty'];
                                            $all_issue_rate += $f['issue_val'];
                                            $all_plating_qty += $f['plating_qnty'];
                                            $all_plating_rate += $f['plating_val'];
                                            $all_stockin_qty += $f['stock_in_qnty'];
                                            $all_stockin_rate += $f['stock_in_val'];
                                            $all_bal_qty += $bal_qty;
                                            $all_bal_rate += $bal_rate;
                                        ?>
                                        <tr>
                                            <th style="text-align:right"><?=++$iter;?></th>
                                            <th><?=$f['item'] . '(' . $f['color'] . ')' ?></th>
                                            <td style="text-align:center"><?= $f['unit'] ?></td>
                                            <?php foreach($segment as $seg){?>
                                            <td></td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
        </section>       
            
            <?php } ?>
            
    <script>
        //table to excel (multiple table)
    var array1 = new Array();
    var n = <?php if(isset($table_no)){echo $table_no;}else{echo 0;} ?>; //Total table
    for ( var x=1; x<=n; x++ ) {
        array1[x-1] = 'export_table_to_excel' + x;
    }
    var tablesToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
            , templateend = '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
            , body = '<body>'
            , tablevar = '<table>{table'
            , tablevarend = '}</table>'
            , bodyend = '</body></html>'
            , worksheet = '<x:ExcelWorksheet><x:Name>'
            , worksheetend = '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>'
            , worksheetvar = '{worksheet'
            , worksheetvarend = '}'
            , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            , wstemplate = ''
            , tabletemplate = '';

        return function (table, name, filename) {
            var tables = table;
            var wstemplate = '';
            var tabletemplate = '';

            wstemplate = worksheet + worksheetvar + '0' + worksheetvarend + worksheetend;
            for (var i = 0; i < tables.length; ++i) {
                tabletemplate += tablevar + i + tablevarend;
            }

            var allTemplate = template + wstemplate + templateend;
            var allWorksheet = body + tabletemplate + bodyend;
            var allOfIt = allTemplate + allWorksheet;

            var ctx = {};
            ctx['worksheet0'] = name;
            for (var k = 0; k < tables.length; ++k) {
                var exceltable;
                if (!tables[k].nodeType) exceltable = document.getElementById(tables[k]);
                ctx['table' + k] = exceltable.innerHTML;
            }

            // window.location.href = uri + base64(format(allOfIt, ctx));

            document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));;
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
        }
    })();
    </script>
</body>
		
		
</html>