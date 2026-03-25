<?php
// Load TCPDF library
require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

// Extend TCPDF to add custom footer
class PackingListPDF extends TCPDF {
    
    // Properties for footer data
    public $footer_data = array();
    public $show_continued = false;
    public $is_last_page = false;
    public $last_page_y = 0; // Track Y position for last page footer
    
    // Page footer
    public function Footer() {
        if(!empty($this->footer_data)) {
            // For last page, use the tracked Y position (just below totals)
            if($this->is_last_page && $this->last_page_y > 0) {
                $footer_y = $this->last_page_y;
            } else {
                // For other pages, use reduced position at bottom - CHANGED from -48 to -50
                $this->SetY(-50); // Position for footer - adjusted to prevent overlap
                $footer_y = $this->GetY();
            }
            
            $footer_height = 38;
            
            // Dimensions box
            $this->Rect(10, $footer_y, 95, $footer_height);
            $this->SetXY(12, $footer_y + 2);
            $this->SetFont('helvetica', 'B', 9);
            $this->Cell(0, 4, 'DIMENSIONS', 0, 1);
            
            if($this->footer_data['header_box_size'] == '') {
                $this->SetFont('helvetica', '', 9);
                foreach ($this->footer_data['arr_leather_dimention'] as $aald) {
                    $this->SetX(12);
                    $dimension_text = $aald . ' : ';
                    
                    // Count cartons for this dimension
                    $temp_count = array();
                    foreach ($this->footer_data['print_packing_list'] as $key) {
                        if ($key->item_name == $aald) {
                            if (!in_array($key->carton_number, $temp_count)) {
                                array_push($temp_count, $key->carton_number);
                            }
                        }
                    }
                    $dimension_text .= count($temp_count) . ' pkt.';
                    $this->Cell(0, 4, $dimension_text, 0, 1);
                }
            } else {
                $this->SetFont('helvetica', '', 9);
                $this->SetX(12);
                $this->MultiCell(90, 4, $this->footer_data['header_box_size'], 0, 'L');
            }
            
            $this->SetFont('helvetica', '', 9);
            $this->SetX(12);
            $this->Cell(0, 4, 'Gross CBM: 0.00', 0, 1);
            $this->SetX(12);
            $this->Cell(0, 4, 'Gross Weight: ' . $this->footer_data['gross_weight'] . ' Kgs', 0, 1);
            $this->SetX(12);
            $this->Cell(0, 4, 'Net Weight: ' . $this->footer_data['net_weight'] . ' Kgs', 0, 1);
            
            // Signature box
            $this->Rect(105, $footer_y, 95, $footer_height);
            $this->SetXY(107, $footer_y + 2);
            $this->SetFont('helvetica', '', 9);
            $this->Cell(0, 4, 'Signature & Date', 0, 1);
            $this->SetFont('helvetica', 'B', 10);
            $this->SetX(107);
            $this->Cell(0, 4, 'SHILPA OVERSEAS (PVT.) LTD', 0, 1);
            
            // Add signature image
            $signature_path = FCPATH . 'assets/img/shilpa1.png';
            if (file_exists($signature_path)) {
                $this->Image($signature_path, 107, $footer_y + 10, 0, 18, 'PNG');
            }
            
            // Signature line and date
            $this->SetXY(107, $footer_y + 30);
            $this->SetFont('helvetica', '', 9);
            $this->Cell(47, 4, 'Authorised Signatory', 0, 0, 'L');
            $this->Cell(46, 4, $this->footer_data['package_date'], 0, 0, 'R');
            
            // If not last page, show "CONTINUED ON NEXT PAGE" below the footer boxes
            if($this->show_continued) {
                $this->SetY($footer_y + $footer_height + 2); // Position below footer boxes
                $this->SetFont('helvetica', 'B', 10);
                $this->SetX(10);
                $this->Cell(190, 5, '--- CONTINUED ON NEXT PAGE ---', 0, 1, 'C');
            }
        }
    }
}

// Process order numbers for display
$orda = array();
$ord_last = '';
foreach($print_packing_list as $fd){
    if(!in_array($fd->buyer_reference_no, $orda)){
        array_push($orda, $fd->buyer_reference_no);
        $ord_last .= $fd->buyer_reference_no . ', ';
    }
}

