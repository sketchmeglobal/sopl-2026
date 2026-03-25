<?php
// Load TCPDF library
require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

// Process order numbers for display
$arr_order_no = array();
foreach ($print_packing_list as $ppli) {
    if (!in_array($ppli->buyer_reference_no, $arr_order_no)) {
        array_push($arr_order_no, $ppli->buyer_reference_no);
    }
}

// Process declarations if available and strip HTML tags
$all_declarations = array();
if(isset($fetch_individual_declaration_details)) {
    foreach($fetch_individual_declaration_details as $pol){
        $clean_declaration = strip_tags($pol->DECLARATION_DESCRIPTION);
        $clean_declaration = html_entity_decode($clean_declaration, ENT_QUOTES, 'UTF-8');
        array_push($all_declarations, $clean_declaration);
    }
}

// Create custom TCPDF class
class InvoicePDF extends TCPDF {
    
    public $invoice_data;
    public $arr_order_no;
    
    // Disable default header
    public function Header() {
        // We'll add headers manually in the main code
    }
    
    // Disable default footer - we'll add footer content manually
    public function Footer() {
        // Empty - we'll handle footer manually after content
    }
}

// Create new PDF document
$pdf = new InvoicePDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Pass data to PDF class
$pdf->invoice_data = $print_packing_list;
$pdf->arr_order_no = $arr_order_no;

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(COMPANY_NAME);
$pdf->SetTitle('OFFICE INVOICE');

// Remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins - smaller bottom margin since we're not using fixed footer
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 15); // Smaller bottom margin

// Add first page
$pdf->AddPage();

// Function to add Bank Details and Signature section
function addBankDetailsAndSignature($pdf, $print_packing_list) {
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $footer_height = 45;
    
    // Check if we have enough space on current page
    $page_height = $pdf->getPageHeight();
    $available_space = $page_height - $y - 15; // 15mm bottom margin
    
    if($available_space < $footer_height + 10) {
        // Not enough space, add new page
        $pdf->AddPage();
        // Add compact header on new page
        addCompactHeader($pdf, $print_packing_list, $pdf->arr_order_no);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
    }
    
    // Add some space before the section
    $pdf->Ln(5);
    $y = $pdf->GetY();
    
    // Bank details box
    $pdf->Rect(10, $y, 95, $footer_height);
    $pdf->SetXY(12, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Bank Details:', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX(12);
    
    // Bank details text
    $bank_text = "BANK NAME : HDFC BANK LIMITED, BANK ADDRESS : 9B,\n";
    $bank_text .= "HINDUSTHAN ROAD, KOLKATA-700029 WEST BENGAL, INDIA\n";
    $bank_text .= "ACCOUNT NO. : 50200041679309 AUTHORISED DEALER CODE :\n";
    $bank_text .= "0512619 1000009 , SWIFT CODE : HDFCINBB";
    
    $pdf->MultiCell(90, 3.5, $bank_text, 0, 'L');
    
    // Signature box
    $pdf->Rect(105, $y, 95, $footer_height);
    $pdf->SetXY(107, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Signature & Date', 0, 1);
    $pdf->SetX(107);
    $pdf->Cell(0, 4, 'Shilpa overseas (Pvt.) Ltd', 0, 1);
    
    // Add signature image if exists
    $signature_path = FCPATH . 'assets/img/shilpa1.png';
    if (file_exists($signature_path)) {
        $pdf->Image($signature_path, 107, $y + 12, 0, 18, 'PNG');
    }
    
    $pdf->SetXY(107, $y + 30);
    $pdf->Cell(0, 4, '________________________', 0, 1);
    $pdf->SetX(107);
    $pdf->Cell(0, 4, 'Authorised Signatory', 0, 1);
    $pdf->SetX(107);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 4, 'Date: ' . $print_packing_list[0]->office_invoice_date, 0, 1);
    
    // Move Y position after the section
    $pdf->SetY($y + $footer_height);
}

// Function to add "CONTINUED" text
function addContinuedText($pdf) {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, '--- CONTINUED ON NEXT PAGE ---', 0, 0, 'C');
}

