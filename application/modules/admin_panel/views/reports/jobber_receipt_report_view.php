<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobber Receipt Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        @page { 
            size: A4 landscape;
            margin: 0;
        }
        
        body { 
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
        .sheet {
            padding: 10mm 8mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .company-address {
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .filter-section label {
            font-weight: bold;
            margin-right: 10px;
        }
        
        .filter-section input[type="date"] {
            padding: 5px 10px;
            margin-right: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        
        .filter-section button {
            padding: 6px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .filter-section button:hover {
            background-color: #0056b3;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 5px 3px;
            font-weight: bold;
            text-align: center;
        }
        
        td {
            border: 1px solid #000;
            padding: 3px;
        }
        
        td.date {
            text-align: center;
            width: 80px;
        }
        
        td.challan {
            text-align: left;
            width: 100px;
        }
        
        td.jobber-name {
            text-align: left;
            padding-left: 5px;
        }
        
        td.quantity {
            text-align: right;
            padding-right: 5px;
            width: 80px;
        }
        
        .month-header {
            background-color: #d0d0d0;
            font-weight: bold;
            padding: 5px 8px !important;
            text-align: left;
        }
        
        .subtotal-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        
        .grand-total-row {
            font-weight: bold;
            background-color: #d0d0d0;
            font-size: 10px;
        }
        
        @media print {
            body, .sheet { 
                margin: 0;
                box-shadow: none;
            }
            
            .no-print {
                display: none;
            }
            
            .filter-section {
                display: none;
            }
        }
        
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }
        
        .excel-button {
            background-color: #28a745;
            color: white;
        }
        
        .excel-button:hover {
            background-color: #218838;
        }
        
        .print-button {
            background-color: #007bff;
            color: white;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="A4 landscape">
    <div class="action-buttons no-print">
        <button class="btn print-button" onclick="window.print()">Print Report</button>
    </div>
    
    <section class="sheet" style="height: auto">
        <!-- Date Filter Form -->
        <div class="filter-section no-print">
            <form method="POST" action="">
                <label>From Date:</label>
                <input type="date" name="from_date" value="<?php echo $from_date; ?>" required>
                
                <label>To Date:</label>
                <input type="date" name="to_date" value="<?php echo $to_date; ?>" required>
                
                <button type="submit">Filter</button>
                <button class="btn excel-button" onclick="exportToExcel()">Export to Excel</button>
            </form>
        </div>
        
        <div class="header">
            <div class="company-name">SHILPA OVERSEAS PVT. LTD.</div>
            <div class="company-address">KAIKHALI, CHIRIAMORE, P.O.: R GOPALPUR, KOLKATA - 700 136</div>
            <div class="report-title">JOBBER RECEIPT DETAILS</div>
            <div style="font-size: 10px; margin-bottom: 10px;">
                Period: <?php echo date('d-m-Y', strtotime($from_date)); ?> to <?php echo date('d-m-Y', strtotime($to_date)); ?>
            </div>
        </div>
        
        <table id="jobberTable">
            <thead>
                <tr>
                    <th>RECEIPT<br>DATE</th>
                    <th>CHALLAN<br>NUMBER</th>
                    <th>JOBBER'S<br>NAME</th>
                    <th>QNTY<br>(BAG)</th>
                    <th>AMNT<br>(BAG)</th>
                    <th>QNTY<br>(SLG)</th>
                    <th>AMNT<br>(SLG)</th>
                    <th>QNTY<br>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Initialize variables
                $current_month = '';
                $month_bag_qty = 0;
                $month_slg_qty = 0;
                $month_total_qty = 0;
                
                $grand_bag_qty = 0;
                $grand_slg_qty = 0;
                $grand_total_qty = 0;
                
                // Group data by month and department
                $grouped_data = [];
                foreach($jobber_receipts as $receipt) {
                    $month_year = date('F Y', strtotime($receipt->jobber_receipt_challan_date));
                    $date = date('d-m-Y', strtotime($receipt->jobber_receipt_challan_date));
                    $challan = $receipt->jobber_receipt_challan_number;
                    $key = $date . '|' . $challan;
                    
                    if (!isset($grouped_data[$month_year][$key])) {
                        $grouped_data[$month_year][$key] = [
                            'date' => $date,
                            'challan' => $challan,
                            'jobber_name' => $receipt->jobber_name,
                            'bag_qty' => 0,
                            'slg_qty' => 0
                        ];
                    }
                    
                    // Add quantity based on department
                    if ($receipt->user_dept == 1) { // BAG
                        $grouped_data[$month_year][$key]['bag_qty'] += $receipt->jobber_receive_quantity;
                    } elseif ($receipt->user_dept == 2) { // SLG
                        $grouped_data[$month_year][$key]['slg_qty'] += $receipt->jobber_receive_quantity;
                    }
                }
                
                // Display grouped data
                foreach($grouped_data as $month_year => $receipts):
                    $month_bag_qty = 0;
                    $month_slg_qty = 0;
                    $month_total_qty = 0;
                ?>
                    <tr>
                        <td colspan="8" class="month-header"><?php echo strtoupper($month_year); ?></td>
                    </tr>
                    <?php foreach($receipts as $receipt_data): 
                        $total_qty = $receipt_data['bag_qty'] + $receipt_data['slg_qty'];
                        
                        $month_bag_qty += $receipt_data['bag_qty'];
                        $month_slg_qty += $receipt_data['slg_qty'];
                        $month_total_qty += $total_qty;
                    ?>
                    <tr>
                        <td class="date"><?php echo $receipt_data['date']; ?></td>
                        <td class="challan"><?php echo $receipt_data['challan']; ?></td>
                        <td class="jobber-name"><?php echo strtoupper($receipt_data['jobber_name']); ?></td>
                        <td class="quantity"><?php echo $receipt_data['bag_qty'] > 0 ? number_format($receipt_data['bag_qty'], 0) : '0'; ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo $receipt_data['slg_qty'] > 0 ? number_format($receipt_data['slg_qty'], 0) : '0'; ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo number_format($total_qty, 0); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Month Subtotal -->
                    <tr class="subtotal-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">Total for <?php echo $month_year; ?></td>
                        <td class="quantity"><?php echo number_format($month_bag_qty, 0); ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo number_format($month_slg_qty, 0); ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo number_format($month_total_qty, 0); ?></td>
                    </tr>
                    
                    <?php 
                    $grand_bag_qty += $month_bag_qty;
                    $grand_slg_qty += $month_slg_qty;
                    $grand_total_qty += $month_total_qty;
                    ?>
                <?php endforeach; ?>
                
                <?php if(empty($grouped_data)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">No data found for the selected date range.</td>
                    </tr>
                <?php else: ?>
                    <!-- Grand Total -->
                    <tr class="grand-total-row">
                        <td colspan="3" style="text-align: right; padding-right: 10px;">GRAND TOTAL</td>
                        <td class="quantity"><?php echo number_format($grand_bag_qty, 0); ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo number_format($grand_slg_qty, 0); ?></td>
                        <td class="quantity">0.00</td>
                        <td class="quantity"><?php echo number_format($grand_total_qty, 0); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    
    <section class="sheet" style="height: auto">
        <?php if(isset($jobber_receipts) && !empty($grouped_data)): ?>
            <!-- Summary Page -->
            <div style="page-break-before: always; margin-top: 50px;" id="summary-page">
                <button class="btn excel-button" onclick="exportSummaryToExcel()">Export Summary to Excel</button>
                <div style="text-align: center; margin-bottom: 15px;">
                    <h4><strong>SHILPA OVERSEAS PVT. LTD.</strong></h4>
                    <p style="font-size: 11px;">KAIKHALI, CHIRIAMORE, P.O.: R GOPALPUR, KOLKATA - 700 136</p>
                    <h5><strong>JOBBER RECEIPT SUMMARY</strong></h5>
                    <p style="font-size: 11px;">
                        Period: <?php echo date('d-m-Y', strtotime($from_date)); ?> to <?php echo date('d-m-Y', strtotime($to_date)); ?>
                    </p>
                </div>

                <table class="jobber-report-table" id="jobberSummaryTable">
                    <thead>
                        <tr>
                            <th>MONTH</th>
                            <th>QNTY<br>(BAG)</th>
                            <th>AMNT<br>(BAG)</th>
                            <th>QNTY<br>(SLG)</th>
                            <th>AMNT<br>(SLG)</th>
                            <th>QNTY<br>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Reset grand totals for summary
                        $summary_bag_qty = 0;
                        $summary_slg_qty = 0;
                        $summary_total_qty = 0;
                        
                        // Display month-wise summary
                        foreach($grouped_data as $month_year => $receipts):
                            $month_bag = 0;
                            $month_slg = 0;
                            
                            foreach($receipts as $receipt_data) {
                                $month_bag += $receipt_data['bag_qty'];
                                $month_slg += $receipt_data['slg_qty'];
                            }
                            
                            $month_total = $month_bag + $month_slg;
                            
                            $summary_bag_qty += $month_bag;
                            $summary_slg_qty += $month_slg;
                            $summary_total_qty += $month_total;
                        ?>
                        <tr>
                            <td style="padding-left: 8px;"><strong><?php echo strtoupper($month_year); ?></strong></td>
                            <td class="quantity"><?php echo number_format($month_bag, 0); ?></td>
                            <td class="quantity">0.00</td>
                            <td class="quantity"><?php echo number_format($month_slg, 0); ?></td>
                            <td class="quantity">0.00</td>
                            <td class="quantity"><?php echo number_format($month_total, 0); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Grand Total -->
                        <tr class="grand-total-row">
                            <td style="text-align: right; padding-right: 10px;"><strong>GRAND TOTAL</strong></td>
                            <td class="quantity"><?php echo number_format($summary_bag_qty, 0); ?></td>
                            <td class="quantity">0.00</td>
                            <td class="quantity"><?php echo number_format($summary_slg_qty, 0); ?></td>
                            <td class="quantity">0.00</td>
                            <td class="quantity"><?php echo number_format($summary_total_qty, 0); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
    
    <script>
        function exportToExcel() {
            var wb = XLSX.utils.book_new();
            var ws_data = [];
            
            // Add header rows
            ws_data.push(['SHILPA OVERSEAS PVT. LTD.']);
            ws_data.push(['KAIKHALI, CHIRIAMORE, P.O.: R GOPALPUR, KOLKATA - 700 136']);
            ws_data.push(['JOBBER RECEIPT DETAILS']);
            ws_data.push(['Period: <?php echo date('d-m-Y', strtotime($from_date)); ?> to <?php echo date('d-m-Y', strtotime($to_date)); ?>']);
            ws_data.push([]);
            ws_data.push(['RECEIPT DATE', 'CHALLAN NUMBER', 'JOBBER\'S NAME', 'QNTY (BAG)', 'AMNT (BAG)', 'QNTY (SLG)', 'AMNT (SLG)', 'QNTY TOTAL']);
            
            // Get table data
            var table = document.getElementById('jobberTable');
            var tbody = table.querySelector('tbody');
            var rows = tbody.querySelectorAll('tr');
            
            rows.forEach(function(row) {
                var cols = row.querySelectorAll('td');
                var rowData = [];
                
                if (cols.length === 1 && cols[0].colSpan === 8) {
                    // Month header
                    rowData = [cols[0].textContent.trim()];
                } else if (cols.length > 1) {
                    cols.forEach(function(col) {
                        rowData.push(col.textContent.trim());
                    });
                }
                
                if (rowData.length > 0) {
                    ws_data.push(rowData);
                }
            });
            
            var ws = XLSX.utils.aoa_to_sheet(ws_data);
            
            // Set column widths
            ws['!cols'] = [
                {wch: 12}, // Date
                {wch: 20}, // Challan
                {wch: 30}, // Jobber Name
                {wch: 12}, // BAG Qty
                {wch: 12}, // BAG Amt
                {wch: 12}, // SLG Qty
                {wch: 12}, // SLG Amt
                {wch: 12}  // Total
            ];
            
            // Merge header cells
            ws['!merges'] = [
                {s: {r: 0, c: 0}, e: {r: 0, c: 7}},
                {s: {r: 1, c: 0}, e: {r: 1, c: 7}},
                {s: {r: 2, c: 0}, e: {r: 2, c: 7}},
                {s: {r: 3, c: 0}, e: {r: 3, c: 7}}
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, 'Jobber Receipt');
            
            var filename = 'Jobber_Receipt_Report_<?php echo date('Y-m-d', strtotime($from_date)); ?>_to_<?php echo date('Y-m-d', strtotime($to_date)); ?>.xlsx';
            XLSX.writeFile(wb, filename);
        }
        
        function exportSummaryToExcel() {
            var wb = XLSX.utils.book_new();
            var ws_data = [];
            
            // Add header rows
            ws_data.push(['SHILPA OVERSEAS PVT. LTD.']);
            ws_data.push(['KAIKHALI, CHIRIAMORE, P.O.: R GOPALPUR, KOLKATA - 700 136']);
            ws_data.push(['JOBBER RECEIPT SUMMARY']);
            ws_data.push(['Period: <?php echo date('d-m-Y', strtotime($from_date)); ?> to <?php echo date('d-m-Y', strtotime($to_date)); ?>']);
            ws_data.push([]);
            ws_data.push(['MONTH', 'QNTY (BAG)', 'AMNT (BAG)', 'QNTY (SLG)', 'AMNT (SLG)', 'QNTY TOTAL']);
            
            // Get table data
            var table = document.getElementById('jobberSummaryTable');
            var tbody = table.querySelector('tbody');
            var rows = tbody.querySelectorAll('tr');
            
            rows.forEach(function(row) {
                var cols = row.querySelectorAll('td');
                var rowData = [];
                
                cols.forEach(function(col) {
                    rowData.push(col.textContent.trim());
                });
                
                if (rowData.length > 0) {
                    ws_data.push(rowData);
                }
            });
            
            var ws = XLSX.utils.aoa_to_sheet(ws_data);
            
            // Set column widths
            ws['!cols'] = [
                {wch: 20}, // Month
                {wch: 12}, // BAG Qty
                {wch: 12}, // BAG Amt
                {wch: 12}, // SLG Qty
                {wch: 12}, // SLG Amt
                {wch: 12}  // Total
            ];
            
            // Merge header cells
            ws['!merges'] = [
                {s: {r: 0, c: 0}, e: {r: 0, c: 5}},
                {s: {r: 1, c: 0}, e: {r: 1, c: 5}},
                {s: {r: 2, c: 0}, e: {r: 2, c: 5}},
                {s: {r: 3, c: 0}, e: {r: 3, c: 5}}
            ];
            
            XLSX.utils.book_append_sheet(wb, ws, 'Summary');
            
            var filename = 'Jobber_Receipt_Summary_<?php echo date('Y-m-d', strtotime($from_date)); ?>_to_<?php echo date('Y-m-d', strtotime($to_date)); ?>.xlsx';
            XLSX.writeFile(wb, filename);
        }
        
    </script>
</body>
</html>