// Process dimensions and leather types
$arr_leather_type = array();
$arr_leather_dimention = array();
$arr_crtn_count = array();

foreach ($print_packing_list as $pplist) {
    if (!in_array($pplist->leather_type, $arr_leather_type)) {
        array_push($arr_leather_type, $pplist->leather_type);
    }
    if (!in_array($pplist->item_name, $arr_leather_dimention)) {
        array_push($arr_leather_dimention, $pplist->item_name);
    }
}

// Calculate totals
$total_count = count($print_packing_list);
$gross_qnty = 0;
$gross_weight = 0;
$net_weight = 0;

foreach ($print_packing_list as $ppl_temp) {
    $net = ($ppl_temp->net_weight <= 0) ? 0 : $ppl_temp->net_weight;
    $gross = ($ppl_temp->gross_weight <= 0) ? 0 : $ppl_temp->gross_weight;
    
    $gross_qnty += $ppl_temp->article_quantity;
    $gross_weight += $gross;
    $net_weight += $net;
}

// Process carton groups for totals
$groups = array();
foreach ($print_packing_list as $item) {
    $key = $item->carton_number;
    if (!isset($groups[$key])) {
        $groups[$key] = array(
            'crt_nu' => $item->carton_number,
            'gr_sum' => $item->article_quantity,
        );
    } else {
        $groups[$key]['crt_nu'] = $item->carton_number;
        $groups[$key]['gr_sum'] += $item->article_quantity;
    }
}

// Create new PDF document using custom class
$pdf = new PackingListPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(COMPANY_NAME);
$pdf->SetTitle('PACKING LIST');

// Remove default header
$pdf->setPrintHeader(false);

// Set margins (REDUCED bottom margin for footer from 55 to 52)
$pdf->SetMargins(10, 10, 10);
$pdf->SetFooterMargin(52); // ADJUSTED from 45 to 52 to prevent overlap
$pdf->SetAutoPageBreak(TRUE, 52); // ADJUSTED from 45 to 52 to prevent overlap

// Set footer data
$pdf->footer_data = array(
    'print_packing_list' => $print_packing_list,
    'arr_leather_dimention' => $arr_leather_dimention,
    'arr_crtn_count' => $arr_crtn_count,
    'gross_weight' => $gross_weight,
    'net_weight' => $net_weight,
    'header_box_size' => $print_packing_list[0]->header_box_size,
    'package_date' => $print_packing_list[0]->package_date
);

// Add first page
$pdf->AddPage();