// Function to add full header for first page with better spacing
function addFullHeader($pdf, $print_packing_list, $arr_order_no) {
    // Add page number at top right
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(160, 5);
    $pdf->Cell(40, 5, 'Page No. ' . $pdf->getPage(), 0, 0, 'R');
    
    // Start content area with more space
    $pdf->SetY(12); // More space from top
    $pdf->SetFont('helvetica', 'B', 14);
    
    // Title box with padding
    $pdf->Cell(190, 10, 'INVOICE', 1, 1, 'C');
    $pdf->Ln(3); // Space after title
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // Exporter box with better height
    $pdf->Rect($x, $y, 95, 48);
    $pdf->SetXY($x + 2, $y + 3);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, 'Exporter', 0, 1);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, COMPANY_NAME, 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(90, 4, 'Address: ' . HEADER_ADDRESS . "\n" . 
                            'Factory Address: ' . HEADER_FACTORY_ADDRESS . "\n" .
                            'Contact: ' . HEADER_TEL . "\n" .
                            'Email: ' . HEADER_EMAIL . "\n" .
                            'CIN: ' . HEADER_CIN, 0, 'L');
    
    // Invoice No box
    $pdf->Rect($x + 95, $y, 47.5, 20);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Invoice No. & Date', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetX($x + 97);
    $pdf->Ln(1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->office_invoice_number, 0, 1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->office_invoice_date, 0, 1);
    
    // Export Ref box
    $pdf->Rect($x + 142.5, $y, 47.5, 20);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Export Ref.', 0, 1);
    $pdf->SetX($x + 144.5);
    $pdf->Ln(1);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, 'GSTIN: 19AAECS6338L1ZT', 0, 1);
    
    // Buyer Order No box
    $pdf->Rect($x + 95, $y + 20, 95, 18);
    $pdf->SetXY($x + 97, $y + 22);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Buyer Order No. & Date:', 0, 1);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 4, implode(', ', $arr_order_no), 0, 'L');
    
    // Other references box
    $pdf->Rect($x + 95, $y + 38, 95, 10);
    $pdf->SetXY($x + 97, $y + 40);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Other Reference(s): PAN : AAECS6338L', 0, 1);
    
    $pdf->Ln(5);
    
    // SECOND ROW - Consignee and Country info
    $y = $pdf->GetY();
    
    // Consignee box - MODIFIED to print account name on same line
    $pdf->Rect($x, $y, 95, 36);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Write(0, 'Consignee: ');
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Write(0, $print_packing_list[0]->acc_name);
    $pdf->Ln(5); // Move to next line
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(90, 3.5, 
        'Billing Address: ' . $print_packing_list[0]->billing_address . "\n" .
        'Email: ' . $print_packing_list[0]->email_id . "\n" .
        'Delivery Address: ' . $print_packing_list[0]->delivery_address, 0, 'L');
    
    // Country boxes
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Rect($x + 95, $y, 47.5, 9);
    $pdf->SetXY($x + 97, $y + 2.5);
    $pdf->Cell(0, 4, 'Country of Origin of Goods', 0, 1);
    
    $pdf->Rect($x + 142.5, $y, 47.5, 9);
    $pdf->SetXY($x + 144.5, $y + 2.5);
    $pdf->Cell(0, 4, 'West Bengal / India', 0, 1);
    
    $pdf->Rect($x + 95, $y + 9, 47.5, 9);
    $pdf->SetXY($x + 97, $y + 11.5);
    $pdf->Cell(0, 4, 'Country of final delivery', 0, 1);
    
    $pdf->Rect($x + 142.5, $y + 9, 47.5, 9);
    $pdf->SetXY($x + 144.5, $y + 11.5);
    $pdf->Cell(0, 4, ucfirst(strtolower($print_packing_list[0]->country)), 0, 1);
    
    // Buyer box
    $pdf->Rect($x + 95, $y + 18, 95, 18);
    $pdf->SetXY($x + 97, $y + 19);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Buyer (if other than consignee)', 0, 1);
    if(isset($print_packing_list[0]->acc_name2) && !empty($print_packing_list[0]->acc_name2)) {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetX($x + 97);
        $pdf->Cell(0, 4, $print_packing_list[0]->acc_name2, 0, 1);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX($x + 97);
        $pdf->MultiCell(90, 3.5, $print_packing_list[0]->acc_address2, 0, 'L');
    }
    
    $pdf->Ln(5);
    
    // THIRD ROW - Other info and shipping
    $y = $pdf->GetY();
    
    // Calculate height needed for Other Information text
    $other_info_text = trim($print_packing_list[0]->other_information);
    // Clean the text - remove any HTML tags or special formatting
    $other_info_text = strip_tags($other_info_text);
    $other_info_text = str_replace(array("\r\n", "\r", "\n"), " ", $other_info_text);
    $other_info_height = 8; // Default minimum height
    
    if(!empty($other_info_text)) {
        // Calculate lines needed for the text (width approx 90mm for content)
        $lines_needed = $pdf->getNumLines($other_info_text, 90);
        $other_info_height = max(8, $lines_needed * 4 + 4); // Adjust height based on content
    }
    
    // Other Information box with dynamic height
    $pdf->Rect($x, $y, 95, $other_info_height);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Other Information:', 0, 1);
    
    if(!empty($other_info_text)) {
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX($x + 2);
        // Reset any text formatting and use clean MultiCell
        $pdf->SetTextColor(0, 0, 0); // Ensure black text
        $pdf->MultiCell(90, 3.5, $other_info_text, 0, 'L', false, 1);
    }
    
    // Pre-Carriage box - align with Other Information height
    $pre_carriage = '';
    if($print_packing_list[0]->pre_carriage_by == 1) $pre_carriage = 'By Air';
    else if($print_packing_list[0]->pre_carriage_by == 2) $pre_carriage = 'By Ship';
    else if($print_packing_list[0]->pre_carriage_by == 3) $pre_carriage = 'By Road';
    
    $pdf->Rect($x + 95, $y, 47.5, $other_info_height);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Pre-Carriage By', 0, 1);
    if(!empty($pre_carriage)) {
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX($x + 97);
        $pdf->Cell(0, 4, $pre_carriage, 0, 1);
    }
    
    // Port of Loading box - align with Other Information height
    $port_loading = !empty($print_packing_list[0]->port_of_loading) ? $print_packing_list[0]->port_of_loading : 'Kolkata';
    $pdf->Rect($x + 142.5, $y, 47.5, $other_info_height);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Port of Loading', 0, 1);
    if(!empty($port_loading)) {
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX($x + 144.5);
        $pdf->Cell(0, 4, $port_loading, 0, 1);
    }
    
    $pdf->SetY($y + $other_info_height);
    $pdf->Ln(5);
    
    // FOURTH ROW - Port and Description
    $y = $pdf->GetY();
    
    // Port of Discharge
    $pdf->Rect($x, $y, 47.5, 15);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Port of Discharge', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 4, $print_packing_list[0]->port_of_discharge, 0, 1);
    
    // Final Destination
    $pdf->Rect($x + 47.5, $y, 47.5, 15);
    $pdf->SetXY($x + 49.5, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Final Destination', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 49.5);
    $pdf->Cell(0, 4, $print_packing_list[0]->country, 0, 1);
    
    // Description of Goods
    $pdf->Rect($x + 95, $y, 95, 15);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Description of Goods', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 4, $print_packing_list[0]->description_of_goods, 0, 'L');
    
    $pdf->Ln(9);
    
    // FIFTH ROW - Mark, Package and Terms
    $y = $pdf->GetY();
    
    // Mark & Container
    $pdf->Rect($x, $y, 47.5, 32);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Mark & Container', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(43, 3.5, $print_packing_list[0]->mark_container, 0, 'L');
    
    // No. & Kind of Pkgs
    $pdf->Rect($x + 47.5, $y, 47.5, 32);
    $pdf->SetXY($x + 49.5, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'No. & Kind of Pkgs', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 49.5);
    $pdf->MultiCell(43, 3.5, $print_packing_list[0]->no_of_kind_of_package, 0, 'L');
    
    // Terms of Delivery
    $pdf->Rect($x + 95, $y, 95, 21);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Terms of Delivery & Payment', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 3.5, $print_packing_list[0]->terms_of_delivery_payment, 0, 'L');
    
    // Notify
    $pdf->Rect($x + 95, $y + 21, 95, 11);
    $pdf->SetXY($x + 97, $y + 23);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Write(0, 'Notify: ');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Write(0, $print_packing_list[0]->notify);
    
    $pdf->Ln(14);
}

