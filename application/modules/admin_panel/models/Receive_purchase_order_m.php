<?php


class Receive_purchase_order_m extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }
    
    private function _user_wise_view_permission($menu_id,$user){

         $nr = $this->db
                ->where('m_id', $menu_id)
                ->where('user_id', $user)
                ->where('view_permission', 0) #0 -> crud inactive
                ->get('user_permission')->num_rows();
        // echo $this->db->last_query(); die;
        if($nr == 0){
            return 'show';
        }else{
            return 'block';
        }
        
    }

    public function log_before_update($post_array,$primary_key, $table_name){
        $insertArray = array(
            'table_name' => $table_name,
            'pk_id' => $primary_key,
            'action_taken'=>'edit', 
            'old_data' => json_encode($post_array),
            'user_id' => $this->session->user_id,
            'comment' => 'purchase order receive'
        );
        if($this->db->insert('user_logs', $insertArray)){
            return true;
        }else{
            return false;
        }
    }

    public function check_and_log_before_delete($reference_array, $primary_key, $pk_field_name, $table_name){
        // echo $table_name . ' || ' . $pk_field_name . ' || ' . $primary_key;die;
        // $item_exists = 0;
        foreach($reference_array as $ra){
            $nr = $this->db->get_where($ra['tbl_name'], array($ra['tbl_pk_fld'] => $primary_key))->num_rows();
            if($nr > 0){
                $item_exists = 1;
            }
        }
        // print_r($this->reference_array);die;        

        if($item_exists == 0){
            return false;
        } else{
            $user_data = $this->db->where($pk_field_name, $primary_key)->get($table_name)->row();
            $insertArray = array(
                'table_name' => $table_name,
                'pk_id' => $primary_key,
                'action_taken'=>'delete', 
                'old_data' => json_encode($user_data),
                'user_id' => $this->session->user_id,
                'comment' => 'purchase order receive'
            );
            if($this->db->insert('user_logs', $insertArray)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function receive_purchase_order() {
        $data = [];
        $data["view_permission"] = $this->_user_wise_view_permission(18, $this->session->user_id);
        $data['po_dtl_items'] = $this->db->distinct()
            ->select('item_master.item, item_dtl.id_id, colors.color')
            ->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left')
            ->join('item_master', 'item_master.im_id=item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id=item_dtl.c_id', 'left')
            ->get_where('purchase_order_receive_detail')->result();
            
        $data['item_groups'] = $this->db->get('item_groups')->result();
        return array('page'=>'receive_purchase_order/receive_purchase_order_list_v', 'data'=>$data);
    }

    
    
    public function ajax_receive_purchase_order_table_data() {
    
    //actual db table column names
    $column_orderable = array(
        0 => 'purchase_order_receive_bill_no',
        1 => 'purchase_order_receive_date',
        2 => 'po_numbers',
        3 => 'total_amount',
        4 => 'total_delivery_charges',
        5 => 'net_amount'
    );
    // Set searchable column fields
    $column_search = array('purchase_order_receive_bill_no', 'acc_master.name', 'purchase_order_receive_date', 'total_amount', 'total_delivery_charges','net_amount');

    $limit = $this->input->post('length');
    $start = $this->input->post('start');
    
    $order = $column_orderable[$this->input->post('order')[0]['column']];
    $dir = $this->input->post('order')[0]['dir'];
    $search = $this->input->post('search')['value'];

    $rs = $this->db->get('purchase_order_receive')->result();
    $totalData = count($rs);
    $totalFiltered = $totalData;

    $data = array();
    
    // for top filter
    $pur_rcv_id_id = $this->input->post('pur_rcv_id_id');
    
    if($pur_rcv_id_id != ''){
        
        $this->db->order_by($order, $dir);
        $this->db->select('GROUP_CONCAT(DISTINCT purchase_order.po_number SEPARATOR ", ") as po_numbers, purchase_order_receive.purchase_order_receive_id, purchase_order_receive.purchase_order_receive_bill_no, 
        DATE_FORMAT(purchase_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, purchase_order_receive.am_id, 
        purchase_order_receive.total_amount, purchase_order_receive.total_delivery_charges, purchase_order_receive.net_amount, purchase_order_receive.status, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, payment_status');
        $this->db->join('acc_master', 'acc_master.am_id = purchase_order_receive.am_id', 'left');
        $this->db->join('purchase_order_receive_detail', 'purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive.purchase_order_receive_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
        $this->db->where('purchase_order_receive_detail.id_id', $pur_rcv_id_id);
        $this->db->group_by('purchase_order_receive.purchase_order_receive_id');
        $rs = $this->db->get('purchase_order_receive')->result();
            
        // echo $this->db->last_query(); die;
        
        $totalData = count($rs);
        $totalFiltered = count($rs);    
        
    } else{
        
        // ORIGINAL DATATABLE
        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('GROUP_CONCAT(DISTINCT purchase_order.po_number SEPARATOR ", ") as po_numbers, purchase_order_receive.purchase_order_receive_id, purchase_order_receive.purchase_order_receive_bill_no, 
            DATE_FORMAT(purchase_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, purchase_order_receive.am_id, 
            purchase_order_receive.total_amount, purchase_order_receive.total_delivery_charges, purchase_order_receive.net_amount, purchase_order_receive.status, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, payment_status');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order_receive.am_id', 'left');
            $this->db->join('purchase_order_receive_detail', 'purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->where('purchase_order_receive.status', 1);
            $this->db->group_by('purchase_order_receive.purchase_order_receive_id');
            $rs = $this->db->get('purchase_order_receive')->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('GROUP_CONCAT(DISTINCT purchase_order.po_number SEPARATOR ", ") as po_numbers, purchase_order_receive.purchase_order_receive_id, purchase_order_receive.purchase_order_receive_bill_no, 
            DATE_FORMAT(purchase_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, purchase_order_receive.am_id, 
            purchase_order_receive.total_amount, purchase_order_receive.total_delivery_charges, purchase_order_receive.net_amount,  purchase_order_receive.status, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, payment_status');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order_receive.am_id', 'left');
            $this->db->join('purchase_order_receive_detail', 'purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->where('purchase_order_receive.status', 1);
            $this->db->group_by('purchase_order_receive.purchase_order_receive_id');
            $rs = $this->db->get('purchase_order_receive')->result();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('GROUP_CONCAT(DISTINCT purchase_order.po_number SEPARATOR ", ") as po_numbers, purchase_order_receive.purchase_order_receive_id, purchase_order_receive.purchase_order_receive_bill_no, 
            DATE_FORMAT(purchase_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, purchase_order_receive.am_id, 
            purchase_order_receive.total_amount, purchase_order_receive.total_delivery_charges, purchase_order_receive.net_amount,  purchase_order_receive.status, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, payment_status');
            $this->db->join('acc_master', 'acc_master.am_id = purchase_order_receive.am_id', 'left');
            $this->db->join('purchase_order_receive_detail', 'purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->where('purchase_order_receive.status', 1);
            $this->db->group_by('purchase_order_receive.purchase_order_receive_id');
            $rs = $this->db->get('purchase_order_receive')->result();
            $this->db->flush_cache();
        }
        
    }
    
    // echo '<pre>', print_r($rs), '</pre>'; die;
    // echo $this->db->last_query();die;
    
    foreach ($rs as $val) {

        if($val->status == '1'){$status='Enable';} else{$status='Disable';}

        $nestedData['purchase_order_receive_bill_no'] = $val->purchase_order_receive_bill_no;
        $nestedData['purchase_order_receive_date'] = $val->purchase_order_receive_date;
        $nestedData['po_number'] = $val->po_numbers;
        $nestedData['pur_order_supplier'] = $val->acc_master_name.'['.$val->acc_master_short_name.']';
        $nestedData['total_amount'] = $val->total_amount;
        $nestedData['delivery_charge'] = $val->total_delivery_charges;
        $nestedData['net_amount'] = $val->net_amount;			
        $nestedData['status'] = $status;
        
        $uvp = $this->_user_wise_view_permission(18, $this->session->user_id);
        if($uvp == 'block'){
            $nestedData['action'] = '-';    
        }else{
            $nestedData['action'] = '<a href="'. base_url('admin/edit-receive-purchase-order/'.$val->purchase_order_receive_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a target="_blank" href="'. base_url('admin/purchase-bill-rate-setup/'.$val->purchase_order_receive_id) .'" class="btn btn-primary"><i class="fa fa-print"></i> Rate History </a>
            <a href="javascript:void(0)" pk-name="purchase_order_receive_id" pk-value="'.$val->purchase_order_receive_id.'" tab="purchase_order_receive" ref-tab="purchase_order_receive_detail" child="1" class="btn btn-danger delete1"><i class="fa fa-times"></i> Delete</a>';
        }
        
        // block admin
        if($this->session->usertype != 1){
            if($val->payment_status == 0){
                $nestedData['action'] .= '&nbsp;<a title="Payment is done" href="javascript:void(0)" pk-value="'.$val->purchase_order_receive_id.'" class="btn btn-success payment"><i class="fa fa-check"></i> Paid</a>';
            } else{
                $nestedData['action'] .= '&nbsp;<a title="Revoke Payment" href="javascript:void(0)" pk-value="'.$val->purchase_order_receive_id.'" class="btn btn-warning payment"><i class="fa fa-times"></i> Revoke</a>';
            }
        }
        $data[] = $nestedData;

        // echo '<pre>', print_r($rs), '</pre>'; 
    }

    $json_data = array(
        "draw"            => intval($this->input->post('draw')),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $data
    );

    return $json_data;
}
    

    public function purchase_bill_rate_setup_m($purchase_order_id){

        $data = '';

        $new_purchase_array = array();

        $get_all_id = $this->db->select('purchase_order_receive_detail.*')->group_by('purchase_order_receive_detail.id_id')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_id))->result();

        if(count($get_all_id) > 0) {

        foreach($get_all_id as $g_a_i) {
        
        $this->db->select('purchase_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order_receive.purchase_order_receive_bill_no, purchase_order_receive.purchase_order_receive_date, item_rates.purchase_rate, item_rates.cost_rate, purchase_order_receive.am_id');
        $this->db->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left');
        $this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('item_rates', 'item_rates.id_id = item_dtl.id_id', 'left');
        $this->db->order_by('purchase_order_receive.purchase_order_receive_date', 'desc');
        $this->db->limit(3);
        $pord_receive_details = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail.id_id' => $g_a_i->id_id))->result();

        foreach($pord_receive_details as $p_r_d) {
        $arr = array(
         'am_id' => $p_r_d->am_id,
         'purchase_order_receive_id' => $p_r_d->purchase_order_receive_id,
         'id_id' => $p_r_d->id_id,
         'purchase_bill_no' => $p_r_d->purchase_order_receive_bill_no,
         'item_name' => $p_r_d->item,
         'color' => $p_r_d->color,
         'created_date' => $p_r_d->purchase_order_receive_date,
         'purchase_rate' => $p_r_d->item_rate,
         'item_purchase_rate' => $p_r_d->purchase_rate,
         'item_cost_rate' => $p_r_d->cost_rate
        );

       array_push($new_purchase_array, $arr);

    }

    }

        $data['segment'] = 'purchase_order_rate_setup_details';        
        $data['purchase_array'] = $new_purchase_array;

        return array('page'=>'reports/common_print_v', 'data'=>$data);

    } else {

          die('No data to show.');

    }

    }

	// ADD supp.purchase ORDER 

    public function add_receive_purchase_order() {
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();
        return array('page'=>'receive_purchase_order/receive_purchase_order_add_v', 'data'=>$data);
    }

    public function ajax_unique_supp_purchase_order_no(){
        $supp_po_number = $this->input->post('supp_po_number');

        $rs = $this->db->get_where('supp_purchase_order', array('supp_po_number' => $supp_po_number))->num_rows();
        if($rs != '0') {
            $data = 'Supp.Purchase order no already exists.';
        }else{
            $data='true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_receive_purchase_order(){

        $insertArray = array(
            'purchase_order_receive_bill_no' => $this->input->post('purchase_order_receive_bill_no'),
            'purchase_order_receive_date' => $this->input->post('purchase_order_receive_date'),
            'am_id' => $this->input->post('am_id'),
            'user_id' => $this->session->user_id
        );

        // echo '<pre>', print_r($insertArray), '</pre>';die;

        $this->db->insert('purchase_order_receive', $insertArray);
        $data['insert_id'] = $this->db->insert_id();
		if($this->db->insert_id() > 0){
			$data['type'] = 'success';
			$data['msg'] = 'Receive order added successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Not Inserted successfully.';
		}
        return $data;
    }

    public function edit_receive_purchase_order($purchase_order_receive_id) {
        $data['item_groups'] = $this->db->select('ig_id, ig_code, group_name')
            ->get_where('item_groups', array('item_groups.status' => 1))->result_array();
    
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')
            ->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();
    
        $data['receive_purchase_order_details'] = $this->db
            ->select('purchase_order_receive.purchase_order_receive_id, 
                      purchase_order_receive.purchase_order_receive_bill_no, 
                      DATE_FORMAT(purchase_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, 
                      purchase_order_receive.am_id, 
                      purchase_order_receive.total_amount, 
                      purchase_order_receive.total_delivery_charges, 
                      purchase_order_receive.net_amount,  
                      purchase_order_receive.status, 
                      acc_master.name as acc_master_name, 
                      acc_master.short_name as acc_master_short_name, 
                      (SELECT SUM(pod_cgst_amount) FROM purchase_order_receive_detail 
                       WHERE purchase_order_receive_id = '.$purchase_order_receive_id.') AS total_cgst_amount,
                      (SELECT SUM(pod_sgst_amount) FROM purchase_order_receive_detail 
                       WHERE purchase_order_receive_id = '.$purchase_order_receive_id.') AS total_sgst_amount')
            ->join('acc_master', 'acc_master.am_id = purchase_order_receive.am_id', 'left')
            ->get_where('purchase_order_receive', array(
                'purchase_order_receive.purchase_order_receive_id' => $purchase_order_receive_id
            ))->result();
    
        $am_id = $data['receive_purchase_order_details'][0]->am_id;
    
        // Challan bill list for dropdown
        $data['challan_orders'] = $this->db
            ->select('purchase_challan_order_receive.purchase_order_receive_id, 
                      purchase_challan_order_receive.purchase_order_receive_bill_no')
            ->get_where('purchase_challan_order_receive', array(
                'purchase_challan_order_receive.am_id'  => $am_id,
                'purchase_challan_order_receive.status' => 1
            ))->result_array();
    
        // ── REMOVED: supp_purchase_order no longer needed ──
        $data['supp_purchase_order'] = array();
        $data['purchase_order']      = array();
    
        return array(
            'page' => 'receive_purchase_order/receive_purchase_order_edit_v',
            'data' => $data
        );
    }

    public function form_edit_receive_purchase_order(){

        $old_array = $this->db->get_where('purchase_order_receive', array('purchase_order_receive_id' => $this->input->post('purchase_order_receive_id')))->row();
        // echo '<pre>', print_r($old_array), '</pre>'; die();
        $this->log_before_update($old_array, $this->input->post('purchase_order_receive_id'), 'purchase_order_receive');

        $billno = $this->input->post('purchase_order_receive_bill_no');
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');

        $duplicate_po = $this->db->get_where('purchase_order_receive', array('purchase_order_receive_bill_no' => $billno, 'purchase_order_receive_id !=' => $purchase_order_receive_id))->num_rows();
        if($duplicate_po > 0){
            $data['type'] = 'warning';
            $data['msg'] = 'Receive Purchase order already exists.';    
        } else{
            $updateArray = array(
                'purchase_order_receive_bill_no' => $this->input->post('purchase_order_receive_bill_no'),
                'purchase_order_receive_date' => $this->input->post('purchase_order_receive_date'),
                'am_id' => $this->input->post('am_id_hidden'),
                'status' => $this->input->post('status'),
                'user_id' => $this->session->user_id
            );
            $this->db->update('purchase_order_receive', $updateArray, array('purchase_order_receive_id' => $purchase_order_receive_id));
            $data['type'] = 'success';
            $data['msg'] = 'Receive Purchase order updated successfully.';
        }
        
        return $data;

    }

    public function ajax_receive_purchase_order_details_table_data() {
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
		//actual db table column names
        $column_orderable = array(
			0 => 'id_id',
            2 => 'item_quantity',
            3 => 'item_rate',
			4 => 'pod_total'
        );
        // Set searchable column fields
        $column_search = array('item_qty', 'item_rate', 'pod_total');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('purchase_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('purchase_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
            // echo $this->db->get_compiled_select('purchase_order_details');
            // exit();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('purchase_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
            // echo $this->db->get_compiled_select('purchase_order_details');
            // exit();
        

            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
             $this->db->select('purchase_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();

            $this->db->flush_cache();
        }

        $data = array();

        //echo '<pre>', print_r($rs), '</pre>'; die;
        

        foreach ($rs as $val) {

            $nestedData['po_number'] = $val->po_number;
            $nestedData['sup_po_number'] = $val->supp_po_number;
            $nestedData['item_name'] = $val->item;
            $nestedData['item_color'] = $val->color . ' ['. $val->c_code .']';
            $nestedData['item_qty'] = $val->item_quantity;
            $nestedData['item_rate'] = $val->item_rate;
            $nestedData['total_amount'] = ($val->item_quantity * $val->item_rate);
            $nestedData['total_tax_rate'] = ($val->pod_cgst_percentage + $val->pod_sgst_percentage);
            $nestedData['total_tax'] = ($val->pod_cgst_amount + $val->pod_sgst_amount);
            $nestedData['net_amount'] = $val->pod_total;
            $nestedData['receive_date'] = $val->receive_date;
            $pod_total_add = $val->pod_total;
			
            $nestedData['action'] = '<a href="javascript:void(0)" purchase_order_receive_detail_id="'.$val->purchase_order_receive_detail_id.'" class="purchase_order_receive_detail_id btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a tab="purchase_order_receive_detail" tab-pk="purchase_order_receive_detail_id" data-pk="'.$val->purchase_order_receive_detail_id.'" reference-tab="purchase_order_receive" reference-pk="purchase_order_receive_id" reference-data-pk="'.$purchase_order_receive_id.'" pod-total-add="'.$pod_total_add.'" href="javascript:void(0)" class="btn btn-danger delete1"><i class="fa fa-times"></i> Delete</a>';
            
            $data[] = $nestedData;

            // echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }
	
	public function ajax_fetch_receive_purchase_order_details_on_pk(){
        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');
		$data = array();
		
		$this->db->select('purchase_order_receive_detail.*, purchase_order.po_number, delivery_charges, supp_purchase_order.supp_po_number, colors.color, colors.c_code, item_master.item, units.unit');
		$this->db->join('purchase_order', 'purchase_order.po_id = purchase_order_receive_detail.po_id', 'left');
		$this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_order_receive_detail.sup_id', 'left');
		$this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
		$this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
		$this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
		$this->db->join('units', 'units.u_id = item_master.u_id', 'left');
		$oreder_receive_details = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id))->result_array()[0];
       //echo $this->db->last_query();die;

		
		$id_id_add = $oreder_receive_details['id_id'];
		$item_quantity = $this->db->select_sum('item_quantity')->get_where('purchase_order_receive_detail', array('id_id' => $id_id_add))->result()[0]->item_quantity;
		
		$data['oreder_receive_details'] = $oreder_receive_details;
		$data['remain_item_quantity'] = $item_quantity;
		
		return $data;
    }

    public function all_items_on_purchase_order(){
		$data = array();
		
        $po_id = $this->input->post('po_id');
		
		$this->db->select('purchase_order_details.id_id, purchase_order_details.pod_quantity, purchase_order_details.pod_rate, purchase_order_details.pod_total, item_master.item as item_name, item_master.im_code, units.unit, colors.color');
		$this->db->join('item_dtl', 'item_dtl.id_id = purchase_order_details.id_id', 'left');
		$this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
		$this->db->join('units', 'units.u_id = item_master.u_id', 'left');
		$this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
		$data['all_items'] = $this->db->get_where('purchase_order_details', array('purchase_order_details.status'=>'1', 'purchase_order_details.po_id' => $po_id))->result_array();
		
		$am_id = $this->input->post('am_id_hidden');
		
		$sup_num_rows = $this->db->get_where('supp_purchase_order', array('am_id' => $am_id, 'po_id' => $po_id, 'supp_status' => 1))->num_rows();
		if($sup_num_rows > 0){
			$data['sup_po_orders'] = $this->db->select('sup_id, supp_po_number')->get_where('supp_purchase_order', array('am_id' => $am_id, 'po_id' => $po_id, 'supp_status' => 1))->result_array();
		}else{
			$data['sup_po_orders'] = array();
		}
		
		return $data;
    }
	
	public function all_items_on_supp_purchase_order(){
        $sup_id = $this->input->post('sup_id');
		
		$this->db->select('supp_purchase_order_detail.id_id, supp_purchase_order_detail.item_qty as pod_quantity, supp_purchase_order_detail.item_rate as pod_rate, supp_purchase_order_detail.total_amount as pod_total, item_master.item as item_name, item_master.im_code, units.unit, colors.color');
		$this->db->join('item_dtl', 'item_dtl.id_id = supp_purchase_order_detail.id_id', 'left');
		$this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
		$this->db->join('units', 'units.u_id = item_master.u_id', 'left');
		$this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
		return $this->db->get_where('supp_purchase_order_detail', array('supp_purchase_order_detail.status'=>'1', 'supp_purchase_order_detail.sup_id' => $sup_id))->result_array();
		
    }
    
    public function ajax_all_colors_on_item_master(){
        $item_id = $this->input->post('item_id');
        $this->db->select('item_dtl.id_id as item_dtl_id, colors.*');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('item_dtl', array('item_dtl.status'=>'1', 'item_dtl.im_id' => $item_id, 'color <>' => null))->result_array();
    }
	
    public function ajax_get_remaining_item_quantity(){
        $id_id_add = $this->input->post('id_id_add');
		$po_id = $this->input->post('po_id');
        $sup_id = $this->input->post('sup_id');
		
		$item_quantity = 0;
		
        if($sup_id == '' || $sup_id == null) {
        $item_quantity1 = $this->db->select_sum('item_quantity')->get_where('purchase_order_receive_detail', array('id_id' => $id_id_add, 'po_id' => $po_id))->result()[0]->item_quantity;
		if($item_quantity1 > 0){
			$item_quantity = $item_quantity1;	
		}
    } else {
        $item_quantity1 = $this->db->select_sum('item_quantity')->get_where('purchase_order_receive_detail', array('id_id' => $id_id_add, 'po_id' => $po_id, 'sup_id' => $sup_id))->result()[0]->item_quantity;
        if($item_quantity1 > 0){
            $item_quantity = $item_quantity1;   
        }
    }
		
		return $item_quantity;
		
    }
	
	public function ajax_all_purchase_order(){
		$data = array();
        $pur_order_date = $this->input->post('pur_order_date');
		$rs = $this->db->get_where('purchase_order', array('purchase_order.po_date >= ' => $pur_order_date, 'purchase_order.status' => '1'))->num_rows();
		$all_po = $this->db->get_where('purchase_order', array('purchase_order.po_date >= ' => $pur_order_date, 'purchase_order.status' => '1'))->result_array();
		
		if($rs > 0){
			$data['status'] = true;
			$data['all_po'] = $all_po;
		}else{
			$data['status'] = false;
			$data['message'] = 'No Purchase order available';
			$data['all_po'] = array();
		}
		return $data;
    }
	
    public function form_add_receive_purchase_order_details(){
        
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
        $line_total = ($this->input->post('pod_quantity_add') * $this->input->post('pod_rate_add')) + $this->input->post('pod_delivery_charges');
        $line_cgst_amount = $line_total * ($this->input->post('pod_cgst_percentage')/100);
        $line_sgst_amount = $line_total * ($this->input->post('pod_sgst_percentage')/100);
        
        $insertArray = array(
            'purchase_order_receive_id' => $this->input->post('purchase_order_receive_id'),
            'po_id' => $this->input->post('po_id'), // item_dtl_id as color
            'sup_id'           => $this->input->post('sup_id'),
            'challan_detail_id'=> (int)$this->input->post('challan_detail_id'), // ← ADD
            'id_id'            => $this->input->post('id_id_add'),
        	'item_quantity' => $this->input->post('pod_quantity_add'),
			'item_rate' => $this->input->post('pod_rate_add'),
			'delivery_charges' => $this->input->post('pod_delivery_charges'),
            'pod_cgst_percentage' => $this->input->post('pod_cgst_percentage'),
            'pod_cgst_amount' => $line_cgst_amount,
            'pod_sgst_percentage' => $this->input->post('pod_sgst_percentage'),
            'pod_sgst_amount' => $line_sgst_amount,
            'pod_total' => $this->input->post('pod_total_add'),
            'receive_date' => $this->input->post('rcv_date_detail'),
            'remarks' => $this->input->post('sup_pod_remarks'),
            'user_id' => $this->session->user_id
        );
        // echo '<pre>', print_r($insertArray), '</pre>';die;
        $this->db->insert('purchase_order_receive_detail', $insertArray);
		$insert_id = $this->db->insert_id();
        $data['insert_id'] = $this->db->insert_id();
		
		
		// Update header table
        $pod_total_amount = $this->db->select('SUM(item_quantity * item_rate) AS pod_total')
            ->where('purchase_order_receive_id', $purchase_order_receive_id)
            ->get('purchase_order_receive_detail')->row()->pod_total;
        $delivery_charge = $this->db->select_sum('delivery_charges')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->row()->delivery_charges;
        $pod_cgst_amount = $this->db->select_sum('pod_cgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->row()->pod_cgst_amount;
        $pod_sgst_amount = $this->db->select_sum('pod_sgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $this->input->post('purchase_order_receive_id')))->row()->pod_sgst_amount;
        
        $updateHeaderArray = array(
            'total_amount' => $pod_total_amount,
            'total_delivery_charges' => $delivery_charge,
            'total_cgst_amount' => $pod_cgst_amount,
            'total_sgst_amount' => $pod_sgst_amount,
            'net_amount' => ($pod_total_amount + $delivery_charge + $pod_cgst_amount + $pod_sgst_amount )
        );
        $this->db->update('purchase_order_receive', $updateHeaderArray, array('purchase_order_receive_id' => $purchase_order_receive_id));
        
		if($insert_id > 0){
			$data['type'] = 'success';
			$data['msg'] = 'Receive purchase order details added successfully.';
			$data['line_items'] = $updateHeaderArray;
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Receive purchase order details not added successfully.';
		}
        // echo '<pre>', print_r($data), '</pre>';die;
        return $data;
    }
	

    public function ajax_fetch_supp_purchase_order_details_on_pk(){
        $supp_dtl_id = $this->input->post('supp_dtl_id');
        return $this->db
            ->select('supp_purchase_order.*, supp_purchase_order_detail.*, acc_master.name, acc_master.address,countries.country,item_master.item,colors.color, colors.c_code, units.unit,item_groups.ig_id as item_group, item_groups.group_name, thick')
            ->join('supp_purchase_order', 'supp_purchase_order_detail.sup_id = supp_purchase_order.sup_id', 'left') // 
            ->join('acc_master', 'acc_master.am_id = supp_purchase_order.am_id', 'left')
            ->join('countries', 'countries.c_id = acc_master.c_id', 'left')
            ->join('item_dtl', 'supp_purchase_order_detail.id_id = item_dtl.id_id', 'left')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left')
            ->join('units', 'units.u_id = item_groups.u_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->get_where('supp_purchase_order_detail', array('supp_purchase_order_detail.supp_dtl_id' => $supp_dtl_id))->result();
        // echo $this->db->get_compiled_select('purchase_order_details');
    }

    public function purchase_order_print_with_code($po_id){
        
        $data['purchase_order_details'] = $this->db
                ->select('purchase_order.*, purchase_order_details.*, acc_master.name, acc_master.address,countries.country,item_master.item,colors.color, colors.c_code, units.unit,item_groups.ig_id as item_group, thick')
                ->join('purchase_order_details', 'purchase_order_details.po_id = purchase_order.po_id', 'left') // 
                ->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left')
                ->join('countries', 'countries.c_id = acc_master.c_id', 'left')
                ->join('item_dtl', 'purchase_order_details.id_id = item_dtl.id_id', 'left')
                ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                ->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left')
                ->join('units', 'units.u_id = item_groups.u_id', 'left')
                ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                ->get_where('purchase_order', array('purchase_order.po_id' => $po_id))
                ->result();
        return array('page'=>'purchase_order/purchase_order_print_with_code_v', 'data'=>$data);
    }

    public function purchase_order_print_without_code($po_id){
        
        $data['purchase_order_details'] = $this->db
                ->select('purchase_order.*, purchase_order_details.*, acc_master.name, acc_master.address,countries.country,item_master.item,colors.color, colors.c_code, units.unit,item_groups.ig_id as item_group, thick')
                ->join('purchase_order_details', 'purchase_order_details.po_id = purchase_order.po_id', 'left') // 
                ->join('acc_master', 'acc_master.am_id = purchase_order.am_id', 'left')
                ->join('countries', 'countries.c_id = acc_master.c_id', 'left')
                ->join('item_dtl', 'purchase_order_details.id_id = item_dtl.id_id', 'left')
                ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                ->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left')
                ->join('units', 'units.u_id = item_groups.u_id', 'left')
                ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                ->get_where('purchase_order', array('purchase_order.po_id' => $po_id))
                ->result();
        return array('page'=>'purchase_order/purchase_order_print_without_code_v', 'data'=>$data);
    }


    public function form_edit_receive_purchase_order_details(){
        
        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
        
        // Log old data
        $old_array = $this->db->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail_id' => $this->input->post('purchase_order_receive_detail_id')))->result();
        $this->log_before_update($old_array, $this->input->post('purchase_order_receive_detail_id'), 'purchase_order_receive_detail');
        
        // Update detail table
        $line_total = ($this->input->post('pod_quantity_edit') * $this->input->post('pod_rate_edit')) + $this->input->post('pod_delivery_charges_edit');
        $line_cgst_amount = $line_total * ($this->input->post('pod_cgst_percentage_edit')/100);
        $line_sgst_amount = $line_total * ($this->input->post('pod_sgst_percentage_edit')/100);
        
        // Round all calculated values to 2 decimal places
        $line_total = round($line_total, 2);
        $line_cgst_amount = round($line_cgst_amount, 2);
        $line_sgst_amount = round($line_sgst_amount, 2);
        $pod_total = round($line_total + ($line_cgst_amount + $line_sgst_amount), 2);
        
        $updateArray = array(
            'item_quantity' => $this->input->post('pod_quantity_edit'),
            'item_rate' => round($this->input->post('pod_rate_edit'), 2),
            'delivery_charges' => round($this->input->post('pod_delivery_charges_edit'), 2),
            'pod_cgst_percentage' => $this->input->post('pod_cgst_percentage_edit'),
            'pod_cgst_amount' => $line_cgst_amount,
            'pod_sgst_percentage' => $this->input->post('pod_sgst_percentage_edit'),
            'pod_sgst_amount' => $line_sgst_amount,
            'pod_total' => $pod_total,
            'receive_date' => $this->input->post('rcv_date_detail_edit'),
            'remarks' => $this->input->post('sup_pod_remarks_edit'),
            'user_id' => $this->session->user_id
        );
        $this->db->update('purchase_order_receive_detail', $updateArray, array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id));
        
        // Update header table
        $pod_total_amount = $this->db->select('SUM(item_quantity * item_rate) AS pod_total')
            ->where('purchase_order_receive_id', $purchase_order_receive_id)
            ->get('purchase_order_receive_detail')->row()->pod_total;
        $delivery_charge = $this->db->select('SUM(delivery_charges) AS delivery_charges')
            ->where('purchase_order_receive_id', $purchase_order_receive_id)
            ->get('purchase_order_receive_detail')->row()->delivery_charges;
        $pod_cgst_amount = $this->db->select_sum('pod_cgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->row()->pod_cgst_amount;
        $pod_sgst_amount = $this->db->select_sum('pod_sgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $this->input->post('purchase_order_receive_id')))->row()->pod_sgst_amount;
        
        // Round all header amounts to 2 decimal places
        $pod_total_amount = round($pod_total_amount, 2);
        $delivery_charge = round($delivery_charge, 2);
        $pod_cgst_amount = round($pod_cgst_amount, 2);
        $pod_sgst_amount = round($pod_sgst_amount, 2);
        $net_amount = round(($pod_total_amount + $delivery_charge + $pod_cgst_amount + $pod_sgst_amount));
        
        $updateHeaderArray = array(
            'total_amount' => $pod_total_amount,
            'total_delivery_charges' => $delivery_charge,
            'total_cgst_amount' => $pod_cgst_amount,
            'total_sgst_amount' => $pod_sgst_amount,
            'net_amount' => $net_amount
        );
        // print_r($updateHeaderArray); die;
        $this->db->update('purchase_order_receive', $updateHeaderArray, array('purchase_order_receive_id' => $purchase_order_receive_id));
        
        $data['type'] = 'success';
        $data['line_items'] = $updateHeaderArray;
        $data['msg'] = 'Receive purchase order details updated successfully.';
        // print_r($data); die;
        return $data;
    }
	
	public function form_edit_delivery_sgst_cgst_value(){
        
		$purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
		
        $updateArray = array(
            'total_amount' => $this->input->post('total_amount'),
            'delivery_charge' => $this->input->post('delivery_charge'),
			'net_amount' => $this->input->post('net_amount'),
            'user_id' => $this->session->user_id
        );
		
		$this->db->update('purchase_order_receive', $updateArray, array('purchase_order_receive_id' => $purchase_order_receive_id));

        $data['type'] = 'success';
        $data['msg'] = 'Net Amount updated successfully.';
        return $data;
    }
    

    

    public function ajax_unique_purchase_order_number() {
        $order_no = $this->input->post('order_no');
        $rs = $this->db->get_where('purchase_order', array('co_no' => $order_no))->num_rows();
        // echo $this->db->last_query();die;
        
        if($rs != '0') {
            $data = 'Order no. already exists.';
        }else{
            $data='true';
        }

        return $data;
    }

    public function delete_receive_purchase_order_details(){
        $tab = $this->input->post('tab');
	    $ref_table = $this->input->post('ref_tab');
		$pk_name = $this->input->post('pk_name');
		$pk_value = $this->input->post('pk_value');

        $primary_key = $this->input->post('pk_value');
        $table_name = $this->input->post('tab');
        $pk_field_name = $this->input->post('pk_name');
             // reference table values for checking  
             $reference_array = array(
                array(
                    "tbl_name" => $this->input->post('tab'),
                    "tbl_pk_fld" => $this->input->post('pk_name'),
                )
            );

       $this->check_and_log_before_delete($reference_array, $primary_key, $pk_field_name, $table_name);

        $this->db->where($pk_name, $pk_value)->delete($tab);
        $this->db->where($pk_name, $pk_value)->delete($ref_table);
		
		
        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Rceive Purchase Order Successfully Deleted';
        return $data;
    }
	
	public function delete_receive_purchase_order_details_list(){

        $primary_key = $this->input->post('data_pk');
        $table_name = $this->input->post('tab');
        $pk_field_name = $this->input->post('tab_pk');
             // reference table values for checking  
             $reference_array = array(
                array(
                    "tbl_name" => $this->input->post('tab'),
                    "tbl_pk_fld" => $this->input->post('tab_pk'),
                )
            );

        $this->check_and_log_before_delete($reference_array, $primary_key, $pk_field_name, $table_name);

        $tab = $this->input->post('tab');
		$tab_pk = $this->input->post('tab_pk');
		$data_pk = $this->input->post('data_pk');
		
		$reference_tab = $this->input->post('reference_tab');
		$reference_pk = $this->input->post('reference_pk');
		$reference_data_pk = $this->input->post('reference_data_pk');
		$pod_total_add = $this->input->post('pod_total_add');
		
		//CGST SGST Update
		$purchase_order_receive_id = $reference_data_pk;
		
		$this->db->where($tab_pk, $data_pk)->delete($tab);
		
        // Update header table
        $pod_total_amount = $this->db->select('SUM(item_quantity * item_rate) AS pod_total')
            ->where('purchase_order_receive_id', $purchase_order_receive_id)
            ->get('purchase_order_receive_detail')->row()->pod_total;
        $delivery_charge = $this->db->select('SUM(delivery_charges) AS delivery_charges')
            ->where('purchase_order_receive_id', $purchase_order_receive_id)
            ->get('purchase_order_receive_detail')->row()->delivery_charges;
        $pod_cgst_amount = $this->db->select_sum('pod_cgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->row()->pod_cgst_amount;
        $pod_sgst_amount = $this->db->select_sum('pod_sgst_amount')->get_where('purchase_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->row()->pod_sgst_amount;
        
        $updateHeaderArray = array(
            'total_amount' => $pod_total_amount,
            'total_delivery_charges' => $delivery_charge,
            'total_cgst_amount' => $pod_cgst_amount,
            'total_sgst_amount' => $pod_sgst_amount,
            'net_amount' => ($pod_total_amount + $delivery_charge + $pod_cgst_amount + $pod_sgst_amount )
        );
        $this->db->update('purchase_order_receive', $updateHeaderArray, array('purchase_order_receive_id' => $purchase_order_receive_id));

        
        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
		$data['line_items'] = $updateHeaderArray;
        $data['msg'] = 'Purchase Order Receive Detail Successfully Deleted';
        return $data;
    }
    
    public function ajax_update_payment_on_pk(){
        $pk = $this->input->post('pk_value');
        $table = 'purchase_order_receive';
        
        $payment_status = $this->db->get_where($table, array('purchase_order_receive_id' => $pk))->row()->payment_status;
        if($payment_status){
            $updateArray= array(
                'payment_status' => 0
            );    
        }else{
            $updateArray= array(
                'payment_status' => 1
            );
        }
        
        $this->db->update($table, $updateArray, array('purchase_order_receive_id' => $pk));
        
        // echo $this->db->last_query(); die;

        $data['title'] = 'Status changed!';
        $data['type'] = 'success';
        $data['msg'] = 'Payment Status Successfully Updated';
        return $data;
    }
    // purchase ORDER ENDS 
    
    public function receive_purchase_order_challan() {
        $data = [];
        $data["view_permission"] = $this->_user_wise_view_permission(18, $this->session->user_id);
        $data['po_dtl_items'] = $this->db->distinct()
            ->select('item_master.item, item_dtl.id_id, colors.color')
            ->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left')
            ->join('item_master', 'item_master.im_id=item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id=item_dtl.c_id', 'left')
            ->get_where('purchase_order_receive_detail')->result();
            
        $data['item_groups'] = $this->db->get('item_groups')->result();
        return array('page'=>'receive_purchase_order_challan/receive_purchase_order_challan_list_v.php', 'data'=>$data);
    }
    
    public function ajax_receive_purchase_order_challan_table_data() {
        

        $column_orderable = array(
            0 => 'purchase_challan_order_receive.purchase_order_receive_bill_no',
            1 => 'purchase_challan_order_receive.purchase_order_receive_date',
            2 => 'po_numbers',
            3 => 'acc_master.name',
            4 => 'purchase_challan_order_receive.status',
            5 => 'purchase_challan_order_receive.purchase_order_receive_id',
        );
    
        $column_search = array(
            'purchase_challan_order_receive.purchase_order_receive_bill_no',
            'acc_master.name',
            'purchase_challan_order_receive.purchase_order_receive_date'
        );
    
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order_col = $this->input->post('order')[0]['column'];
        $order  = isset($column_orderable[$order_col]) ? $column_orderable[$order_col] : 'purchase_challan_order_receive.purchase_order_receive_id';
        $dir    = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];
    
        $pur_rcv_id_id = $this->input->post('pur_rcv_id_id');
    
        $select = 'GROUP_CONCAT(DISTINCT purchase_order.po_number SEPARATOR ", ") as po_numbers,
            purchase_challan_order_receive.purchase_order_receive_id,
            purchase_challan_order_receive.purchase_order_receive_bill_no,
            DATE_FORMAT(purchase_challan_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date,
            purchase_challan_order_receive.am_id,
            purchase_challan_order_receive.status,
            acc_master.name as acc_master_name,
            acc_master.short_name as acc_master_short_name,
            purchase_challan_order_receive.payment_status,
            purchase_order_receive.purchase_order_receive_bill_no as por_bill_no,
            purchase_order_receive.purchase_order_receive_date as por_bill_date';
    
        // ── Filter by item id if provided ────────────────────────────────────────
        if (!empty($pur_rcv_id_id)) {
    
            // Total count
            $this->db->select($select);
            $this->db->join('acc_master', 'acc_master.am_id = purchase_challan_order_receive.am_id', 'left');
            $this->db->join('purchase_challan_order_receive_detail', 'purchase_challan_order_receive_detail.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->where('purchase_challan_order_receive_detail.id_id', $pur_rcv_id_id);
            $this->db->group_by('purchase_challan_order_receive.purchase_order_receive_id');
            $totalData = count($this->db->get('purchase_challan_order_receive')->result());
            $totalFiltered = $totalData;
    
            // Paginated result
            $this->db->select($select);
            $this->db->join('acc_master', 'acc_master.am_id = purchase_challan_order_receive.am_id', 'left');
            $this->db->join('purchase_challan_order_receive_detail', 'purchase_challan_order_receive_detail.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->where('purchase_challan_order_receive_detail.id_id', $pur_rcv_id_id);
            $this->db->group_by('purchase_challan_order_receive.purchase_order_receive_id');
            $this->db->order_by($order, $dir);
            $this->db->limit($limit, $start);
            $rs = $this->db->get('purchase_challan_order_receive')->result();
    
        } else {
    
            // ── Build search condition ────────────────────────────────────────────
            if (!empty($search)) {
                $this->db->group_start();
                $i = 0;
                foreach ($column_search as $item) {
                    if ($i === 0) {
                        $this->db->like($item, $search);
                    } else {
                        $this->db->or_like($item, $search);
                    }
                    $i++;
                }
                $this->db->group_end();
            }
    
            // Total count with search
            $this->db->select($select);
            $this->db->join('acc_master', 'acc_master.am_id = purchase_challan_order_receive.am_id', 'left');
            $this->db->join('purchase_challan_order_receive_detail', 'purchase_challan_order_receive_detail.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->where('purchase_challan_order_receive.status', 1);
            $this->db->group_by('purchase_challan_order_receive.purchase_order_receive_id');
            $count_result  = $this->db->get('purchase_challan_order_receive')->result();
            $totalData     = count($count_result);
            $totalFiltered = count($count_result);
    
            // Re-apply search for paginated result
            if (!empty($search)) {
                $this->db->group_start();
                $i = 0;
                foreach ($column_search as $item) {
                    if ($i === 0) {
                        $this->db->like($item, $search);
                    } else {
                        $this->db->or_like($item, $search);
                    }
                    $i++;
                }
                $this->db->group_end();
            }
    
            // Paginated result
            $this->db->select($select);
            $this->db->join('acc_master', 'acc_master.am_id = purchase_challan_order_receive.am_id', 'left');
            $this->db->join('purchase_challan_order_receive_detail', 'purchase_challan_order_receive_detail.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_challan_order_receive.purchase_order_receive_id', 'left');
            $this->db->where('purchase_challan_order_receive.status', 1);
            $this->db->group_by('purchase_challan_order_receive.purchase_order_receive_id');
            $this->db->order_by($order, $dir);
            $this->db->limit($limit, $start);
            $rs = $this->db->get('purchase_challan_order_receive')->result();
        }
    
        $data = array();
    
        foreach ($rs as $val) {
    
            $status = ($val->status == '1') ? 'Enable' : 'Disable';
    
            $nestedData['purchase_order_receive_bill_no'] = $val->purchase_order_receive_bill_no;
            $nestedData['purchase_order_receive_date']    = $val->purchase_order_receive_date;
            $nestedData['po_number']                      = $val->po_numbers;
            $nestedData['pur_order_supplier']             = $val->acc_master_name . ' [' . $val->acc_master_short_name . ']';
            $nestedData['status']                         = $status;
    
            $uvp = $this->_user_wise_view_permission(18, $this->session->user_id);
            if ($uvp == 'block') {
                $nestedData['action'] = '-';
            } else {
                $nestedData['action'] = '
                    <a href="' . base_url('admin/edit-receive-purchase-order-challan/' . $val->purchase_order_receive_id) . '" class="btn btn-info">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a href="javascript:void(0)"
                        pk-name="purchase_order_receive_id"
                        pk-value="' . $val->purchase_order_receive_id . '"
                        tab="purchase_challan_order_receive"
                        ref-tab="purchase_challan_order_receive_detail"
                        child="1"
                        class="btn btn-danger delete1">
                        <i class="fa fa-times"></i> Delete
                    </a>';
            }
    
            if ($this->session->usertype != 1) {
                if ($val->payment_status == 0) {
                    $nestedData['action'] .= '&nbsp;';
                } else {
                    $nestedData['action'] .= '&nbsp;';
                }
            }
    
            $data[] = $nestedData;
        }
    
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
    
        return $json_data;
    }
        
    public function purchase_challan_bill_rate_setup_m($purchase_order_id) {
        $data = '';
    
        $new_purchase_array = array();
    
        $get_all_id = $this->db->select('purchase_challan_order_receive_detail.*')
            ->group_by('purchase_challan_order_receive_detail.id_id')
            ->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_id))
            ->result();
    
        if (count($get_all_id) > 0) {
    
            foreach ($get_all_id as $g_a_i) {
    
                $this->db->select('purchase_challan_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_challan_order_receive.purchase_order_receive_bill_no, purchase_challan_order_receive.purchase_order_receive_date, item_rates.purchase_rate, item_rates.cost_rate, purchase_challan_order_receive.am_id');
                $this->db->join('purchase_challan_order_receive', 'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left');
                $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
                $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
                $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
                $this->db->join('item_rates', 'item_rates.id_id = item_dtl.id_id', 'left');
                $this->db->order_by('purchase_challan_order_receive.purchase_order_receive_date', 'desc');
                $this->db->limit(3);
                $pord_receive_details = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_challan_order_receive_detail.id_id' => $g_a_i->id_id))->result();
    
                foreach ($pord_receive_details as $p_r_d) {
                    $arr = array(
                        'am_id'                    => $p_r_d->am_id,
                        'purchase_order_receive_id' => $p_r_d->purchase_order_receive_id,
                        'id_id'                    => $p_r_d->id_id,
                        'purchase_bill_no'         => $p_r_d->purchase_order_receive_bill_no,
                        'item_name'                => $p_r_d->item,
                        'color'                    => $p_r_d->color,
                        'created_date'             => $p_r_d->purchase_order_receive_date,
                        'purchase_rate'            => $p_r_d->item_rate,
                        'item_purchase_rate'       => $p_r_d->purchase_rate,
                        'item_cost_rate'           => $p_r_d->cost_rate
                    );
    
                    array_push($new_purchase_array, $arr);
                }
            }
    
            $data['segment']       = 'purchase_challan_order_rate_setup_details';
            $data['purchase_array'] = $new_purchase_array;
    
            return array('page' => 'reports/common_print_v', 'data' => $data);
    
        } else {
    
            die('No data to show.');
    
        }
    }

    public function edit_receive_purchase_order_challan($purchase_order_receive_id) {
    $data['item_groups'] = $this->db->select('ig_id, ig_code, group_name')->get_where('item_groups', array('item_groups.status' => 1))->result_array();
    $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();

    $data['receive_purchase_order_details'] = $this->db
        ->select('purchase_challan_order_receive.purchase_order_receive_id, purchase_challan_order_receive.purchase_order_receive_bill_no, 
        DATE_FORMAT(purchase_challan_order_receive.purchase_order_receive_date, "%d-%m-%Y") as purchase_order_receive_date, purchase_challan_order_receive.am_id, 
        purchase_challan_order_receive.total_amount, purchase_challan_order_receive.total_delivery_charges, purchase_challan_order_receive.net_amount,  
        purchase_challan_order_receive.status, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, 
        (SELECT SUM(pod_cgst_amount) as total_cgst_amount FROM purchase_challan_order_receive_detail WHERE purchase_order_receive_id = '.$purchase_order_receive_id.') AS total_cgst_amount,
        (SELECT SUM(pod_sgst_amount) as total_sgst_amount FROM purchase_challan_order_receive_detail WHERE purchase_order_receive_id = '.$purchase_order_receive_id.') AS total_sgst_amount')
        ->join('acc_master', 'acc_master.am_id = purchase_challan_order_receive.am_id', 'left')
        ->get_where('purchase_challan_order_receive', array('purchase_challan_order_receive.purchase_order_receive_id' => $purchase_order_receive_id))->result();

    $am_id = $data['receive_purchase_order_details'][0]->am_id;

    $pur_num_rows = $this->db->get_where('purchase_order', array('am_id' => $am_id, 'status' => 1))->num_rows();
    if ($pur_num_rows > 0) {
        $data['purchase_order'] = $this->db->select('po_id, po_number')->order_by('po_number')->get_where('purchase_order', array('am_id' => $am_id, 'status' => 1))->result_array();
    } else {
        $data['purchase_order'] = array();
    }

    $sup_num_rows = $this->db->get_where('supp_purchase_order', array('am_id' => $am_id, 'supp_status' => 1))->num_rows();
    if ($sup_num_rows > 0) {
        $data['supp_purchase_order'] = $this->db->select('sup_id, supp_po_number')->get_where('supp_purchase_order', array('am_id' => $am_id, 'supp_status' => 1))->result_array();
    } else {
        $data['supp_purchase_order'] = array();
    }

    return array('page' => 'receive_purchase_order_challan/receive_purchase_order_challan_edit_v', 'data' => $data);
}

    public function form_edit_receive_purchase_orderr(){

        $old_array = $this->db->get_where('purchase_challan_order_receive', array(
            'purchase_order_receive_id' => $this->input->post('purchase_order_receive_id')
        ))->row();
    
        $this->log_before_update($old_array, $this->input->post('purchase_order_receive_id'), 'purchase_challan_order_receive');
    
        $billno = $this->input->post('purchase_order_receive_bill_no');
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
    
        $duplicate_po = $this->db->get_where('purchase_challan_order_receive', array(
            'purchase_order_receive_bill_no' => $billno,
            'purchase_order_receive_id !=' => $purchase_order_receive_id
        ))->num_rows();
    
        if($duplicate_po > 0){
            $data['type'] = 'warning';
            $data['msg'] = 'Receive Purchase order already exists.';    
        } else{
            $updateArray = array(
                'purchase_order_receive_bill_no' => $this->input->post('purchase_order_receive_bill_no'),
                'purchase_order_receive_date' => $this->input->post('purchase_order_receive_date'),
                'am_id' => $this->input->post('am_id_hidden'),
                'status' => $this->input->post('status'),
                'user_id' => $this->session->user_id
            );
    
            $this->db->update(
                'purchase_challan_order_receive',
                $updateArray,
                array('purchase_order_receive_id' => $purchase_order_receive_id)
            );
    
            $data['type'] = 'success';
            $data['msg'] = 'Receive Purchase order updated successfully.';
        }
        
        return $data;
    }
    
    

    public function all_items_on_purchase_challan_order() {
    $po_id  = $this->input->post('po_id');
    $am_id  = $this->input->post('am_id_hidden');
 
    $items = $this->db
        ->select('purchase_order_details.id_id, purchase_order_details.pod_quantity, item_master.item AS item_name, colors.color, units.unit')
        ->from('purchase_order_details')
        ->join('item_dtl', 'item_dtl.id_id = purchase_order_details.id_id', 'left')
        ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
        ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
        ->join('units', 'units.u_id = item_master.u_id', 'left')
        ->where('purchase_order_details.po_id', $po_id)
        ->where('purchase_order_details.status', 1)
        ->get()->result();
 
    $sup_po_orders = $this->db
        ->select('sup_id, supp_po_number')
        ->where('po_id', $po_id)
        ->where('am_id', $am_id)
        ->where('supp_status', 1)
        ->get_where('supp_purchase_order')->result();
 
    $all_items = array();
 
    foreach ($items as $item) {
 
        // ── Already received qty ──────────────────────────────────────────────
        $this->db->reset_query();
        $received = $this->db
            ->select_sum('item_quantity')
            ->from('purchase_challan_order_receive_detail')
            ->where('po_id', $po_id)
            ->where('id_id', $item->id_id)
            ->where('status', 1)
            ->get()->row();
 
        $received_qty  = (!empty($received) && $received->item_quantity !== null)
            ? (float)$received->item_quantity : 0;
        $remaining_qty = (float)$item->pod_quantity - $received_qty;
 
        // ── Fetch last challan rate: same supplier + same item + color ─────────
        // Priority 1: last saved challan rate (item_rate > 0) for this supplier
        // Priority 2: fallback to opening_rate from item_dtl
        
        
       // Priority 1: latest purchase_rate from item_rates master
// same supplier (am_id) + same item (id_id), order by effective_date DESC
$this->db->reset_query();
$last_rate_row = $this->db
    ->select('ir.purchase_rate AS item_rate')
    ->from('item_rates ir')
    ->where('ir.id_id', $item->id_id)
    ->where('ir.am_id', $am_id)
    ->where('ir.status', 1)
    ->order_by('ir.effective_date', 'DESC')
    ->limit(1)
    ->get()->row();

if (!empty($last_rate_row) && (float)$last_rate_row->item_rate > 0) {
    // ← use latest purchase_rate from item_rates
    $last_item_rate = number_format((float)$last_rate_row->item_rate, 3, '.', '');
} else {
    // Priority 2: fallback to opening_rate from item_dtl
    $this->db->reset_query();
    $opening_rate_row = $this->db
        ->select('opening_rate')
        ->get_where('item_dtl', array('id_id' => $item->id_id))
        ->row();
    $last_item_rate = (!empty($opening_rate_row) && (float)$opening_rate_row->opening_rate > 0)
        ? number_format((float)$opening_rate_row->opening_rate, 3, '.', '')
        : '0';
}
 
        $all_items[] = array(
            'id_id'        => $item->id_id,
            'pod_quantity' => $remaining_qty,  // ← 2500 - 1021.5 = 1478.5
            'item_name'    => $item->item_name,
            'color'        => $item->color,
            'unit'         => $item->unit,
            'item_rate'    => $last_item_rate, // ← last challan rate OR opening_rate
        );
    }
 
    echo json_encode(array(
        'all_items'     => $all_items,
        'sup_po_orders' => $sup_po_orders
    ));
    die();
}
    
    public function ajax_receive_purchase_challan_order_details_table_data() {
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
    
        $column_orderable = array(
            0 => 'id_id',
        );
        $column_search = array('item_master.item', 'colors.color');
    
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $column_orderable[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];
    
        // get total count
        $this->db->limit($limit, $start);
        $this->db->order_by($order, $dir);
        $this->db->select('purchase_challan_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_challan_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
        $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
        $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_challan_order_receive_detail.sup_id', 'left');
        $rs = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
        $totalData     = count($rs);
        $totalFiltered = $totalData;
    
        if (empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('purchase_challan_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_challan_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_challan_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
        } else {
            $this->db->start_cache();
            $i = 0;
            foreach ($column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search);
                } else {
                    $this->db->or_like($item, $search);
                }
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
            $this->db->stop_cache();
    
            $this->db->select('purchase_challan_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_challan_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_challan_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
    
            $totalFiltered = count($rs);
    
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('purchase_challan_order_receive_detail.*, colors.color, colors.c_code, item_master.item, purchase_order.po_number, supp_purchase_order.supp_po_number, DATE_FORMAT(purchase_challan_order_receive_detail.receive_date, "%d-%m-%Y") as receive_date');
            $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
            $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_challan_order_receive_detail.sup_id', 'left');
            $rs = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_id' => $purchase_order_receive_id))->result();
    
            $this->db->flush_cache();
        }
    
        $data = array();
    
        foreach ($rs as $val) {
            $nestedData['po_number']     = $val->po_number;
            $nestedData['sup_po_number'] = $val->supp_po_number;
            $nestedData['item_name']     = $val->item;
            $nestedData['item_color']    = $val->color . ' [' . $val->c_code . ']';
            $nestedData['item_qty']      = $val->item_quantity;
            $nestedData['item_rate']     = number_format((float)$val->item_rate, 3); // ← ADD THIS
            $nestedData['receive_date']  = $val->receive_date;
    
            $nestedData['action'] = '<a href="javascript:void(0)" purchase_order_receive_detail_id="' . $val->purchase_order_receive_detail_id . '" class="purchase_order_receive_detail_id btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a tab="purchase_challan_order_receive_detail" tab-pk="purchase_order_receive_detail_id" data-pk="' . $val->purchase_order_receive_detail_id . '" reference-tab="purchase_challan_order_receive" reference-pk="purchase_order_receive_id" reference-data-pk="' . $purchase_order_receive_id . '" href="javascript:void(0)" class="btn btn-danger delete1"><i class="fa fa-times"></i> Delete</a>';
    
            $data[] = $nestedData;
        }
    
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
    
        return $json_data;
    }
        
    public function form_edit_receive_purchase_challan_order_details() {
    $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');
    $purchase_order_receive_id        = $this->input->post('purchase_order_receive_id');
 
    // Log old data
    $old_array = $this->db->get_where('purchase_challan_order_receive_detail', array(
        'purchase_order_receive_detail_id' => $purchase_order_receive_detail_id
    ))->result();
    $this->log_before_update($old_array, $purchase_order_receive_detail_id, 'purchase_challan_order_receive_detail');
 
    $updateArray = array(
        'item_quantity' => $this->input->post('pod_quantity_edit'),
        'item_rate'     => $this->input->post('item_rate'), // ← NEW
        'receive_date'  => $this->input->post('rcv_date_detail_edit'),
        'remarks'       => $this->input->post('sup_pod_remarks_edit'),
        'user_id'       => $this->session->user_id
    );
 
    $this->db->update(
        'purchase_challan_order_receive_detail',
        $updateArray,
        array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id)
    );
 
    $data['type'] = 'success';
    $data['msg']  = 'Receive purchase challan order details updated successfully.';
 
    return $data;
}
        
    public function form_add_receive_purchase_challan_order_details() {

            $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
        
            $insertArray = array(
                'purchase_order_receive_id' => $purchase_order_receive_id,
                'po_id'         => $this->input->post('po_id'),
                'sup_id'        => $this->input->post('sup_id'),
                'id_id'         => $this->input->post('id_id_add'),
                'item_rate'     => $this->input->post('item_rate'),
                'item_quantity' => $this->input->post('pod_quantity_add'),
                'receive_date'  => $this->input->post('rcv_date_detail'),
                'remarks'       => $this->input->post('sup_pod_remarks'),
                'user_id'       => $this->session->user_id
            );
        
            $this->db->insert('purchase_challan_order_receive_detail', $insertArray);
            $insert_id = $this->db->insert_id();
        
            if ($insert_id > 0) {
                $data['type'] = 'success';
                $data['msg']  = 'Receive purchase challan order details added successfully.';
            } else {
                $data['type'] = 'error';
                $data['msg']  = 'Receive purchase challan order details not added successfully.';
            }
        
            return $data;
        }

    public function ajax_fetch_receive_purchase_challan_order_details_on_pk() {
        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');
        $data = array();
    
        $this->db->select('purchase_challan_order_receive_detail.purchase_order_receive_detail_id,
            purchase_challan_order_receive_detail.po_id,
            purchase_challan_order_receive_detail.sup_id,
            purchase_challan_order_receive_detail.id_id,
            purchase_challan_order_receive_detail.item_quantity,
            purchase_challan_order_receive_detail.receive_date,
            purchase_challan_order_receive_detail.remarks,
            purchase_order.po_number,
            supp_purchase_order.supp_po_number,
            colors.color,
            colors.c_code,
            item_master.item,
            units.unit');
        $this->db->join('purchase_order', 'purchase_order.po_id = purchase_challan_order_receive_detail.po_id', 'left');
        $this->db->join('supp_purchase_order', 'supp_purchase_order.sup_id = purchase_challan_order_receive_detail.sup_id', 'left');
        $this->db->join('item_dtl', 'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('units', 'units.u_id = item_master.u_id', 'left');
        $oreder_receive_details = $this->db->get_where('purchase_challan_order_receive_detail', array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id))->result_array()[0];
    
        $data['oreder_receive_details'] = $oreder_receive_details;
    
        return $data;
    }
    
    public function delete_receive_purchase_challan_order_details_list() {
    $primary_key   = $this->input->post('data_pk');
    $table_name    = $this->input->post('tab');
    $pk_field_name = $this->input->post('tab_pk');

    $reference_array = array(
        array(
            "tbl_name"   => $this->input->post('tab'),
            "tbl_pk_fld" => $this->input->post('tab_pk'),
        )
    );
    $this->check_and_log_before_delete($reference_array, $primary_key, $pk_field_name, $table_name);

    $tab_pk  = $this->input->post('tab_pk');
    $data_pk = $this->input->post('data_pk');

    $this->db->where($tab_pk, $data_pk)->delete($table_name);

    $data['title'] = 'Deleted!';
    $data['type']  = 'success';
    $data['msg']   = 'Purchase Challan Order Receive Detail Successfully Deleted';

    return $data;
}
    
    public function add_receive_purchase_challan_order() {
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();
        return array('page'=>'receive_purchase_order_challan/receive_purchase_challan_order_add_v', 'data'=>$data);
    }
    
    public function form_add_receive_purchase_challan_order(){

        $insertArray = array(
            'purchase_order_receive_bill_no' => $this->input->post('purchase_order_receive_bill_no'),
            'purchase_order_receive_date' => $this->input->post('purchase_order_receive_date'),
            'am_id' => $this->input->post('am_id'),
            'user_id' => $this->session->user_id
        );

        // echo '<pre>', print_r($insertArray), '</pre>';die;

        $this->db->insert('purchase_challan_order_receive', $insertArray);
        $data['insert_id'] = $this->db->insert_id();
		if($this->db->insert_id() > 0){
			$data['type'] = 'success';
			$data['msg'] = 'Receive order added successfully.';
		}else{
			$data['type'] = 'error';
			$data['msg'] = 'Not Inserted successfully.';
		}
        return $data;
    }
    
    public function all_items_from_challan_by_po() {
        $po_id  = $this->input->post('po_id');
        $am_id  = $this->input->post('am_id_hidden');
    
        $this->db->select('
            purchase_challan_order_receive_detail.id_id,
            purchase_challan_order_receive_detail.item_quantity as pod_quantity,
            item_master.item as item_name,
            units.unit,
            colors.color
        ');
        $this->db->join('item_dtl',    'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('units',       'units.u_id = item_master.u_id',      'left');
        $this->db->join('colors',      'colors.c_id = item_dtl.c_id',        'left');
    
        $data['all_items'] = $this->db
            ->get_where('purchase_challan_order_receive_detail', array(
                'purchase_challan_order_receive_detail.po_id' => $po_id
            ))->result_array();
    
        // Supp PO list for the same PO
        $sup_rows = $this->db->get_where('supp_purchase_order', array(
            'am_id'       => $am_id,
            'po_id'       => $po_id,
            'supp_status' => 1
        ))->num_rows();
    
        $data['sup_po_orders'] = ($sup_rows > 0)
            ? $this->db->select('sup_id, supp_po_number')
                  ->get_where('supp_purchase_order', array(
                      'am_id'       => $am_id,
                      'po_id'       => $po_id,
                      'supp_status' => 1
                  ))->result_array()
            : array();
    
        return $data;
    }
    
    public function all_items_from_challan_by_receive_id() {
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
    
        $this->db->select('
            purchase_challan_order_receive_detail.purchase_order_receive_detail_id,
            purchase_challan_order_receive_detail.id_id,
            purchase_challan_order_receive_detail.item_quantity as pod_quantity,
            item_master.item as item_name,
            units.unit,
            colors.color
        ');
        $this->db->join('item_dtl',    'item_dtl.id_id = purchase_challan_order_receive_detail.id_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('units',       'units.u_id = item_master.u_id',      'left');
        $this->db->join('colors',      'colors.c_id = item_dtl.c_id',        'left');
    
        $data = $this->db->get_where(
            'purchase_challan_order_receive_detail',
            array('purchase_challan_order_receive_detail.purchase_order_receive_id' => $purchase_order_receive_id)
        )->result_array();
    
        return $data;
    }
    
    // public function all_items_from_by_receive_id() {
    //     $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
    
    //     $this->db->select('
    //         purchase_order_receive_detail.purchase_order_receive_detail_id,
    //         purchase_order_receive_detail.id_id,
    //         purchase_order_receive_detail.item_quantity as pod_quantity,
    //         item_master.item as item_name,
    //         units.unit,
    //         colors.color
    //     ');
    //     $this->db->join('item_dtl',    'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left');
    //     $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
    //     $this->db->join('units',       'units.u_id = item_master.u_id',      'left');
    //     $this->db->join('colors',      'colors.c_id = item_dtl.c_id',        'left');
    
    //     $data = $this->db->get_where(
    //         'purchase_challan_order_receive_detail',
    //         array('purchase_order_receive_detail.purchase_order_receive_id' => $purchase_order_receive_id)
    //     )->result_array();
    
    //     return $data;
    // }
    
    public function all_items_from_by_receive_id() {
        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');
    
        if (empty($purchase_order_receive_id)) {
            return array();
        }
    
        $sql = "
            SELECT
                pcord.purchase_order_receive_detail_id,
                pcord.id_id,
                pcord.po_id,
                im.item     AS item_name,
                u.unit,
                c.color,
                pcord.item_quantity AS challan_qty,
                IFNULL((
                    SELECT SUM(pord.item_quantity)
                    FROM purchase_order_receive_detail pord
                    WHERE pord.challan_detail_id = pcord.purchase_order_receive_detail_id
                ), 0) AS already_received,
                (pcord.item_quantity - IFNULL((
                    SELECT SUM(pord.item_quantity)
                    FROM purchase_order_receive_detail pord
                    WHERE pord.challan_detail_id = pcord.purchase_order_receive_detail_id
                ), 0)) AS pod_quantity
            FROM purchase_challan_order_receive_detail pcord
            LEFT JOIN item_dtl    id ON id.id_id = pcord.id_id
            LEFT JOIN item_master im ON im.im_id = id.im_id
            LEFT JOIN units       u  ON u.u_id   = im.u_id
            LEFT JOIN colors      c  ON c.c_id   = id.c_id
            WHERE pcord.purchase_order_receive_id = ?
            AND   pcord.status = 1
            HAVING pod_quantity > 0
        ";
    
        $data = $this->db->query($sql, array($purchase_order_receive_id))->result_array();
    
        $data = array_values(array_filter($data, function($row) {
            return (float)$row['pod_quantity'] > 0;
        }));
    
        return $data;
    }

}