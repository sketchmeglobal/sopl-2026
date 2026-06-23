<?php
class Bulk_edit_master_m extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }

    // Returns all data needed to render the page
    public function page_data() {
        $data = [];

        $data['item_groups'] = $this->db
            ->get_where('item_groups', array('status' => '1'))
            ->result_array();

        $data['customers'] = $this->db
            ->select('acc_master.am_id, acc_master.name')
            ->join('acc_groups', 'acc_groups.ag_id = acc_master.ag_id', 'left')
            ->where('acc_groups.group_name', 'Sundry Debtors')
            ->where('acc_master.status', '1')
            ->order_by('acc_master.name', 'ASC')
            ->get('acc_master')
            ->result_array();

        $data['customer_orders'] = $this->db
            ->select('co_id, co_no')
            ->where('status', '1')
            ->order_by('co_id', 'DESC')
            ->get('customer_order')
            ->result_array();

        $data['packing_lists'] = $this->db
            ->select('packing_shipment_id, package_name')
            ->where('status', '1')
            ->order_by('packing_shipment_id', 'DESC')
            ->get('packing_shipment')
            ->result_array();

        $data['sizes']  = $this->db->get_where('sizes',  array('status' => '1'))->result_array();
        $data['shapes'] = $this->db->get_where('shapes', array('status' => '1'))->result_array();
        $data['units']  = $this->db->get_where('units',  array('status' => '1'))->result_array();

        $data['article_groups'] = $this->db
            ->select('ag_id, group_name')
            ->get_where('article_groups', array('status' => '1'))
            ->result_array();

        return array('page' => 'bulk_edit_master/bulk_edit_master_v', 'data' => $data);
    }

    // Returns customer orders for a given customer (for dynamic filter chaining)
    public function get_customer_orders_by_customer() {
        $customer_id = $this->input->post('customer_id');

        $this->db->select('co_id, co_no');
        $this->db->where('status', '1');
        $this->db->order_by('co_id', 'DESC');

        if (!empty($customer_id)) {
            $this->db->where('acc_master_id', $customer_id);
        }

        return $this->db->get('customer_order')->result_array();
    }

    // Returns packing lists for a given customer order (for dynamic filter chaining)
    public function get_packing_lists_by_order() {
        $co_id = $this->input->post('co_id');

        if (!empty($co_id)) {
            $ps_rows = $this->db
                ->select('packing_shipment_id')
                ->where('co_id', $co_id)
                ->where('status', '1')
                ->group_by('packing_shipment_id')
                ->get('packing_shipment_detail')
                ->result_array();

            if (!empty($ps_rows)) {
                $ps_ids = array_column($ps_rows, 'packing_shipment_id');
                return $this->db
                    ->select('packing_shipment_id, package_name')
                    ->where_in('packing_shipment_id', $ps_ids)
                    ->where('status', '1')
                    ->order_by('packing_shipment_id', 'DESC')
                    ->get('packing_shipment')
                    ->result_array();
            }
            return [];
        }

        return $this->db
            ->select('packing_shipment_id, package_name')
            ->where('status', '1')
            ->order_by('packing_shipment_id', 'DESC')
            ->get('packing_shipment')
            ->result_array();
    }

    // Fetches item_master rows based on optional filter criteria
    public function get_filtered_items() {
        $master_type          = $this->input->post('master_type');
        $ig_id                = $this->input->post('ig_id');
        $customer_id          = $this->input->post('customer_id');
        $co_id                = $this->input->post('co_id');
        $packing_shipment_id  = $this->input->post('packing_shipment_id');

        if (!in_array($master_type, array('item_master', 'article_master'))) {
            return array('type' => 'error', 'msg' => 'Invalid master type.');
        }

        if ($master_type === 'article_master') {
            $this->db->select('
                article_master.am_id,
                article_master.ag_id,
                article_master.art_no,
                article_master.alt_art_no,
                article_master.info,
                article_master.size,
                article_master.leather_type,
                article_master.remark,
                article_master.exworks_amt,
                article_master.cf_amt,
                article_master.fob_amt,
                article_master.cutting_rate_b,
                article_master.fabrication_rate_b,
                article_master.status,
                article_groups.group_name,
                acc_master.name AS customer_name
            ');
            $this->db->from('article_master');
            $this->db->join('article_groups', 'article_groups.ag_id = article_master.ag_id', 'left');
            $this->db->join('acc_master',     'acc_master.am_id = article_master.customer_id', 'left');

            if (!empty($ig_id)) {
                $this->db->where('article_master.ag_id', $ig_id);
            }
            if (!empty($customer_id)) {
                $this->db->where('article_master.customer_id', $customer_id);
            }
            if (!empty($co_id)) {
                $coid = (int)$co_id;
                $this->db->where("article_master.am_id IN (SELECT am_id FROM customer_order_dtl WHERE co_id = {$coid} AND status = 1)");
            }
            if (!empty($packing_shipment_id)) {
                $psid = (int)$packing_shipment_id;
                $this->db->where("article_master.am_id IN (SELECT am_id FROM packing_shipment_detail WHERE packing_shipment_id = {$psid} AND status = 1)");
            }

            $this->db->order_by('article_groups.group_name, article_master.art_no');
            $rows = $this->db->get()->result_array();
            return array('type' => 'success', 'data' => $rows);
        }

        $this->db->select('
            item_master.im_id,
            item_master.im_code,
            item_master.item,
            item_master.ig_id,
            item_master.u_id,
            item_master.sz_id,
            item_master.sh_id,
            item_master.hsn_code,
            item_master.type,
            item_master.status,
            item_groups.group_name,
            units.unit,
            sizes.size,
            shapes.shape
        ');
        $this->db->from('item_master');
        $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
        $this->db->join('units',       'units.u_id = item_master.u_id',         'left');
        $this->db->join('sizes',       'sizes.sz_id = item_master.sz_id',       'left');
        $this->db->join('shapes',      'shapes.sh_id = item_master.sh_id',      'left');

        if (!empty($ig_id)) {
            $this->db->where('item_master.ig_id', $ig_id);
        }

        if (!empty($customer_id)) {
            $cid = (int)$customer_id;
            // Filter items whose item_group is used in articles belonging to this customer
            $this->db->where("item_master.ig_id IN (SELECT DISTINCT ap.ig_id FROM article_parts ap WHERE ap.am_id IN (SELECT am_id FROM article_master WHERE customer_id = {$cid}))");
        }

        if (!empty($co_id)) {
            $coid = (int)$co_id;
            $this->db->where("item_master.ig_id IN (SELECT ig_id FROM article_parts WHERE am_id IN (SELECT am_id FROM customer_order_dtl WHERE co_id = {$coid} AND status = 1))");
        }

        if (!empty($packing_shipment_id)) {
            $psid = (int)$packing_shipment_id;
            $this->db->where("item_master.ig_id IN (SELECT ig_id FROM article_parts WHERE am_id IN (SELECT am_id FROM packing_shipment_detail WHERE packing_shipment_id = {$psid} AND status = 1))");
        }

        $this->db->order_by('item_groups.group_name, item_master.im_code');

        $rows = $this->db->get()->result_array();
        return array('type' => 'success', 'data' => $rows);
    }

    // Updates a single item_master row
    public function update_item() {
        $im_id = $this->input->post('im_id');

        if (empty($im_id)) {
            return array('type' => 'error', 'msg' => 'Invalid item ID.');
        }

        $update_data = array(
            'ig_id'    => $this->input->post('ig_id'),
            'im_code'  => $this->input->post('im_code'),
            'item'     => $this->input->post('item'),
            'sz_id'    => $this->input->post('sz_id'),
            'sh_id'    => $this->input->post('sh_id'),
            'u_id'     => $this->input->post('u_id'),
            'hsn_code' => $this->input->post('hsn_code'),
            'type'     => $this->input->post('type'),
            'status'   => $this->input->post('status'),
            'user_id'  => $this->session->user_id,
        );

        $this->db->where('im_id', $im_id);
        $this->db->update('item_master', $update_data);

        if ($this->db->affected_rows() >= 0) {
            return array('type' => 'success', 'msg' => 'Item updated successfully.');
        }
        return array('type' => 'error', 'msg' => 'Update failed. Please try again.');
    }

    // Updates a single article_master row
    public function update_article() {
        $am_id = $this->input->post('am_id');

        if (empty($am_id)) {
            return array('type' => 'error', 'msg' => 'Invalid article ID.');
        }

        $update_data = array(
            'ag_id'              => $this->input->post('ag_id'),
            'art_no'             => $this->input->post('art_no'),
            'alt_art_no'         => $this->input->post('alt_art_no'),
            'info'               => $this->input->post('info'),
            'size'               => $this->input->post('size'),
            'leather_type'       => $this->input->post('leather_type'),
            'remark'             => $this->input->post('remark'),
            'exworks_amt'        => $this->input->post('exworks_amt'),
            'cf_amt'             => $this->input->post('cf_amt'),
            'fob_amt'            => $this->input->post('fob_amt'),
            'cutting_rate_b'     => $this->input->post('cutting_rate_b'),
            'fabrication_rate_b' => $this->input->post('fabrication_rate_b'),
            'status'             => $this->input->post('status'),
            'user_id'            => $this->session->user_id,
        );

        $this->db->where('am_id', $am_id);
        $this->db->update('article_master', $update_data);

        if ($this->db->affected_rows() >= 0) {
            return array('type' => 'success', 'msg' => 'Article updated successfully.');
        }
        return array('type' => 'error', 'msg' => 'Update failed. Please try again.');
    }
}