// Function to add header for continuation pages
function addCompactHeader($pdf, $print_packing_list, $arr_order_no) {
    // Add page number at top right
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(160, 5);
    $pdf->Cell(40, 5, 'Page No. ' . $pdf->getPage(), 0, 0, 'R');
    
    // Start content area with more space
    $pdf->SetY(12);
    $pdf->SetFont('helvetica', 'B', 14);
    
    // Title box
    $pdf->Cell(190, 10, 'INVOICE', 1, 1, 'C');
    $pdf->Ln(3);
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // Exporter box
    $pdf->Rect($x, $y, 95, 48);
    $pdf->SetXY($x + 2, $y + 3);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, 'Exporter', 0, 1);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, COMPANY_NAME, 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(90, 4, 'Address: ' . HEADER_ADDRESS . "\n" . 
                            'Factory Address: ' . HEADER_FACTORY_ADDRESS . "\n" .
                            'Contact: ' . HEADER_TEL . "\n" .
                            'Email: ' . HEADER_EMAIL . "\n" .
                            'CIN: ' . HEADER_CIN, 0, 'L');
    
    // Invoice No box
    $pdf->Rect($x + 95, $y, 47.5, 20);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Invoice No. & Date', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetX($x + 97);
    $pdf->Ln(1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->office_invoice_number, 0, 1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->office_invoice_date, 0, 1);
    
    // Export Ref box
    $pdf->Rect($x + 142.5, $y, 47.5, 20);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Export Ref.', 0, 1);
    $pdf->SetX($x + 144.5);
    $pdf->Ln(1);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, 'GSTIN: 19AAECS6338L1ZT', 0, 1);
    
    // Buyer Order No box
    $pdf->Rect($x + 95, $y + 20, 95, 18);
    $pdf->SetXY($x + 97, $y + 22);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Buyer Order No. & Date:', 0, 1);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 4, implode(', ', $arr_order_no), 0, 'L');
    
    // Other references box
    $pdf->Rect($x + 95, $y + 38, 95, 10);
    $pdf->SetXY($x + 97, $y + 40);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Other Reference(s): PAN : AAECS6338L', 0, 1);
    
    $pdf->SetY($y + 53);
    $pdf->Ln(2);
}

