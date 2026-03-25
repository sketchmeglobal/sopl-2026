<?PHP
//echo '<pre>', print_r($res), '</pre>';
$tot = 0;
$tot_inr = 0;
$tot_price_ext = 0;
foreach ($res as $r) {
    $tot_inr += $r->price_inr;
    $tot += $r->article_quantity;
    $tot_price_ext += $r->price_foreign;
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Courier Invoice</title>

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
            .height_42{ height: 42px }
            .height_135{height: 150px}
            .height_90{height: 90px}
            .height_100{height: 100px}
            .height_110{height: 110px}
            .height_41{ height: 41px }
            .height_23{ height: 23px }
            .height_63{ height: 63px }
            .height_21{ height: 21px }

            .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000!important;  text-align: center;}
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}

            .border-bottom{border-bottom:  1px solid #000}

            @page { size: A4 }

            @media print{
                .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th { border: 1px solid #000;  text-align: center;}
                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding: 5px; text-align: left; font-size: 13px}
                .col-sm-6{ width: 50%!important;float:left; }.col-sm-5 { width: 41.66666667%;float:left; }.col-sm-7 { width: 58.33333333%;float:left; }
                .border-bottom{border-bottom:  1px solid #000}
            }
            .all-p-o > p{
                padding:0px !important;
            }
        </style>
    </head>

    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4" id="page-content">
        <?php
        $page_no = 1;
        ?>
        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <div>
                <header class="pull-right">
                    <small>Page No. <?= $page_no ?></small>
                </header>
                <div class="clearfix"></div>
                <div class="container">
                    <div class="row border_all text-center text-uppercase mar_bot_3">
                        <h3 class="mar_0 head_font">Invoice</h3>
                    </div>
                    <div class="row mar_bot_3">
                        <div class="col-sm-6 border_all header_left">
                            <p class="mar_0"><strong>Sender</strong></p>
                            <h4  class="mar_0"><strong>SHILPA OVERSEAS PVT. LTD. </strong></h4>
                            <p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 052</p>
                            <!--<p class="mar_0">51, MAHANIRBAN ROAD, KOLKATA - 700029</p>-->
                            <p class="mar_0">PHONE: +91 2573-3470/71/72/2405</p>
                            <!--<p class="mar_0">FAX:+91-33-40012865</p>-->
                            <p class="mar_0">Email : goutam.dey@shilpaoverseas.com</p>
                            <p class="mar_0">GSTIN: 19AAECS6338L1ZT</p>
                        </div>
                        <div class="col-sm-6 header_right">
                            <div class="row mar_bot_3">
                                <div class="col-sm-12 border_all height_60">
                                    <div class="">
                                        <p class="mar_0">Invoice No. & Date</p>
                                        <h5 class="mar_0"><strong><?= $res[0]->invoice_no . ',<br /> dated, '. date('d-m-Y', strtotime($res[0]->invoice_date)) ?></strong></h5>
                                        
                                    </div>
                                </div>
                                <!--<div class="col-sm-6 border_all height_60">-->
                                <!--    <div class="">-->
                                        <!--<p class="mar_0"></p>-->
                                        <!--<p class="mar_0">Export GSTIN: 19AAECS6338L1ZT</p>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div>
                            <div class="row border_all height_63 mar_bot_3">
                                <div class="col-sm-12">
                                    <h4 class="mar_0">AWB No.: </h4>
                                    <h4 class="mar_0">
                                        <strong>
                                            <?= $res[0]->awb_number ?>
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                            <div class="row border_all height_21">
                                <div class="col-sm-12">
                                    <p class="mar_0">Booking Number : <?= $res[0]->remarks ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row height_90 mar_bot_3">
                        <div class="col-sm-6 border_all height_90">
                            <p class="mar_0"><strong>Ship To: </strong><?= isset($res[0]->name) ? $res[0]->name : '' ?></p>
                            <!--<h5 class="mar_0"><strong>< ?= isset($fetch_all_courier_dtl[0]->ACC_MASTER_NAME) ? $fetch_all_courier_dtl[0]->ACC_MASTER_NAME : '' ?></strong></h5>-->
                            <article style="font-size:12px;line-height:1"><?= $res[0]->address ?></article>
                            <p class="mar_0" style="font-size:12px"></p>
                        </div>
                        <div class="col-sm-6">

                            <div class="row height_23">
                                <div class="col-sm-6 border_all height_23">
                                    <div class="">
                                        <small><strong>Country of Origin of Goods</strong></small>
                                    </div>
                                </div>
                                <div class="col-sm-6 height_23 border_all">
                                    <div class="">
                                        <p class=""> West Bengal / India </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row height_23 mar_bot_3">
                                <div class="col-sm-6 border_all height_23">
                                    <div class="">
                                        <small><strong>Country of final delivery</strong></small>
                                    </div>
                                </div>
                                <div class="col-sm-6 border_all height_23">
                                    <div class="">
                                        <p class="text-capitalize">
                                            <?= strtoupper($res[0]->country) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row height_41 border_all">
                                <div class="col-sm-12">
                                    <p class="mar_0">
                                        <strong>Contact Person:</strong>
                                        <?= $res[0]->proprietor ?>
                                    </p>
                                    <p class="mar_0">
                                        <strong>Contact No.:</strong>
                                        <?= $res[0]->phone ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mar_bot_3">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12 height_110 border_all">
                                    <p class="mar_0 border_bottom" style="margin-top:5px;font-size: 13px;"><strong>Carrier: </strong><?= $res[0]->courier_name ?> </p>
                                    <p class="mar_0 border_bottom" style="font-size: 13px;"><strong>Pieces.: </strong><?= $res[0]->pieces ?></p>
                                    <p class="mar_0 border_bottom" style="font-size: 13px;"><strong>Total Weight: </strong><?= $res[0]->weight ?></p>
                                    <!--<p class="mar_0 border_bottom"><strong>Total Price: </strong> < ?= $fetch_all_courier_dtl[0]->CURR_NAME ?>: < ?= $tot_price_ext . '.00' ?></p>-->
                                    <div class="all-p-o" style="display:flex;">
                                        
                                        <p class="p-0" style="font-size: 13px;"><strong>Total Price: </strong></p>
                                        <p class="p-0" style="font-size: 13px;"><?= $res[0]->currency . '('. $res[0]->currency_sign .')' ?>:</p>
                                        <p class="p-0" style="font-size: 13px;"><?= number_format($tot_price_ext, 2) ?></p>
                                    </div>
                                    

                                    <p class="mar_0" style="font-size: 13px;
    position: relative;
    top: -8px;
    line-height: 13px;"><strong>Dimension: </strong><?= $res[0]->box_dimention ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12 height_110 border_all">
                                    <p class="mar_0"><strong>Reason of Export</strong></p>
                                    <h6 class="">
                                        Free trade sample, No commercial value, value declared for customs purpose only.
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--table data-->
                    <div class="row">
                        <h4 class="text-center border-bottom">Courier Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover width-100 table2excel" >
                                <thead>
                                    <tr>
                                        <th>Sr. #</th>
                                        <th>Article Description</th>
                                        <th>HSN Code</th>
                                        <!--<th>Leather Type</th>-->
                                        <th style="text-align: center;">Qnty. (Pcs.)</th>
                                        <th style="text-align: center;"><div style="display: flex; width: fit-content; margin: 0 auto;"><p>Price</p> <p>(<?= $res[0]->currency . ' ('. $res[0]->currency_sign .')' ?>)</p></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $iter = 1;
                                    $tot_qnty = 0;
                                    $tot_amnt = 0;
                                    foreach ($res as $r) {
                                        if ($iter == 14 or $iter == 32 or $iter == 50 or $iter == 68 or $iter == 86 or $iter == 104 or $iter == 122 or $iter == 140 or $iter == 158 or $iter == 176 or $iter == 194 or $iter == 212 or $iter == 230 or $iter == 248 or $iter == 266 or $iter == 284 or $iter == 302 or $iter == 320 or $iter == 338 or $iter == 356 or $iter == 374 or $iter == 392 or $iter == 410 or $iter == 428 or $iter == 446 or $iter == 464 or $iter == 482 or $iter == 500 or $iter == 518 or $iter == 536 or $iter == 554 or $iter == 572 or $iter == 590 or $iter == 608 or $iter == 626 or $iter == 644 or $iter == 662 or $iter == 680 or $iter == 698 or $iter == 716 or $iter == 734 or $iter == 752 or $iter == 770 or $iter == 788 or $iter == 806 or $iter == 824 or $iter == 842 or $iter == 860 or $iter == 878 or $iter == 898 or $iter == 914 or $iter == 932 or $iter == 950 or $iter == 968 or $iter == 986 or $iter == 1004 or $iter == 1022 or $iter == 1040 or $iter == 1058 or $iter == 1076 or $iter == 1094 or $iter == 1112 or $iter == 1130 or $iter == 1148 or $iter == 1166 or $iter == 1184 or $iter == 1202 or $iter == 1220 or $iter == 1238 or $iter == 1256 or $iter == 1274 or $iter == 1292 or $iter == 1310 or $iter == 1328 or $iter == 1346 or $iter == 1364 or $iter == 1382 or $iter == 1400 or $iter == 1418 or $iter == 1436 or $iter == 1454 or $iter == 1472 or $iter == 1490 or $iter == 1508 or $iter == 1526 or $iter == 1544 or $iter == 1562 or $iter == 1580 or $iter == 1598 or $iter == 1616 or $iter == 1634 or $iter == 1652 or $iter == 1670 or $iter == 1688 or $iter == 1706) {
                                            ?>
                                </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <footer>
                                    <div class="col-sm-6 border_all height_135">
                                        <h6 class="mar_0 border-bottom text-justify">
                                           I declare that the above information is true and correct to the best of my knowledge and that the goods are of Indian origin.
                                            <br />
                                            &nbsp;
                                        </h6>
                                        <h6 class="border-bottom text-justify">
                                            For and on behalf of the above named company<br />
                                            Name: <b>Shipa Overseas Pvt. Ltd.</b><br />
                                            Position: <b>Manager</b>
                                            <br />
                                           &nbsp;
                                        </h6>
                                    </div>
                                    <div class="col-sm-6 border_all height_135">
                                        <p class="mar_0">Signature & Date</p>
                                        <h6 class="mar_0 text-uppercase"><strong>Shilpa overseas (Pvt.) Ltd</strong></h6>
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="">
                                                    <p class="mar_0">Authorised Signatory</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="">
                                                    <p class="mar_0 text-right"><?= date('d-m-Y', strtotime($res[0]->create_date)) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </footer>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sheet padding-10mm">
                    <header class="pull-right">
                        <small>Page No. <?= ++$page_no ?></small>
                    </header>
                    <div class="clearfix"></div>
                    <div class="container">
                        <div class="">
                            <div class="row border_all text-center text-uppercase mar_bot_3">
                                <h3 class="mar_0 head_font"> Invoice</h3>
                            </div>
                            <div class="row mar_bot_3">
                                <div class="col-sm-6 border_all header_left">
                                    <p class="mar_0"><strong>Sender</strong></p>
                                    <h4  class=""><strong>SHILPA OVERSEAS PVT. LTD. </strong></h4>
                                    <p class="mar_0">KAIKHALI, CHIRIAMORE,P.O. : R.GOPALPUR, KOLKATA - 700 136</p>
                                    <!--<p class="mar_0">51, MAHANIRBAN ROAD, KOLKATA - 700029</p>-->
                                    <p class="mar_0">PHONE: +91 2573-3470/71/72/2405</p>
                                    <!--<p class="mar_0">FAX:+91-33-40012865</p>-->
                                    <p class="mar_0">Email : goutam.dey@shilpaoverseas.com</p>
                                    <!--<p class="mar_0">CIN-U19116WB1992PTC055524</p>-->
                                </div>
                                <div class="col-sm-6 header_right">
                                    <div class="row mar_bot_3">
                                        <div class="col-sm-12 border_all height_60">
                                            <div class="">
                                                <p class="mar_0">Invoice No.  & Date</p>
                                                <h5 class="mar_0"><strong><?= $res[0]->invoice_no . ',<br /> dated, '. date('d-m-Y', strtotime($fetch_all_courier_dtl[0]->invoice_date)) ?></strong></h5>
                                                
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-6 border_all height_60">-->
                                        <!--    <div class="">-->
                                                <!--<p class="mar_0"></p>-->
                                                <!--<p class="mar_0">Export GSTIN: 19AAECS6338L1ZT</p>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div>
                                    <div class="row border_all height_63 mar_bot_3">
                                        <div class="col-sm-12">
                                            <h4 class="mar_0">AWB No.: </h4>
                                            <h4 class="mar_0">
                                                <strong>
                                                    <?= $res[0]->awb_number ?>
                                                </strong>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row border_all height_21">
                                        <div class="col-sm-12">
                                            <p class="mar_0">Other Reference(s) : </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row height_90 mar_bot_3">
                                <div class="col-sm-6 border_all height_90">
                                    <p class="mar_0"><strong>Ship To: </strong><?= isset($res[0]->name) ? $res[0]->name : '' ?></p>
                                    <!--<h5 class="mar_0"><strong>< ?= isset($fetch_all_courier_dtl[0]->ACC_MASTER_NAME) ? $fetch_all_courier_dtl[0]->ACC_MASTER_NAME : '' ?></strong></h5>-->
                                    <article style="font-size:12px;line-height:1"><?= $res[0]->address ?></article>
                                    <p class="mar_0" style="font-size:12px"></p>
                                </div>
                                <div class="col-sm-6">
        
                                    <div class="row height_23">
                                        <div class="col-sm-6 border_all height_23">
                                            <div class="">
                                                <small><strong>Country of Origin of Goods</strong></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 height_23 border_all">
                                            <div class="">
                                                <p class=""> West Bengal / India </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row height_23 mar_bot_3">
                                        <div class="col-sm-6 border_all height_23">
                                            <div class="">
                                                <small><strong>Country of final delivery</strong></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 border_all height_23">
                                            <div class="">
                                                <p class="text-capitalize">
                                                    <?= strtoupper($res[0]->country) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row height_41 border_all">
                                        <div class="col-sm-12">
                                            <p class="mar_0">
                                                <strong>Contact Person:</strong>
                                                <?= $res[0]->proprietor ?>
                                            </p>
                                            <p class="mar_0">
                                                <strong>Contact No.:</strong>
                                                <?= $res[0]->phone ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row table-responsive">
                                <table class="table table-bordered table-hover table2excel">
                                    <thead>
                                        <tr>
                                            <th>Sr. #</th>
                                            <th>Article Description</th>
                                            <th>HS Code</th>
                                            <!--<th>Leather Type</th>-->
                                            <th style="text-align: center;">Qnty. (Pcs.)</th>
                                            <th style="text-align: center;"><div style="display: flex; width: fit-content; margin: 0 auto;"><p>Price</p> <p>(<?= $res[0]->currency . ' ('. $res[0]->currency_sign .')' ?>)</p></div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $iter ?></td>
                                        <td><?=  $r->art_no . ' ('.$r->info.')'  ?></td>
                                        <td><?= ($r->hs_code == '') ? $r->remark : $r->hs_code ?></td>
                                        <!--<td>< ?= $ppl->LEATHER_TYPE ?></td>-->
                                        <td style="text-align: center;"><?= $r->article_quantity ?></td>
                                        <td style="text-align: center;"><?= number_format($r->price_foreign, 2) ?></td>
                                    </tr>
                                    

                                    <?php
                                    $last_iter = $iter;
                                    $last_page_no = $page_no;
                                    $iter++;
                                }
                                ?>
                                <tr>
                                    <td colspan="2" style="text-align:left"><b>Leather Type:</b> <?= $res[0]->leather_type ?> </td>
                                    <td style="text-align:left"><b>Total:</b></td>
                                    <td style="text-align:center"><h5 class="mar_0"><b><?php echo $tot ?></b></h5> </td>
                                    <td style="text-align:center"><h5 class="mar_0"><b><?= number_format($tot_price_ext, 2) ?></b></h5> </td>  
                                </tr>
                                <?php
                                if ($last_page_no == 1) {
                                    $add_td = (13 - $last_iter);
                                } else {
                                    $temp_add = ($last_iter - 18) % 10;
                                    if ($temp_add == 0) {
                                        $add_td = 0;
                                    } else {
                                        $add_td = 18 - $temp_add;
                                    }
                                }
                                // echo $last_iter . ' => ' . $last_page_no;
                                // echo 'td to be added. =>' . $add_td;die;
                                for ($i = 1; $i < $add_td; $i++) {
                                    ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <footer>

                        <div class="col-sm-6 border_all height_135">
                            <div class="">
                                <h6 class="border-bottom text-justify">
                                   I declare that the above information is true and correct to the best of my knowledge and that the goods are of Indian origin.
                                   <br />
                                   &nbsp;
                                </h6>
                                <h6 class="border-bottom text-justify">
                                    For and on behalf of the above named company<br />
                                    Name: <b>Shipa Overseas Pvt. Ltd.</b><br />
                                    Position: <b>Manager</b>
                                    <br />
                                   &nbsp;
                                </h6>
                            </div>
                        </div>
                        <div class="col-sm-6 border_all height_135">

                            <p class="mar_0">Signature & Date</p>
                            <h6 class="mar_0 text-uppercase"><strong>Shilpa overseas (Pvt.) Ltd</strong></h6>
                            <br />
                            <br />
                            <br />
                            <br />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        <p class="mar_0">Authorised Signatory</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="">
                                        <p class="mar_0 text-right"><?= date('d-m-Y', strtotime($res[0]->create_date)) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </section>
        <?php

        function convertNumberToWord($number) {
            $hyphen = '-';
            $conjunction = ' and ';
            $separator = ', ';
            $negative = 'negative ';
            $decimal = ' point ';
            $dictionary = array(
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
                20 => 'twenty',
                30 => 'thirty',
                40 => 'fourty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
                100 => 'hundred',
                1000 => 'thousand',
                100000 => 'lakh',
                10000000 => 'crore'
            );

            if (!is_numeric($number)) {
                return false;
            }

            if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
                // overflow
                trigger_error(
                        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
                );
                return false;
            }

            if ($number < 0) {
                return $negative . convertNumberToWord(abs($number));
            }

            $string = $fraction = null;

            if (strpos($number, '.') !== false) {
                list($number, $fraction) = explode('.', $number);
            }

            switch (true) {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens = ((int) ($number / 10)) * 10;
                    $units = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                    break;
                case $number < 1000:
                    $hundreds = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 100000:
                    $thousands = ((int) ($number / 1000));
                    $remainder = $number % 1000;

                    $thousands = convertNumberToWord($thousands);

                    $string .= $thousands . ' ' . $dictionary[1000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 10000000:
                    $lakhs = ((int) ($number / 100000));
                    $remainder = $number % 100000;

                    $lakhs = convertNumberToWord($lakhs);

                    $string = $lakhs . ' ' . $dictionary[100000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                case $number < 1000000000:
                    $crores = ((int) ($number / 10000000));
                    $remainder = $number % 10000000;

                    $crores = convertNumberToWord($crores);

                    $string = $crores . ' ' . $dictionary[10000000];
                    if ($remainder) {
                        $string .= $separator . convertNumberToWord($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = convertNumberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= convertNumberToWord($remainder);
                    }
                    break;
            }

            if (null !== $fraction && is_numeric($fraction)) {
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $dictionary[$number];
                }
                $string .= implode(' ', $words);
            }

            return ucfirst($string);
        }
        ?>
    </body>
</html>
