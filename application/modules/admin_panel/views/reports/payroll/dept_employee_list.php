<?php 
// Uncomment this line if you want to see the data structure
// echo '<pre>', print_r($dept_employee_list), '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department-wise Employee List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <!-- SheetJS library for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        @page { 
            size: A4;
            margin: 0;
        }
        
        body { 
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
        .sheet {
            padding: 15mm 10mm;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-address {
            font-size: 11px;
            margin-bottom: 15px;
        }
        
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }
        
        td {
            border: 1px solid #000;
            padding: 4px;
        }
        
        td.sl-no {
            text-align: center;
            width: 40px;
        }
        
        td.name {
            padding-left: 8px;
        }
        
        td.designation {
            padding-left: 8px;
        }
        
        td.department {
            text-align: center;
            width: 80px;
        }
        
        td.gender {
            text-align: center;
            width: 45px;
        }
        
        .section-header {
            background-color: #e0e0e0;
            font-weight: bold;
            padding: 6px 8px !important;
            text-align: left;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .total-row td {
            text-align: center;
            padding: 6px;
        }
        
        .grand-total {
            font-size: 11px;
        }
        
        @media print {
            body, .sheet { 
                margin: 0;
                box-shadow: none;
            }
            
            .no-print {
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
        
        .print-button {
            background-color: #007bff;
            color: white;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        .excel-button {
            background-color: #28a745;
            color: white;
        }
        
        .excel-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body class="A4">
    <div class="action-buttons no-print">
        <button class="btn excel-button" onclick="exportToExcel()">Export to Excel</button>
        <button class="btn print-button" onclick="window.print()">Print Report</button>
    </div>
    
    <section class="sheet" style="height:auto">
        <div class="header">
            <div class="company-name">SHILPA OVERSEAS PVT LTD</div>
            <div class="company-address">KAIKHALI CHIRIAMORE, P.O - R GOPALPUR, KOLKATA - 700 136</div>
            <div class="report-title">DEPARTMENT-WISE EMPLOYEE LIST</div>
        </div>
        
        <table id="employeeTable">
            <thead>
                <tr>
                    <th>SL NO</th>
                    <th>NAME</th>
                    <th>DESIGNATION</th>
                    <th>DEPARTMENT</th>
                    <th>MALE</th>
                    <th>FEMALE</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Initialize counters
                $sl_no = 1;
                $male_count = 0;
                $female_count = 0;
                
                // Group employees by department
                $grouped_employees = [];
                $no_department_employees = [];
                
                foreach($dept_employee_list as $emp) {
                    $dept = trim($emp->department);
                    
                    if (empty($dept)) {
                        // Store employees with no department separately
                        $no_department_employees[] = $emp;
                    } else {
                        // Group by department
                        if (!isset($grouped_employees[$dept])) {
                            $grouped_employees[$dept] = [];
                        }
                        $grouped_employees[$dept][] = $emp;
                    }
                }
                
                // Sort departments alphabetically
                ksort($grouped_employees);
                
                // Display employees with departments
                foreach($grouped_employees as $dept_name => $employees):
                ?>
                    <tr>
                        <td colspan="6" class="section-header"><?php echo strtoupper($dept_name); ?></td>
                    </tr>
                    <?php 
                    foreach($employees as $emp):
                        $is_male = ($emp->gender == 'Male') ? '✓' : '';
                        $is_female = ($emp->gender == 'Female') ? '✓' : '';
                        
                        if($emp->gender == 'Male') $male_count++;
                        if($emp->gender == 'Female') $female_count++;
                    ?>
                    <tr>
                        <td class="sl-no"><?php echo $sl_no++; ?></td>
                        <td class="name"><?php echo strtoupper($emp->name); ?></td>
                        <td class="designation"><?php echo strtoupper($emp->designation_audit ?: ''); ?></td>
                        <td class="department"><?php echo strtoupper($dept_name); ?></td>
                        <td class="gender"><?php echo $is_male; ?></td>
                        <td class="gender"><?php echo $is_female; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                
                <?php 
                // Display employees without department at the end
                if (!empty($no_department_employees)):
                ?>
                    <tr>
                        <td colspan="6" class="section-header">NO DEPARTMENT ASSIGNED</td>
                    </tr>
                    <?php 
                    foreach($no_department_employees as $emp):
                        $is_male = ($emp->gender == 'Male') ? '✓' : '';
                        $is_female = ($emp->gender == 'Female') ? '✓' : '';
                        
                        if($emp->gender == 'Male') $male_count++;
                        if($emp->gender == 'Female') $female_count++;
                    ?>
                    <tr>
                        <td class="sl-no"><?php echo $sl_no++; ?></td>
                        <td class="name"><?php echo strtoupper($emp->name); ?></td>
                        <td class="designation"><?php echo strtoupper($emp->designation_audit ?: ''); ?></td>
                        <td class="department"></td>
                        <td class="gender"><?php echo $is_male; ?></td>
                        <td class="gender"><?php echo $is_female; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- Empty row before total -->
                <tr>
                    <td colspan="6" style="border: none; height: 10px;"></td>
                </tr>
                
                <!-- Total row -->
                <tr class="total-row">
                    <td colspan="3"></td>
                    <td>TOTAL</td>
                    <td><?php echo $male_count; ?></td>
                    <td><?php echo $female_count; ?></td>
                </tr>
                
                <!-- Grand total row -->
                <tr class="total-row grand-total">
                    <td colspan="3"></td>
                    <td></td>
                    <td colspan="2"><?php echo $male_count + $female_count; ?></td>
                </tr>
            </tbody>
        </table>
    </section>
    
    <script>
        function exportToExcel() {
            // Create a new workbook
            var wb = XLSX.utils.book_new();
            
            // Create worksheet data array
            var ws_data = [];
            
            // Add header rows
            ws_data.push(['SHILPA OVERSEAS PVT LTD']);
            ws_data.push(['KAIKHALI CHIRIAMORE,P.O-GOPALPUR,24 PGS(N) KOLKATA-700136']);
            ws_data.push(['DEPARTMENT-WISE EMPLOYEE LIST']);
            ws_data.push(['SL NO', 'NAME', 'DESIGNATION', 'DEPARTMENT', 'MALE', 'FEMALE']);
            
            // Get table rows
            var table = document.getElementById('employeeTable');
            var tbody = table.querySelector('tbody');
            var rows = tbody.querySelectorAll('tr');
            
            rows.forEach(function(row) {
                var cols = row.querySelectorAll('td');
                var rowData = [];
                
                // Check if it's a section header (colspan=6)
                if (cols.length === 1 && cols[0].colSpan === 6) {
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
            
            // Create worksheet from data
            var ws = XLSX.utils.aoa_to_sheet(ws_data);
            
            // Set column widths
            ws['!cols'] = [
                {wch: 8},  // SL NO
                {wch: 30}, // NAME
                {wch: 30}, // DESIGNATION
                {wch: 25}, // DEPARTMENT
                {wch: 10}, // MALE
                {wch: 10}  // FEMALE
            ];
            
            // Merge cells for header rows
            ws['!merges'] = [
                {s: {r: 0, c: 0}, e: {r: 0, c: 5}}, // Company name
                {s: {r: 1, c: 0}, e: {r: 1, c: 5}}, // Address
                {s: {r: 2, c: 0}, e: {r: 2, c: 5}}  // Report title
            ];
            
            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Department-wise List');
            
            // Generate filename with current date
            var today = new Date();
            var filename = 'Department_Employee_List_' + today.getFullYear() + '-' + 
                          (today.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                          today.getDate().toString().padStart(2, '0') + '.xlsx';
            
            // Save file
            XLSX.writeFile(wb, filename);
        }
    </script>
</body>
</html>