// Function to add full header for first page
function addFullPackingHeader($pdf, $print_packing_list, $ord_last, $acc_master_details, $page_num = 1) {
    // Page number in top right
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(160, 5);
    $pdf->Cell(40, 5, 'Page No. ' . $page_num, 0, 0, 'R');
    
    // Title
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetXY(10, 10);
    $pdf->Cell(190, 10, 'PACKING LIST', 1, 1, 'C');
    
    // Uniform vertical spacing
    $pdf->Ln(4);
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // FIRST ROW - Exporter and Invoice details
    // LEFT COLUMN - Exporter
    $pdf->Rect($x, $y, 95, 45);
    $pdf->SetXY($x + 2, $y + 2);
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
    
    // RIGHT COLUMN - Invoice details
    $pdf->SetFont('helvetica', '', 9);
    
    // Invoice No box
    $pdf->Rect($x + 95, $y, 47.5, 16);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->Cell(0, 4, 'Invoice No. & Date', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->package_name, 0, 1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->package_date, 0, 1);
    
    // Export Ref box
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Rect($x + 142.5, $y, 47.5, 16);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->Cell(0, 4, 'Export Ref.', 0, 1);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, 'GSTIN: 19AAECS6338L1ZT', 0, 1);
    
    // Buyer Order No box
    $pdf->Rect($x + 95, $y + 16, 95, 21);
    $pdf->SetXY($x + 97, $y + 18);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Buyer Order No. & Date:', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 4, rtrim($ord_last, ', '), 0, 'L');
    
    // Other references box
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Rect($x + 95, $y + 37, 95, 8);
    $pdf->SetXY($x + 97, $y + 39);
    $pdf->Cell(0, 4, 'Other Reference(s): PAN : AAECS6338L', 0, 1);
    
    // Move to next row with uniform spacing
    $pdf->SetY($y + 45);
    $pdf->Ln(4);
    
    // SECOND ROW - Consignee and Country info
    $y = $pdf->GetY();
    
    // Consignee box - adjusted height to accommodate extra space after Delivery Address
    $pdf->Rect($x, $y, 95, 36);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 4, 'Consignee', 0, 1);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, $print_packing_list[0]->acc_name, 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(90, 3.5, 
        'Billing Address: ' . $print_packing_list[0]->billing_address . "\n" .
        'Email: ' . $print_packing_list[0]->email_id . "\n" .
        'Delivery Address: ' . $print_packing_list[0]->delivery_address, 0, 'L');
    
    // Country boxes
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Rect($x + 95, $y, 47.5, 7);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->Cell(0, 4, 'Country of Origin of Goods', 0, 1);
    
    $pdf->Rect($x + 142.5, $y, 47.5, 7);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->Cell(0, 4, 'West Bengal / India', 0, 1);
    
    $pdf->Rect($x + 95, $y + 7, 47.5, 7);
    $pdf->SetXY($x + 97, $y + 9);
    $pdf->Cell(0, 4, 'Country of final delivery', 0, 1);
    
    $pdf->Rect($x + 142.5, $y + 7, 47.5, 7);
    $pdf->SetXY($x + 144.5, $y + 9);
    $pdf->Cell(0, 4, ucfirst(strtolower($print_packing_list[0]->acc_country)), 0, 1);
    
    // Buyer box
    $pdf->Rect($x + 95, $y + 14, 95, 22);
    $pdf->SetXY($x + 97, $y + 16);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Buyer (if other than consignee)', 0, 1);
    if(isset($acc_master_details) && $acc_master_details->acc_name != '') {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetX($x + 97);
        $pdf->Cell(0, 4, $acc_master_details->acc_name, 0, 1);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX($x + 97);
        $pdf->MultiCell(90, 3.5, $acc_master_details->acc_address, 0, 'L');
    }
    
    // Move to next row with uniform spacing
    $pdf->SetY($y + 36);
    $pdf->Ln(4);
    
    // THIRD ROW - Other info and shipping
    $y = $pdf->GetY();
    
    // Other Information box - minimum height 10mm
    $other_info_height = 12;
    // If other_information is empty, use minimum height
    if(empty($print_packing_list[0]->other_information) || trim($print_packing_list[0]->other_information) == '') {
        $other_info_height = 10; // Minimum height
    }
    
    $pdf->Rect($x, $y, 95, $other_info_height);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Other Information:', 0, 1);
    if(!empty($print_packing_list[0]->other_information) && trim($print_packing_list[0]->other_information) != '') {
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX($x + 2);
        $pdf->MultiCell(90, 3, $print_packing_list[0]->other_information, 0, 'L');
    }
    
    // Pre-Carriage box
    $pre_carriage = '';
    if($print_packing_list[0]->pre_carriage_by == 1) $pre_carriage = 'By Air';
    else if($print_packing_list[0]->pre_carriage_by == 2) $pre_carriage = 'By Ship';
    else if($print_packing_list[0]->pre_carriage_by == 3) $pre_carriage = 'By Road';
    
    $pdf->Rect($x + 95, $y, 47.5, $other_info_height);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Pre-Carriage By', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $pre_carriage, 0, 1);
    
    // Port of Loading box
    $pdf->Rect($x + 142.5, $y, 47.5, $other_info_height);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Port of Loading', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, 'Kolkata', 0, 1);
    
    // Move to next row with uniform spacing
    $pdf->SetY($y + $other_info_height);
    $pdf->Ln(4);
    
    // FOURTH ROW - Port and Terms
    $y = $pdf->GetY();
    
    // Port of Discharge
    $pdf->Rect($x, $y, 39, 12);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Port of Discharge', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 4, strtoupper($print_packing_list[0]->port_of_discharge), 0, 1);
    
    // Final Destination
    $pdf->Rect($x + 39, $y, 56, 12);
    $pdf->SetXY($x + 41, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Final Destination', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 41);
    $pdf->Cell(0, 4, strtoupper($print_packing_list[0]->acc_country), 0, 1);
    
    // Terms of Delivery
    $pdf->Rect($x + 95, $y, 95, 12);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Terms of Delivery & Payment', 0, 1);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 3, strtoupper($print_packing_list[0]->terms_of_delivery), 0, 'L');
    
    // Move to next row with uniform spacing
    $pdf->SetY($y + 12);
    $pdf->Ln(4);
    
    // FIFTH ROW - Mark, Package and Description
    $y = $pdf->GetY();
    
    // Mark & Container
    $pdf->Rect($x, $y, 39, 24);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Mark & Container', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(35, 3, strtoupper($print_packing_list[0]->mark_container), 0, 'L');
    
    // No. & Kind of Pkgs
    $pdf->Rect($x + 39, $y, 56, 24);
    $pdf->SetXY($x + 41, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'No. & Kind of Pkgs', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 41);
    $pdf->MultiCell(52, 3, strtoupper($print_packing_list[0]->no_of_kind_of_package), 0, 'L');
    
    // Description of Goods
    $pdf->Rect($x + 95, $y, 95, 12);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 4, 'Description of Goods', 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 97);
    $pdf->MultiCell(90, 3, strtoupper($print_packing_list[0]->description_of_goods), 0, 'L');
    
    // Notify
    $pdf->Rect($x + 95, $y + 12, 95, 12);
    $pdf->SetXY($x + 97, $y + 14);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Write(0, 'Notify: ');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Write(0, $print_packing_list[0]->notify);
    
    $pdf->SetY($y + 24);
    $pdf->Ln(6);
}

