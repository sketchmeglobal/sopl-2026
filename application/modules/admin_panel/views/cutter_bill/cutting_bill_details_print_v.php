<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cutting Bill Details</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <link href="https://fonts.googleapis.com/css?family=Chivo|Signika" rel="stylesheet">

    <link href="http://shilpaoverseas.com/new/assets/img/favicon.ico" rel="shortcut icon" type="image/png">
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
    body {
        /*font-family: 'Chivo', sans-serif;*/
        font-family: Calibri;
    }

    p {
        margin: 0 0 5px;
    }

    table {
        border: 1px solid #777;
    }

    .table {
        margin-bottom: 3px;
    }

    .text-right {
        text-align: right !important;
    }

    .head_font {
        /*font-family: 'Signika', sans-serif;*/
        font-family: Calibri;
    }

    .container {
        width: 100%
    }

    .border_all {
        border: 1px solid #000;
    }

    .mar_0 {
        margin: 0
    }

    .pad_0 {
        padding: 0
    }

    .mar_bot_3 {
        margin-bottom: 3px
    }


    .no-print {
        display: none;
    }


    .header_left,
    .header_right {
        height: 107px;
    }

    .width-100 {
        width: 100%
    }

    .height_60 {
        height: 60px
    }

    .height_42 {
        height: 42px
    }

    .height_135 {
        height: 150px
    }

    .height_90 {
        height: 90px
    }

    .height_100 {
        height: 100px
    }

    .height_41 {
        height: 41px
    }

    .height_23 {
        height: 23px
    }

    .height_63 {
        height: 63px
    }

    .height_21 {
        height: 21px
    }

    .height_100 {
        height: 120px
    }

    .table-bordered,
    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        text-align: center;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px;
        text-align: left;
        font-size: 12px
    }

    .border-bottom {
        border-bottom: 1px solid #000
    }

    body.A4 .sheet {
        width: 210mm;
        height: auto;
    }

    .padding-10mm {
        padding: 1mm;
    }

    @page {
        size: A4
    }

    @media print {

        .table-bordered,
        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid #000;
            text-align: center;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 5px;
            text-align: left;
            font-size: 11px
        }

        .col-sm-6 {
            width: 50% !important;
            float: left;
        }

        .col-sm-5 {
            width: 41.66666667%;
            float: left;
        }

        .col-sm-7 {
            width: 58.33333333%;
            float: left;
        }

        .col-sm-8 {
            width: 66.66666667%;
            float: left;
        }

        .col-sm-4 {
            width: 33.33333333%;
            float: left;
        }

        .border-bottom {
            border-bottom: 1px solid #000
        }

        .pad_0 {
            padding: 0
        }
    }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4" id="page-content">
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->


    <!-- <form method="post" class="no-print" style="width: 200px;background:#f5f5f5;padding:10px;position:absolute;right:0;box-shadow: 0px 0px 2px 1px #c1c1c1;">
            <label>First page rows: <input required type="number" min="1" name="first_page_row" class="form-control" value="20"/></label>
            <label>Other page rows: <input required type="number" min="5" name="other_page_row" class="form-control" value="6"/></label>
            <label>Blank rows: <input required type="number" min="0" name="blank_row" class="form-control" value="6"/></label>
            <input type="hidden" name="module_id" value="8"/>
            <input type="hidden" name="user_id" value="1"/>
            <input type="submit" name="page_setup_submit" class="btn btn-warning btn-sm"/>
        </form> -->


    <section class="sheet padding-10mm">
        <div>
            <header class="pull-right">
                <!--<small>Page No. 1</small>-->
            </header>
            <div class="clearfix"></div>
            <div class="container">
                <div class="row border_all text-center text-uppercase mar_bot_3">
                    <h4 class="mar_0 head_font">Cutting Bill Details</h4>
                </div>
                <div class="row mar_bot_3">
                    <div class="col-sm-8 border_all header_left">
                        <h5 class="mar_0 text-uppercase">
                            <strong>
                            <?php
                                $name = end($data);
                                echo $name['name'];
                            ?>
                            </strong>
                        </h5>
                        <h6 class="mar_0">
                        <?php
                        $address = end($data);
                        echo $address['address'];
                        ?>
                        </h6>
                        <hr class="mar_0" style="margin: 7px 0">
                        <p class="mar_0">Bill To,</p>
                        <p class="mar_0"><b><?=COMPANY_NAME?></b></p>
                        <p class="mar_0"><?=COMPANY_ADDRESS?></p>
                    </div>
                    <div class="col-sm-4 header_right border_all">
                        <div class="row mar_bot_3">
                            <div class="col-sm-12">
                                <div class="">
                                    <!--<h5 class=""><b>Rcpt. No.</b>: < ?php-->
                                    <!--$last = end($data);-->
                                    <!--echo $last['cutting_receipt_number'];-->
                                    <!--? >-->
                                    <!--</h5>-->
                                    <h5 class=""><b>Cutter Bill</b>: <?=$data[0]['cutter_bill_name']?></h5>
                                    <h5 class=""><b>Cutter Date</b>: <?=$data[0]['cutter_bill_date']?></h5>
                                    <h5 class=""><b>Cutter Type</b>: 
                                        <?= ($data[0]['cutter_bill_type'] == 'Type A') ? 'Type A (Non-leather)' : 'Type B (Leather)' ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
//   echo"<pre>";
//         print_r($data);
//         echo"</pre>";
?>
                <!--table data-->
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover width-100 table2excel">
                            <thead>
                                <tr>
                                    <th>Cutting Rcpt. Challan No.</th>
                                    <th>Order No.</th>
                                    <th>Article</th>
                                    <th>Colour</th>
                                    <th class="text-right">Qnty</th>
                                    <th>Part</th>
                                    <th>Rate</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
    $totalQuantity = 0;
    $totalAmount = 0;
    ?>

                                <?php if (!empty($data)) : ?>
                                <?php foreach ($data as $row) : ?>
                                <?php
                $rowQty = $row['original_quantity'] + $row['extra_quantity'];
                $totalQuantity += $rowQty;
                $totalAmount += $row['total_amount'];
            ?>
                                <tr>
                                    <td><?= $row['cutting_receipt_number'] ?></td>
                                    <td><?= $row['co_no'] ?></td>
                                    <td><?= $row['art_no'] ?></td>
                                    <td><?= $row['color'] ?></td>
                                    <td class="text-right"><?= $rowQty ?></td>
                                    <td><?= $row['parts'] ?></td>
                                    <td><?= $row['rate'] ?></td>
                                    <td class="text-right"><?= $row['total_amount'] ?></td>
                                </tr>
                                <?php endforeach; ?>

                                <tr style="font-weight: bold; background-color: #f9f9f9;">
                                    <td colspan="4" class="text-left">Grand Total</td>
                                    <td class="text-right"><?= $totalQuantity ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><?= $totalAmount ?></td>
                                </tr>
                                <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>


                        </table>
                    </div>
                </div>

            </div>
    </section>


</body>

</html>