// Function to add table header
function addTableHeader($pdf, $rate_header) {
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetFillColor(240, 240, 240);
    
    $pdf->Cell(30, 8, 'Order #', 1, 0, 'C', true);
    $pdf->Cell(75, 8, 'Style No, Description & Colour', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'Qnty in Pcs.', 1, 0, 'C', true);
    $pdf->Cell(32, 8, 'Rate in ' . $rate_header, 1, 0, 'C', true);
    $pdf->Cell(33, 8, 'Amount in ' . $rate_header, 1, 0, 'C', true);
    $pdf->Ln();
}

// Include the convertNumberToWord function
function convertNumberToWord($number) {
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
        5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
        15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
        20 => 'twenty', 30 => 'thirty', 40 => 'fourty', 50 => 'fifty',
        60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety',
        100 => 'hundred', 1000 => 'thousand', 100000 => 'lakh', 10000000 => 'crore'
    );

    if (!is_numeric($number)) return false;
    
    $string = $fraction = null;
    if (strpos($number, '.') !== false) list($number, $fraction) = explode('.', $number);

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) $string .= $hyphen . $dictionary[$units];
            break;
        case $number < 1000:
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) $string .= $conjunction . convertNumberToWord($remainder);
            break;
        case $number < 100000:
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            $string = convertNumberToWord($thousands) . ' ' . $dictionary[1000];
            if ($remainder) $string .= $separator . convertNumberToWord($remainder);
            break;
        case $number < 10000000:
            $lakhs = floor($number / 100000);
            $remainder = $number % 100000;
            $string = convertNumberToWord($lakhs) . ' ' . $dictionary[100000];
            if ($remainder) $string .= $separator . convertNumberToWord($remainder);
            break;
        default:
            $crores = floor($number / 10000000);
            $remainder = $number % 10000000;
            $string = convertNumberToWord($crores) . ' ' . $dictionary[10000000];
            if ($remainder) $string .= $separator . convertNumberToWord($remainder);
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        foreach (str_split((string) $fraction) as $number) {
            $string .= ' ' . $dictionary[$number];
        }
    }

    return ucfirst($string);
}