// Function to add compact header for continuation pages
function addCompactPackingHeader($pdf, $print_packing_list, $ord_last, $page_num) {
    // Page number in top right
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(160, 5);
    $pdf->Cell(40, 5, 'Page No. ' . $page_num, 0, 0, 'R');
    
    // Title
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetXY(10, 10);
    $pdf->Cell(190, 10, 'PACKING LIST', 1, 1, 'C');
    $pdf->Ln(3);
    
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // LEFT COLUMN - Compact Exporter info
    $pdf->Rect($x, $y, 95, 35);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, 'Exporter', 0, 1);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetX($x + 2);
    $pdf->Cell(0, 5, COMPANY_NAME, 0, 1);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetX($x + 2);
    $pdf->MultiCell(90, 3.5, 'Address: ' . HEADER_ADDRESS . "\n" . 
                            'Factory Address: ' . HEADER_FACTORY_ADDRESS . "\n" .
                            'Contact: ' . HEADER_TEL . "\n" .
                            'Email: ' . HEADER_EMAIL . "\n" .
                            'CIN: ' . HEADER_CIN, 0, 'L');
    
    // RIGHT COLUMN - Invoice and Order details
    $pdf->SetFont('helvetica', '', 9);
    
    // Invoice No & Date box
    $pdf->Rect($x + 95, $y, 47.5, 17.5);
    $pdf->SetXY($x + 97, $y + 2);
    $pdf->Cell(0, 4, 'Invoice No. & Date', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->package_name, 0, 1);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, $print_packing_list[0]->package_date, 0, 1);
    
    // Export Ref box
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Rect($x + 142.5, $y, 47.5, 17.5);
    $pdf->SetXY($x + 144.5, $y + 2);
    $pdf->Cell(0, 4, 'Export Ref.', 0, 1);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, 'GSTIN:', 0, 1);
    $pdf->SetX($x + 144.5);
    $pdf->Cell(0, 4, '19AAECS6338L1ZT', 0, 1);
    
    // Buyer Order No box
    $pdf->Rect($x + 95, $y + 17.5, 95, 17.5);
    $pdf->SetXY($x + 97, $y + 19.5);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'Buyer Order No. & Date: ' . rtrim($ord_last, ', '), 0, 1);
    $pdf->SetX($x + 97);
    $pdf->Ln(2);
    $pdf->SetX($x + 97);
    $pdf->Cell(0, 4, 'Other Reference(s) : PAN : AAECS6338L', 0, 1);
    
    $pdf->SetY($y + 38);
    $pdf->Ln(2);
}

