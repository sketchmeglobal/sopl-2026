<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 11-03-2020
 * Time: 10:56
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Delete_all_old_data extends My_Controller {

    private $user_type = null;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('grocery_CRUD');

        if($this->session->has_userdata('user_id')) { //if logged-in
            $this->user_type = $this->session->usertype;
        }
    }
    
    public function delete_all_old_data(){
        //echo"hi";
         $this->load->view('delete_all_old_data');
    }
    
    public function delete_all_data() {
        
          $this->db->trans_start();
          
          //Update Item Detail:
 
        //$this->db->query("UPDATE item_dtl SET opening_stock = 0, opn_qnty_for_leather_status = 0, opening_rate = 0, virtual_opng_stock = 0, virtual_opng_rate = 0");
        
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        
        $this->db->query("DELETE FROM customer_order_dtl 
                          WHERE co_id IN (SELECT co_id FROM customer_order WHERE store_for_next_year = 'No');");
                          
        $this->db->query("DELETE FROM customer_order_combination_article_colors 
                          WHERE co_id IN (SELECT co_id FROM customer_order WHERE store_for_next_year = 'No');");
                          
        $this->db->query("DELETE FROM temp_customer_order_dtl 
                          WHERE co_id IN (SELECT co_id FROM customer_order WHERE store_for_next_year = 'No');");

        // Delete from main table
        $this->db->query("DELETE FROM customer_order WHERE store_for_next_year = 'No'");
        
        $this->db->query("UPDATE customer_order SET store_for_next_year = 'No'");


        $tables_to_truncate = [
            "advance_salary_department", "checking_details", "cutter_bill_dtl", "cutting_issue_challan_details",
            "cutting_received_challan_detail", "finishing_details", "inking_details", "jobber_bill_detail",
            "jobber_challan_receipt_details", "jobber_issue_details", "lining_details", "material_issue_detail",
            "office_invoice_detail", "office_proforma_detail", "packing_shipment_detail", "platting_issue_detail",
            "purchase_order_details", "purchase_order_receive_detail", "sample_bill_detail", "sample_issue_details",
            "sample_receive_details", "skiving_bill_detail", "skiving_receive_challan_details", "splitting_bill_detail",
            "splitting_receive_challan_details", "stitching_bill_detail", "stock_in_detail", "supp_purchase_order_detail",
            "temp_customer_order_dtl", "advance", "checking", "cutter_bill", "cutting_issue_challan",
            "cutting_received_challan", "finishing", "gst_data_upload", "inking", "invoice_declaration",
            "jobber_bill", "jobber_challan_receipt", "jobber_issue", "lining", "material_issue",
            "office_invoice", "office_proforma", "packing_shipment", "platting_issue", "purchase_order",
            "purchase_order_receive", "purchase_ord_brk_up", "salary", "salary_for_salary_department",
            "sample_bill", "sample_issue", "sample_receive", "skiving_bill", "skiving_receive_challan",
            "splitting_bill", "splitting_receive_challan", "stitching_bill", "stitching_rate", "stock_in",
            "supp_purchase_order", "temp_consumption_material_issue", "temp_invoice_hsn_code",
            "temp_leather_consumption", "temp_leather_consumption_for_comb", "temp_table"
        ];

        foreach ($tables_to_truncate as $table) {
            $this->db->query("TRUNCATE TABLE $table;");
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo "Error deleting data!";
        } else {
            echo "Data deleted successfully!";
        }
    
        
    }

    
}