// Add header to first page
addFullHeader($pdf, $print_packing_list, $arr_order_no);

// Prepare rate type text
$rate_type_text = '';
if(isset($print_packing_list[0]->rate_type)) {
    switch($print_packing_list[0]->rate_type) {
        case 1: $rate_type_text = 'Ex. Works'; break;
        case 2: $rate_type_text = 'C&F'; break;
        case 3: $rate_type_text = 'CIF'; break;
        case 4: $rate_type_text = 'FOB'; break;
    }
}

$currency = isset($print_packing_list[0]->currency) ? $print_packing_list[0]->currency : '';
$rate_header = $rate_type_text ? $rate_type_text . ' / ' . $currency : $currency;

// Add initial table header
addTableHeader($pdf, $rate_header);

// Initialize totals
$tot_qnty = 0;
$tot_amnt = 0;

// Process items with TCPDF automatic page breaking
$pdf->SetFont('helvetica', '', 8);

// Track total pages for later reference
$total_items = count($print_packing_list);
$current_item = 0;

foreach($print_packing_list as $ppl) {
    $current_item++;
    
    // Clean and prepare text data
    $style_description = strip_tags($ppl->alt_art_no) . ' ' . 
                        strip_tags($ppl->art_info) . ' ' . 
                        strip_tags($ppl->color) . ' ' . 
                        strip_tags($ppl->item_no) . ' ' . 
                        strip_tags($ppl->reference_no);
    $style_description = trim(preg_replace('/\s+/', ' ', $style_description));
    
    // Calculate height needed for both rows (main + details)
    $nb_lines = $pdf->getNumLines($style_description, 75);
    $main_row_height = max(6, $nb_lines * 4);
    
    // Prepare details text
    $hand_machine = '';
    if(!empty(trim($ppl->hand_machine)) && $print_packing_list[0]->print_hand_ratio == 1) {
        $hand_machine = 'Hand & Machine Ratio: '.strip_tags($ppl->hand_machine).', ';
    }
    
    $brand = ($ppl->brand != '') ? strip_tags($ppl->brand) : 'Unbranded';
    
    $details = $hand_machine . 
               strip_tags($ppl->leather_type_info) . ', H.S.Code: ' . 
               strip_tags($ppl->remark) . ', Weight: ' . 
               strip_tags($ppl->wl_rate_a) . 'k.g, ' . 
               strip_tags($ppl->metal_fitting) . ', ' . 
               strip_tags($ppl->size) . ', ' . $brand;
    
    $details = trim(preg_replace('/\s+/', ' ', $details));
    $details = html_entity_decode($details, ENT_QUOTES, 'UTF-8');
    
    // Calculate details row height
    $details_lines = $pdf->getNumLines($details, 190);
    $details_row_height = max(5, $details_lines * 3.5);
    
    // Total height needed for this item
    $total_item_height = $main_row_height + $details_row_height + 2;
    
    // Check if we have enough space for the complete item (both rows)
    $current_y = $pdf->GetY();
    $page_height = $pdf->getPageHeight();
    $bottom_margin = 15; // Our auto page break margin
    $available_space = $page_height - $current_y - $bottom_margin;
    
    // Need extra space to potentially add "CONTINUED" + footer if this is the last item before page break
    $space_needed = $total_item_height;
    if($current_item < $total_items) {
        $space_needed += 60; // Space for potential footer + continued text
    }
    
    // If not enough space, add footer and continued text, then trigger page break
    if($available_space < $space_needed && $current_item < $total_items) {
        // Add Bank Details and Signature on current page
        addBankDetailsAndSignature($pdf, $print_packing_list);
        
        // Add continued text
        addContinuedText($pdf);
        
        // Add new page
        $pdf->AddPage();
        addCompactHeader($pdf, $print_packing_list, $arr_order_no);
        addTableHeader($pdf, $rate_header);
        $pdf->SetFont('helvetica', '', 8);
    }
    
    // Now draw the main item row
    $pdf->Cell(30, $main_row_height, $ppl->buyer_reference_no, 1, 0, 'L');
    
    // Save X position for style description
    $x_pos = $pdf->GetX();
    $y_pos = $pdf->GetY();
    $pdf->MultiCell(75, $main_row_height, $style_description, 1, 'L', false, 0);
    
    // Continue with other cells
    $pdf->Cell(20, $main_row_height, $ppl->quantity, 1, 0, 'R');
    $pdf->Cell(32, $main_row_height, number_format($ppl->rate_foreign + $ppl->additional_charges, 3), 1, 0, 'R');
    $pdf->Cell(33, $main_row_height, $ppl->amount, 1, 0, 'R');
    $pdf->Ln();
    
    // Details row
    $pdf->SetFont('helvetica', '', 7);
    $pdf->MultiCell(190, 0, $details, 1, 'L');
    $pdf->SetFont('helvetica', '', 8);
    
    $tot_qnty += $ppl->quantity;
    $tot_amnt += $ppl->amount;
}

