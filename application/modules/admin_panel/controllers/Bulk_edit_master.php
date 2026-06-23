<?php
class Bulk_edit_master extends My_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('Bulk_edit_master_m');
        $data = $this->Bulk_edit_master_m->page_data();
        $this->load->view($data['page'], $data['data']);
    }

    // AJAX: fetch customer orders filtered by customer
    public function ajax_get_customer_orders() {
        $this->load->model('Bulk_edit_master_m');
        echo json_encode($this->Bulk_edit_master_m->get_customer_orders_by_customer(), JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    // AJAX: fetch packing lists filtered by customer order
    public function ajax_get_packing_lists() {
        $this->load->model('Bulk_edit_master_m');
        echo json_encode($this->Bulk_edit_master_m->get_packing_lists_by_order(), JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    // AJAX: get items/articles based on filters
    public function ajax_get_filtered_items() {
        $this->load->model('Bulk_edit_master_m');
        echo json_encode($this->Bulk_edit_master_m->get_filtered_items(), JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    // AJAX: save a single item row update
    public function ajax_update_item() {
        $this->load->model('Bulk_edit_master_m');
        echo json_encode($this->Bulk_edit_master_m->update_item(), JSON_HEX_QUOT | JSON_HEX_TAG);
    }

    // AJAX: save a single article_master row update
    public function ajax_update_article() {
        $this->load->model('Bulk_edit_master_m');
        echo json_encode($this->Bulk_edit_master_m->update_article(), JSON_HEX_QUOT | JSON_HEX_TAG);
    }
}