// Function to add table header
function addPackingTableHeader($pdf, $show_item_ref = false) {
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetFillColor(240, 240, 240);
    
    if($show_item_ref) {
        // With Item and Reference columns
        $pdf->Cell(15, 8, 'CRTN', 1, 0, 'C', true);  // Reduced from 20 to 15
        $pdf->Cell(25, 8, 'Order No.', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'Style No, Description & Colour', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Item', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Reference', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Qnty in PCS', 1, 0, 'C', true);
        $pdf->Cell(15, 8, 'Total/Box', 1, 0, 'C', true);  // Reduced from 20 to 15
        $pdf->Cell(25, 8, 'Gross Wt.', 1, 0, 'C', true);  // Increased from 20 to 25
        $pdf->Cell(25, 8, 'Net Wt.', 1, 0, 'C', true);    // Increased from 20 to 25
    } else {
        // Without Item and Reference columns
        $pdf->Cell(15, 8, 'CRTN', 1, 0, 'C', true);  // Reduced from 20 to 15
        $pdf->Cell(25, 8, 'Order No.', 1, 0, 'C', true);
        $pdf->Cell(85, 8, 'Style No, Description & Colour', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Qnty in PCS', 1, 0, 'C', true);
        $pdf->Cell(15, 8, 'Total/Box', 1, 0, 'C', true);  // Reduced from 20 to 15
        $pdf->Cell(15, 8, 'Gross Wt.', 1, 0, 'C', true);  // Increased from 10 to 15
        $pdf->Cell(15, 8, 'Net Wt.', 1, 0, 'C', true);    // Increased from 10 to 15
    }
    $pdf->Ln();
}

// Start generating PDF
// Add header to first page
addFullPackingHeader($pdf, $print_packing_list, $ord_last, isset($acc_master_details) ? $acc_master_details : null, 1);

// Determine if we need Item and Reference columns
$show_item_ref = ($print_packing_list[0]->acc_am_id == 3);

// Add table header
addPackingTableHeader($pdf, $show_item_ref);

// Track pages and items
$pdf->SetFont('helvetica', '', 8);
$page_num = 1;
$total_items = count($print_packing_list);
$items_processed = 0;

// Enable footer for all pages except last
$pdf->show_continued = true;

foreach ($print_packing_list as $curr_key => $ppl) {
    $items_processed++;
    
    // Get keys for this carton group
    $keys = array();
    foreach($print_packing_list as $key => $val) {
        if ($val->carton_number == $ppl->carton_number) {
            array_push($keys, $key);
        }
    }
    
    // Prepare style description text
    $style_description = strip_tags($ppl->alt_art_no . ' ' . $ppl->info . ' ' . $ppl->leather_color);
    $style_description = trim(preg_replace('/\s+/', ' ', $style_description));
    
    // Save starting Y position
    $startY = $pdf->GetY();
    
    // Calculate row height based on text length
    $desc_width = $show_item_ref ? 65 : 85;
    $nb_lines = $pdf->getNumLines($style_description, $desc_width);
    $row_height = max(7, $nb_lines * 3.5);
    
    // Draw row cells using MultiCell with position tracking
    if($show_item_ref) {
        $pdf->MultiCell(15, $row_height, $ppl->carton_number, 1, 'C', false, 0);  // Reduced from 20 to 15
        $pdf->MultiCell(25, $row_height, $ppl->buyer_reference_no, 1, 'L', false, 0);
        $pdf->MultiCell(65, $row_height, $style_description, 1, 'L', false, 0);
        $pdf->MultiCell(20, $row_height, isset($ppl->item) ? $ppl->item : '', 1, 'C', false, 0);
        $pdf->MultiCell(20, $row_height, isset($ppl->reference) ? $ppl->reference : '', 1, 'C', false, 0);
        $pdf->MultiCell(20, $row_height, round($ppl->article_quantity), 1, 'R', false, 0);
        
        if(end($keys) == $curr_key) {
            $pdf->MultiCell(15, $row_height, round($groups[$ppl->carton_number]['gr_sum']), 1, 'R', false, 0);  // Reduced from 20 to 15
        } else {
            $pdf->MultiCell(15, $row_height, '', 1, 'R', false, 0);  // Reduced from 20 to 15
        }
        
        $pdf->MultiCell(25, $row_height, ($ppl->gross_weight <= 0) ? '' : $ppl->gross_weight, 1, 'R', false, 0);  // Increased from 20 to 25
        $pdf->MultiCell(25, $row_height, ($ppl->net_weight <= 0) ? '' : $ppl->net_weight, 1, 'R', false, 1);    // Increased from 20 to 25
    } else {
        $pdf->MultiCell(15, $row_height, $ppl->carton_number, 1, 'C', false, 0);  // Reduced from 20 to 15
        $pdf->MultiCell(25, $row_height, $ppl->buyer_reference_no, 1, 'L', false, 0);
        $pdf->MultiCell(85, $row_height, $style_description, 1, 'L', false, 0);
        $pdf->MultiCell(20, $row_height, round($ppl->article_quantity), 1, 'R', false, 0);
        
        if(end($keys) == $curr_key) {
            $pdf->MultiCell(15, $row_height, round($groups[$ppl->carton_number]['gr_sum']), 1, 'R', false, 0);  // Reduced from 20 to 15
        } else {
            $pdf->MultiCell(15, $row_height, '', 1, 'R', false, 0);  // Reduced from 20 to 15
        }
        
        $pdf->MultiCell(15, $row_height, ($ppl->gross_weight <= 0) ? '' : $ppl->gross_weight, 1, 'R', false, 0);  // Increased from 10 to 15
        $pdf->MultiCell(15, $row_height, ($ppl->net_weight <= 0) ? '' : $ppl->net_weight, 1, 'R', false, 1);    // Increased from 10 to 15
    }
    
    // Check if next row fits using dynamic calculation - REDUCED buffer from 10 to 2
    $usable_height = $pdf->getPageHeight() - $pdf->getBreakMargin() - 2; // REDUCED buffer from 5 to 2
    if ($pdf->GetY() >= $usable_height) {
        $pdf->AddPage();
        $page_num++;
        addCompactPackingHeader($pdf, $print_packing_list, $ord_last, $page_num);
        addPackingTableHeader($pdf, $show_item_ref);
        $pdf->SetFont('helvetica', '', 8);
    }
}

// Check if totals row will fit - REDUCED buffer from 10 to 2
$row_height = 7; // Height for totals row
$usable_height = $pdf->getPageHeight() - $pdf->getBreakMargin() - 2; // REDUCED buffer from 5 to 2
if ($pdf->GetY() + $row_height >= $usable_height) {
    $pdf->AddPage();
    $page_num++;
    addCompactPackingHeader($pdf, $print_packing_list, $ord_last, $page_num);
    addPackingTableHeader($pdf, $show_item_ref);
}

// Disable continued text for the last page (with totals)
$pdf->show_continued = false;
$pdf->is_last_page = true;

// Add totals row
$pdf->SetFont('helvetica', 'B', 10);
if($show_item_ref) {
    $pdf->Cell(145, $row_height, 'Total', 1, 0, 'L');  // Adjusted from 150 to 145 (5mm reduction)
    $pdf->Cell(20, $row_height, $gross_qnty, 1, 0, 'R');
    $pdf->Cell(15, $row_height, '', 1, 0, 'R');  // Reduced from 20 to 15
    $pdf->Cell(25, $row_height, number_format($gross_weight, 2), 1, 0, 'R');  // Increased from 20 to 25
    $pdf->Cell(25, $row_height, ($net_weight <= 0) ? '0' : number_format($net_weight, 2), 1, 0, 'R');  // Increased from 20 to 25
} else {
    $pdf->Cell(125, $row_height, 'Total', 1, 0, 'L');  // Adjusted from 130 to 125 (5mm reduction)
    $pdf->Cell(20, $row_height, $gross_qnty, 1, 0, 'R');
    $pdf->Cell(15, $row_height, '', 1, 0, 'R');  // Reduced from 20 to 15
    $pdf->Cell(15, $row_height, number_format($gross_weight, 2), 1, 0, 'R');  // Increased from 10 to 15
    $pdf->Cell(15, $row_height, ($net_weight <= 0) ? '0' : number_format($net_weight, 2), 1, 0, 'R');  // Increased from 10 to 15
}
$pdf->Ln();

// Set the Y position for the last page footer (REDUCED gap from 3mm to 1mm)
$pdf->last_page_y = $pdf->GetY() + 1; // REDUCED gap after totals from 3mm to 1mm

// Output PDF
$pdf->Output('packing_list_' . $print_packing_list[0]->package_name . '.pdf', 'I');
?>