// Calculate totals
$discount = isset($print_packing_list[0]->discount) ? $print_packing_list[0]->discount : 0;
$hand_charge = isset($print_packing_list[0]->hand_charge) ? $print_packing_list[0]->hand_charge : 0;
$discount_amount = round($tot_amnt * ($discount / 100), 2);
$net_total = $tot_amnt - $discount_amount + $hand_charge;

// Check if totals section will fit on current page
$space_needed = 27; // Space for total rows
if(!empty($all_declarations)) {
    $declaration_text = implode(' ', $all_declarations);
    $declaration_text = preg_replace('/\s+/', ' ', trim($declaration_text));
    $declaration_height = $pdf->getStringHeight(186, $declaration_text);
    $declaration_height = max($declaration_height + 6, 20);
    $space_needed += $declaration_height + 5;
}
$space_needed += 55; // Space for Bank Details and Signature section

$current_y = $pdf->GetY();
$page_height = $pdf->getPageHeight();
$available_space = $page_height - $current_y - 15; // Account for bottom margin

if($available_space < $space_needed) {
    // Add footer and continued text on current page
    addBankDetailsAndSignature($pdf, $print_packing_list);
    addContinuedText($pdf);
    
    $pdf->AddPage();
    addCompactHeader($pdf, $print_packing_list, $arr_order_no);
}

// Add totals section
$pdf->SetFont('helvetica', 'B', 9);

// Create the amount in words text and break it into multiple lines if needed
$amount_in_words = strtoupper($currency . ' ' . convertNumberToWord(round($net_total, 2)));

// Use MultiCell for the amount in words to allow line breaks
$x_pos = $pdf->GetX();
$y_pos = $pdf->GetY();
$pdf->MultiCell(105, 3.5, $amount_in_words, 1, 'L');

// Position for the remaining cells in the same row
$pdf->SetXY($x_pos + 105, $y_pos);
$pdf->Cell(20, 7, number_format($tot_qnty), 1, 0, 'R');
$pdf->Cell(32, 7, 'Total ' . $currency, 1, 0, 'L');
$pdf->Cell(33, 7, number_format($tot_amnt, 2), 1, 0, 'R');
$pdf->Ln();

// Discount row
$pdf->Cell(105, 6, '', 1, 0, 'L');
$pdf->Cell(52, 6, '(-)Less: Discount @ ' . $discount . '%', 1, 0, 'L');
$pdf->Cell(33, 6, number_format($discount_amount, 2), 1, 0, 'R');
$pdf->Ln();

// Handling charges row
$pdf->Cell(105, 6, '', 1, 0, 'L');
$pdf->Cell(52, 6, '(+)Handling Charges', 1, 0, 'L');
$pdf->Cell(33, 6, number_format($hand_charge, 2), 1, 0, 'R');
$pdf->Ln();

// Weight and Net Total row - split into 2 lines (labels and values)
$pdf->SetFont('helvetica', '', 8);

// Save position for weight cells
$x_pos = $pdf->GetX();
$y_pos = $pdf->GetY();

// Gross Weight cell with 2 lines
$pdf->Rect($x_pos, $y_pos, 32, 8);
$pdf->SetXY($x_pos + 1, $y_pos + 1);
$pdf->Cell(30, 3, 'Gross Weight:', 0, 1, 'L');
$pdf->SetX($x_pos + 1);
$pdf->Cell(30, 3, number_format($print_packing_list[0]->gross_weight, 2) . ' Kgs', 0, 0, 'L');

// Net Weight cell with 2 lines
$pdf->Rect($x_pos + 32, $y_pos, 32, 8);
$pdf->SetXY($x_pos + 33, $y_pos + 1);
$pdf->Cell(30, 3, 'Net Weight:', 0, 1, 'L');
$pdf->SetX($x_pos + 33);
$pdf->Cell(30, 3, number_format($print_packing_list[0]->net_weight, 2) . ' Kgs', 0, 0, 'L');

// Volume Weight cell with 2 lines
$pdf->Rect($x_pos + 64, $y_pos, 41, 8);
$pdf->SetXY($x_pos + 65, $y_pos + 1);
$pdf->Cell(39, 3, 'Volume Weight:', 0, 1, 'L');
$pdf->SetX($x_pos + 65);
$pdf->Cell(39, 3, number_format($print_packing_list[0]->volume_weight, 2) . ' Kgs', 0, 0, 'L');

// Net Total cells
$pdf->SetXY($x_pos + 105, $y_pos);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(52, 8, 'Net Total ' . $currency, 1, 0, 'L');
$pdf->Cell(33, 8, number_format($net_total, 2), 1, 0, 'R');
$pdf->Ln();

// Add declarations if any
if(!empty($all_declarations)) {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 8);
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // Check one more time if declaration will fit
    $current_y = $pdf->GetY();
    $available_space = $page_height - $current_y - 15;
    
    if($available_space < $declaration_height + 55) { // Include space for footer
        // Add footer and continued text
        addBankDetailsAndSignature($pdf, $print_packing_list);
        addContinuedText($pdf);
        
        $pdf->AddPage();
        addCompactHeader($pdf, $print_packing_list, $arr_order_no);
        $pdf->Ln(5);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
    }
    
    // Draw border
    $pdf->Rect($x, $y, 190, $declaration_height);
    
    // Add text
    $pdf->SetXY($x + 2, $y + 3);
    $pdf->MultiCell(186, 5, $declaration_text, 0, 'L');
    
    $pdf->SetY($y + $declaration_height);
}

// Finally, add the Bank Details and Signature section at the end (not as footer)
addBankDetailsAndSignature($pdf, $print_packing_list);

// Output PDF
$pdf->Output('office_invoice_' . $print_packing_list[0]->office_invoice_number . '.pdf', 'I');
?>