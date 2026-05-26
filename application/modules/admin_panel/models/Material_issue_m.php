<?php

class Material_issue_m extends CI_Model {

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
            'comment' => 'Material Isuue'
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
                'comment' => 'Material Issue'
            );
            if($this->db->insert('user_logs', $insertArray)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function material_issue_list() {
        $data = [];
        $data["view_permission"] = $this->_user_wise_view_permission(20, $this->session->user_id);
        return array('page' => 'material_issue/material_issue_list_v', 'data' => $data);
    }

    public function ajax_material_issue_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'material_issue.material_issue_slip_number',
            2 => 'material_issue.material_issue_to_form',
            4 => 'material_issue.total_value',
            4 => 'jobber_issue.jobber_challan_number'
        );
        // Set searchable column fields
        $column_search = array('material_issue.material_issue_slip_number', 'material_issue.material_issue_to_form', 'material_issue.total_value', 'jobber_issue.jobber_challan_number');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');

        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('material_issue.material_issue_id, material_issue.material_issue_slip_number,  material_issue.material_issue_to_form, material_issue.terms_condition, material_issue.remarks, material_issue.total_value, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, DATE_FORMAT(material_issue_date, "%d-%m-%Y") as material_issue_date, jobber_issue.jobber_challan_number');
        $this->db->join('acc_master', 'acc_master.am_id = material_issue.am_id', 'left');
        $this->db->join('jobber_issue', 'jobber_issue.jobber_issue_id = material_issue.jobber_challan_receipt_id', 'left');
        $rs = $this->db->get_where('material_issue', array('material_issue.status => 1'))->result();

        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if (empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('material_issue.material_issue_id, material_issue.material_issue_slip_number,  material_issue.material_issue_to_form, material_issue.terms_condition, material_issue.remarks, material_issue.total_value, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, DATE_FORMAT(material_issue_date, "%d-%m-%Y") as material_issue_date, jobber_issue.jobber_challan_number');
            $this->db->join('acc_master', 'acc_master.am_id = material_issue.am_id', 'left');
            $this->db->join('jobber_issue', 'jobber_issue.jobber_issue_id = material_issue.jobber_challan_receipt_id', 'left');
            $rs = $this->db->get_where('material_issue', array('material_issue.status => 1'))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach ($column_search as $item) {
                // first loop
                if ($i === 0) {
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                } else {
                    $this->db->or_like($item, $search);
                }
                // last loop
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('material_issue.material_issue_id, material_issue.material_issue_slip_number,  material_issue.material_issue_to_form, material_issue.terms_condition, material_issue.remarks, material_issue.total_value, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, DATE_FORMAT(material_issue_date, "%d-%m-%Y") as material_issue_date, jobber_issue.jobber_challan_number');
            $this->db->join('acc_master', 'acc_master.am_id = material_issue.am_id', 'left');
            $this->db->join('jobber_issue', 'jobber_issue.jobber_issue_id = material_issue.jobber_challan_receipt_id', 'left');
            $rs = $this->db->get_where('material_issue', array('material_issue.status => 1'))->result();


            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('material_issue.material_issue_id, material_issue.material_issue_slip_number,  material_issue.material_issue_to_form, material_issue.terms_condition, material_issue.remarks, material_issue.total_value, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, DATE_FORMAT(material_issue_date, "%d-%m-%Y") as material_issue_date, jobber_issue.jobber_challan_number');
            $this->db->join('acc_master', 'acc_master.am_id = material_issue.am_id', 'left');
            $this->db->join('jobber_issue', 'jobber_issue.jobber_issue_id = material_issue.jobber_challan_receipt_id', 'left');
            $rs = $this->db->get_where('material_issue', array('material_issue.status => 1'))->result();
            $this->db->flush_cache();
        }

        $data = array();

        // echo '<pre>', print_r($rs), '</pre>'; die;
        //echo $this->db->last_query();die;

        foreach ($rs as $val) {
            if ($val->material_issue_to_form == '1') {
                $issue_to_form = 'Godown';
            }
            if ($val->material_issue_to_form == '2') {
                $issue_to_form = 'Fabricator';
            }
            if ($val->material_issue_to_form == '3') {
                $issue_to_form = 'Other';
            }

            $challan_or_supplier = $val->jobber_challan_number . ' ' . $val->acc_master_name;

            $nestedData['issue_slip_number'] = $val->material_issue_slip_number;
            $nestedData['issue_date'] = $val->material_issue_date;
            $nestedData['challan_or_supplier'] = $challan_or_supplier;
            $nestedData['issue_to_form'] = $issue_to_form;
            $nestedData['terms_condition'] = $val->terms_condition;
            $nestedData['remarks'] = $val->remarks;

            $uvp = $this->_user_wise_view_permission(20, $this->session->user_id);
            if($uvp == 'block'){
                $nestedData['action'] = '-';    
            }else{
                $nestedData['action'] = '<a href="' . base_url('admin/material-issue-edit/' . $val->material_issue_id) . '" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a href="javascript:void(0)" pk-name="material_issue_id" pk-value="' . $val->material_issue_id . '" tab="material_issue" ref-tab="material_issue_detail" child="1" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';
            }
            $data[] = $nestedData;

            // echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return $json_data;
    }

    // ADD supp.purchase ORDER 

    public function material_issue_add() {
        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();

        $data['jobber_challan_details'] = $this->db->select('jobber_issue_id, jobber_challan_number')->order_by('jobber_challan_number')->get_where('jobber_issue', array('jobber_issue.status' => 1))->result();
        $data['jobber_challan_details1'] = $this->db->select('jobber_challan_receipt_id')->order_by('jobber_challan_receipt_id')->get_where('material_issue', array('material_issue.status' => 1))->result();

        return array('page' => 'material_issue/material_issue_add_v', 'data' => $data);
    }

    public function ajax_unique_material_issue_number() {
        $material_issue_slip_number = $this->input->post('material_issue_slip_number');

        $rs = $this->db->get_where('material_issue', array('material_issue_slip_number' => $material_issue_slip_number))->num_rows();
        if ($rs != '0') {
            $data = 'Issue Slip number already exists.';
        } else {
            $data = 'true';
        }
        // echo $this->db->last_query();
        return $data;
    }

    public function form_add_material_issue() {

        $insertArray = array(
            'material_issue_slip_number' => $this->input->post('material_issue_slip_number'),
            'material_issue_date' => $this->input->post('material_issue_date'),
            'material_issue_to_form' => $this->input->post('material_issue_to_form'),
            'jobber_challan_receipt_id' => $this->input->post('jobber_challan_receipt_id'),
            'am_id' => $this->input->post('am_id'),
            'terms_condition' => $this->input->post('terms_condition'),
            'remarks' => $this->input->post('remarks'),
            'virtual_status' => $this->input->post('virtual_status'),
            'user_id' => $this->session->user_id
        );

        // echo '<pre>', print_r($insertArray), '</pre>';die;

        $this->db->insert('material_issue', $insertArray);
        $data['insert_id'] = $this->db->insert_id();
        if ($this->db->insert_id() > 0) {
            $data['type'] = 'success';
            $data['msg'] = 'Material Issued successfully.';
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Data Insert Error';
        }
        return $data;
    }

    public function material_issue_edit($material_issue_id) {
        $data['item_groups'] = $this->db->select('ig_id, ig_code, group_name')->get_where('item_groups', array('item_groups.status' => 1))->result_array();

        $data['buyer_details'] = $this->db->select('am_id, name, short_name')->get_where('acc_master', array('ag_id' => 1, 'acc_master.status' => 1))->result();

        $data['jobber_challan_details'] = $this->db->select('jobber_issue_id, jobber_challan_number')->order_by('jobber_challan_number')->get_where('jobber_issue', array('jobber_issue.status' => 1))->result();
        $data['jobber_challan_details1'] = $this->db->select('jobber_challan_receipt_id')->order_by('jobber_challan_receipt_id')->get_where('material_issue', array('material_issue.status' => 1))->result();


        $data['customer_order'] = $this->db->select('co_id, co_no')
            ->get_where('customer_order', array( 'customer_order.status' => 1))->result();


        $data['material_issue_data'] = $this->db->select('material_issue.material_issue_id, material_issue.material_issue_slip_number, material_issue.material_issue_to_form, material_issue.jobber_challan_receipt_id, material_issue.am_id, material_issue.terms_condition, material_issue.remarks,material_issue.virtual_status,material_issue.total_value, acc_master.name as acc_master_name, acc_master.short_name as acc_master_short_name, jobber_challan_receipt.jobber_receipt_challan_number, material_issue.material_issue_date, jobber_issue.jobber_issue_id, jobber_issue.jobber_challan_number')
            ->join('acc_master', 'acc_master.am_id = material_issue.am_id', 'left')
            ->join('jobber_challan_receipt', 'jobber_challan_receipt.jobber_challan_receipt_id = material_issue.jobber_challan_receipt_id', 'left')
            ->join('jobber_challan_receipt_details', 'jobber_challan_receipt_details.jobber_challan_receipt_id = jobber_challan_receipt.jobber_challan_receipt_id', 'left')
            ->join('jobber_issue', 'jobber_issue.jobber_issue_id = jobber_challan_receipt_details.jobber_challan_number', 'left')
            ->get_where('material_issue', array('material_issue.material_issue_id' => $material_issue_id))->result();

    $data['item_details'] = $this->db->select('purchase_order_receive_detail.purchase_order_receive_detail_id, purchase_order_receive_detail.remain_quantity_for_material_issue, item_dtl.id_id, item_master.item, item_master.im_id, item_master.im_code, item_master.u_id, units.unit, colors.c_id, colors.color, purchase_order_receive_detail.material_issue_status')
            ->join('purchase_order_receive_detail', 'purchase_order_receive_detail.id_id = item_dtl.id_id', 'left')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('units', 'units.u_id = item_master.u_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left')
            // ->where('item_dtl.opening_stock >', 0)
            ->order_by('item_master.item, colors.color')
            ->group_by('item_dtl.id_id')
            ->get_where('item_dtl', array('item_dtl.status' => 1))->result();

        return array('page' => 'material_issue/material_issue_edit_v', 'data' => $data);
    }

    public function form_edit_material_issue() {

        $old_array = $this->db->get_where('material_issue', array('material_issue_id' => $this->input->post('material_issue_id')))->row();

        // echo '<pre>', print_r($old_array), '</pre>'; die();

        $this->log_before_update($old_array, $this->input->post('material_issue_id'), 'material_issue');

        $updateArray = array(
            'material_issue_slip_number' => $this->input->post('material_issue_slip_number'),
            'material_issue_to_form' => $this->input->post('material_issue_to_form'),
            'material_issue_date' => $this->input->post('material_issue_date'),
            'jobber_challan_receipt_id' => $this->input->post('jobber_challan_receipt_id'),
            'am_id' => $this->input->post('am_id'),
            'terms_condition' => $this->input->post('terms_condition'),
            'remarks' => $this->input->post('remarks'),
            'virtual_status' => $this->input->post('virtual_status'),
            'user_id' => $this->session->user_id
        );
        $material_issue_id = $this->input->post('material_issue_id');

        $this->db->update('material_issue', $updateArray, array('material_issue_id' => $material_issue_id));

        $data['type'] = 'success';
        $data['msg'] = 'Material Issue updated successfully.';
        return $data;
    }

    public function ajax_material_issue_details_table_data() {
        $material_issue_id = $this->input->post('material_issue_id_add');

        //actual db table column names
        $column_orderable = array(
            0 => 'item_master.item',
            1 => 'colors.color',
            2 => 'material_issue_detail.issue_quantity',
            3 => 'material_issue_detail.issue_rate',
            4 => 'material_issue_detail.total_amount'
        );
        // Set searchable column fields
        $column_search = array('item_master.item', 'colors.color', 'material_issue_detail.issue_quantity', 'material_issue_detail.issue_rate', 'material_issue_detail.total_amount');
        // $column_search = array('co_no');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');

        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $this->db->select('material_issue_detail.material_issue_detail_id, material_issue_detail.material_issue_id, material_issue_detail.co_id, material_issue_detail.purchase_order_receive_detail_id, material_issue_detail.issue_quantity, material_issue_detail.issue_rate, material_issue_detail.total_amount, colors.color, colors.c_code, item_master.item');
        $this->db->join('item_dtl', 'item_dtl.id_id = material_issue_detail.id_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $rs = $this->db->get_where('material_issue_detail', array('material_issue_detail.material_issue_id' => $material_issue_id))->result();

        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if (empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('material_issue_detail.material_issue_detail_id, material_issue_detail.material_issue_id, material_issue_detail.co_id, material_issue_detail.purchase_order_receive_detail_id, material_issue_detail.issue_quantity, material_issue_detail.issue_rate, material_issue_detail.total_amount, colors.color, colors.c_code, item_master.item');
            $this->db->join('item_dtl', 'item_dtl.id_id = material_issue_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $rs = $this->db->get_where('material_issue_detail', array('material_issue_detail.material_issue_id' => $material_issue_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach ($column_search as $item) {
                // first loop
                if ($i === 0) {
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                } else {
                    $this->db->or_like($item, $search);
                }
                // last loop
                if (count($column_search) - 1 == $i) {
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('material_issue_detail.material_issue_detail_id, material_issue_detail.material_issue_id, material_issue_detail.co_id, material_issue_detail.purchase_order_receive_detail_id, material_issue_detail.issue_quantity, material_issue_detail.issue_rate, material_issue_detail.total_amount, colors.color, colors.c_code, item_master.item');
            $this->db->join('item_dtl', 'item_dtl.id_id = material_issue_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $rs = $this->db->get_where('material_issue_detail', array('material_issue_detail.material_issue_id' => $material_issue_id))->result();


            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('material_issue_detail.material_issue_detail_id, material_issue_detail.material_issue_id, material_issue_detail.co_id, material_issue_detail.purchase_order_receive_detail_id, material_issue_detail.issue_quantity, material_issue_detail.issue_rate, material_issue_detail.total_amount, colors.color, colors.c_code, item_master.item');
            $this->db->join('item_dtl', 'item_dtl.id_id = material_issue_detail.id_id', 'left');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
            $rs = $this->db->get_where('material_issue_detail', array('material_issue_detail.material_issue_id' => $material_issue_id))->result();

            $this->db->flush_cache();
        }

        $data = array();

        //echo '<pre>', print_r($rs), '</pre>'; die;


        foreach ($rs as $val) {
            
            
            if($val->co_id != 0) {
            $customer_order_rows_vals = $this->db->select('customer_order.co_no')->get_where('customer_order', array('co_id' => $val->co_id))->row();
            $customer_order = $customer_order_rows_vals->co_no;
            } else {
            $customer_order = '';    
            }
            

            $nestedData['item_name'] = $val->item;
            $nestedData['item_color'] = $val->color . ' [' . $val->c_code . ']';
            $nestedData['item_qty'] = $val->issue_quantity;
            $nestedData['item_rate'] = $val->issue_rate;
            $nestedData['customer_orders'] = $customer_order;
            $nestedData['total_amount'] = $val->total_amount;

            $nestedData['action'] = '<a href="javascript:void(0)" material_issue_detail_id="' . $val->material_issue_detail_id . '" class="material_issue_detail_id btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            <a tab="material_issue_detail" tab-pk="material_issue_detail_id" 
            data-pk="' . $val->material_issue_detail_id . '" reference-tab="material_issue" reference-pk="material_issue_id" reference-data-pk="' . $val->material_issue_id . '" issue_quantity="' . $val->issue_quantity . '" total_amount="' . $val->total_amount . '" issue_quantity="' . $val->issue_quantity . '" purchase_order_receive_detail_id="' . $val->purchase_order_receive_detail_id . '" href="javascript:void(0)" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>';

            $data[] = $nestedData;

            // echo '<pre>', print_r($rs), '</pre>'; 
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return $json_data;
    }

    public function all_items_on_purchase_order_receive_detail() {
        $issue_date_add = $this->input->post('issue_date_add');
        $data = array();

        $item_details_as_purch_rcv = $this->db->select('purchase_order_receive_detail.purchase_order_receive_detail_id, purchase_order_receive_detail.remain_quantity_for_material_issue, item_dtl.id_id, item_master.item, item_master.im_id, item_master.im_code, item_master.u_id, units.unit, colors.c_id, colors.color, purchase_order_receive_detail.material_issue_status')
                        ->join('purchase_order_receive_detail', 'purchase_order_receive_detail.id_id = item_dtl.id_id', 'left')
                        ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                        ->join('units', 'units.u_id = item_master.u_id', 'left')
                        ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                        ->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left')
                        ->order_by('item_master.item, colors.color')
                        ->group_by('item_dtl.id_id')
                        ->get_where('item_dtl', array('item_dtl.status' => 1))->result();

        
        return $item_details_as_purch_rcv;
    }

    
    public function fetch_remainng_stock_for_material_issue() {
        $issue_date_add = $this->input->post('issue_date_add');
        $item_dtl_id    = $this->input->post('item_dtl_id');

        // Opening stock
        $item_detail_row = $this->db->get_where('item_dtl', array('id_id' => $item_dtl_id))->row();
        if ($item_detail_row === null) return 0;
        $opening_stock = (float)$item_detail_row->opening_stock;
        $im_id         = $item_detail_row->im_id;
        $c_id          = $item_detail_row->c_id;

        // Purchase received (purchase_order_receive_detail)
        $pur_row = $this->db->select_sum('purchase_order_receive_detail.item_quantity')
            ->join('purchase_order_receive', 'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left')
            ->where('purchase_order_receive.purchase_order_receive_date <=', $issue_date_add)
            ->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail.id_id' => $item_dtl_id, 'purchase_order_receive_detail.status' => 1))->row();
        $sum_purchase = ($pur_row !== null && $pur_row->item_quantity !== null) ? (float)$pur_row->item_quantity : 0;


        // Challan received — always fetch; use challan when available, fall back to formal receipt when challan = 0
        $this->db->reset_query();
        $challan_row = $this->db
            ->select_sum('purchase_challan_order_receive_detail.item_quantity')
            ->from('purchase_challan_order_receive_detail')
            ->join('purchase_challan_order_receive',
                'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left')
            ->where('purchase_challan_order_receive.purchase_order_receive_date <=', $issue_date_add)
            ->where('purchase_challan_order_receive_detail.id_id', $item_dtl_id)
            ->where('purchase_challan_order_receive_detail.status', 1)
            ->where('purchase_challan_order_receive.status', 1)
            ->get()->row();
        $sum_challan = (!empty($challan_row) && $challan_row->item_quantity !== null) ? (float)$challan_row->item_quantity : 0;
        $effective_receipt = ($sum_challan > 0) ? $sum_challan : $sum_purchase;

        // Stock in
        $stock_in_row = $this->db->select_sum('stock_in_detail.item_quantity')
            ->join('stock_in', 'stock_in.purchase_order_receive_id = stock_in_detail.purchase_order_receive_id', 'left')
            ->where('stock_in.purchase_order_receive_date <=', $issue_date_add)
            ->get_where('stock_in_detail', array('stock_in_detail.id_id' => $item_dtl_id, 'stock_in_detail.status' => 1))->row();
        $sum_stock_in = ($stock_in_row !== null && $stock_in_row->item_quantity !== null) ? (float)$stock_in_row->item_quantity : 0;

        // Material issued
        $mat_row = $this->db->select_sum('material_issue_detail.issue_quantity')
            ->join('material_issue', 'material_issue.material_issue_id = material_issue_detail.material_issue_id', 'left')
            ->where('material_issue.material_issue_date <=', $issue_date_add)
            ->where('material_issue.material_issue_date >=', YEAR_START_DATE)
            ->get_where('material_issue_detail', array('material_issue_detail.id_id' => $item_dtl_id))->row();
        $sum_material_issue = ($mat_row !== null && $mat_row->issue_quantity !== null) ? (float)$mat_row->issue_quantity : 0;

        // Platting issued
        $platting_row = $this->db->select_sum('platting_issue_detail.issue_quantity')
            ->join('platting_issue', 'platting_issue.platting_issue_id = platting_issue_detail.platting_issue_id', 'left')
            ->where('platting_issue.platting_issue_date <=', $issue_date_add)
            ->get_where('platting_issue_detail', array('platting_issue_detail.im_id' => $im_id, 'platting_issue_detail.item_colour' => $c_id))->row();
        $sum_platting = ($platting_row !== null && $platting_row->issue_quantity !== null) ? (float)$platting_row->issue_quantity : 0;

        return round($opening_stock + $effective_receipt + $sum_stock_in - $sum_material_issue - $sum_platting, 2);
    }
    public function ajax_get_consume_list_purchase_order_receive_detail() {
    $data                   = array();
    $preview_data           = array();
    $data_array             = array();
 
    $id_id                  = $this->input->post('id_id');
    $im_id                  = $this->input->post('im_id');
    $issue_quantity_preview = $this->input->post('issue_quantity_preview');
    $issue_date_add         = date('Y-m-d', strtotime($this->input->post('issue_date_add')));
    $purc_rcv_id            = $this->input->post('purc_rcv_id');
 
    $sum_stock_in            = 0;
    $sum_challan             = 0;
    $sum_plating             = 0;
    $mat_issue               = 0;
    $opening_stock_quantity1 = 0;
    $item_quantity           = 0;
 
    // ── Guard ─────────────────────────────────────────────────────────────────
    if (empty($id_id) || empty($issue_quantity_preview)) {
        $data['status']       = false;
        $data['preview_data'] = array();
        return $data;
    }
 
    // ── Purchase receipts up to issue date ───────────────────────────────────
    $result1 = $this->db
        ->select('purchase_order_receive_detail.*, item_master.im_id, item_master.item,
                  colors.c_id, colors.color, item_dtl.opening_stock')
        ->join('purchase_order_receive',
            'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left')
        ->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left')
        ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
        ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
        ->where('purchase_order_receive.purchase_order_receive_date <=', $issue_date_add)
        ->order_by('purchase_order_receive_detail.purchase_order_receive_detail_id')
        ->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail.id_id' => $id_id))
        ->result();
 
    // ── Stock In sum ──────────────────────────────────────────────────────────
    $sum_stock_in_row = $this->db
        ->select('SUM(stock_in_detail.item_quantity) as item_quantity, stock_in_detail.item_rate')
        ->join('stock_in', 'stock_in.purchase_order_receive_id = stock_in_detail.purchase_order_receive_id', 'left')
        ->where('stock_in.purchase_order_receive_date <=', $issue_date_add)
        ->get_where('stock_in_detail', array(
            'stock_in_detail.id_id'  => $id_id,
            'stock_in_detail.status' => 1
        ))->row();
 
    if (!empty($sum_stock_in_row) && $sum_stock_in_row->item_quantity !== null && (float)$sum_stock_in_row->item_quantity > 0) {
        $sum_stock_in = (float)$sum_stock_in_row->item_quantity;
    }
 
    // ── Item detail ───────────────────────────────────────────────────────────
    $item_detail_row = $this->db->get_where('item_dtl', array('id_id' => $id_id))->row();
    if (empty($item_detail_row)) {
        $data['status']       = false;
        $data['preview_data'] = array();
        return $data;
    }
    $im_id = $item_detail_row->im_id;
    $c_id  = $item_detail_row->c_id;
 
    // ── Challan received sum ──────────────────────────────────────────────────
    $this->db->reset_query();
    $sum_challan_row = $this->db
        ->select_sum('purchase_challan_order_receive_detail.item_quantity')
        ->from('purchase_challan_order_receive_detail')
        ->join('purchase_challan_order_receive',
            'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left')
        ->where('purchase_challan_order_receive.purchase_order_receive_date <=', $issue_date_add)
        ->where('purchase_challan_order_receive_detail.id_id', $id_id)
        ->where('purchase_challan_order_receive_detail.status', 1)
        ->where('purchase_challan_order_receive.status', 1)
        ->get()->row();
 
    if (!empty($sum_challan_row) && $sum_challan_row->item_quantity !== null) {
        $sum_challan = (float)$sum_challan_row->item_quantity;
    }
 
    // ── FIX: Get ACTUAL challan rate from purchase_challan_order_receive_detail
    // Show real rate (even if 0.00) — do NOT fallback to opening_rate
    $this->db->reset_query();
    $actual_challan_rate_row = $this->db
        ->select('purchase_challan_order_receive_detail.item_rate')
        ->from('purchase_challan_order_receive_detail')
        ->join('purchase_challan_order_receive',
            'purchase_challan_order_receive.purchase_order_receive_id = purchase_challan_order_receive_detail.purchase_order_receive_id', 'left')
        ->where('purchase_challan_order_receive_detail.id_id', $id_id)
        ->where('purchase_challan_order_receive_detail.status', 1)
        ->where('purchase_challan_order_receive.status', 1)
        ->order_by('purchase_challan_order_receive_detail.purchase_order_receive_detail_id', 'DESC')
        ->limit(1)
        ->get()->row();
 
    // Use actual challan rate (0.00 if not set) — never override with opening_rate
    $challan_item_rate = (!empty($actual_challan_rate_row))
        ? (float)$actual_challan_rate_row->item_rate
        : 0;
 
    // ── Plating sum ───────────────────────────────────────────────────────────
    $sum_plating_row = $this->db
        ->select_sum('platting_issue_detail.issue_quantity')
        ->join('platting_issue', 'platting_issue.platting_issue_id = platting_issue_detail.platting_issue_id', 'left')
        ->where('platting_issue.platting_issue_date <=', $issue_date_add)
        ->get_where('platting_issue_detail', array(
            'platting_issue_detail.im_id'       => $im_id,
            'platting_issue_detail.item_colour' => $c_id
        ))->row();
 
    if (!empty($sum_plating_row) && $sum_plating_row->issue_quantity !== null) {
        $sum_plating = (float)$sum_plating_row->issue_quantity;
    }
 
    // ── Material issue sum ────────────────────────────────────────────────────
    $result_mat_issue = $this->db
        ->select_sum('issue_quantity')
        ->join('material_issue', 'material_issue.material_issue_id = material_issue_detail.material_issue_id', 'left')
        ->where('material_issue.material_issue_date <=', $issue_date_add)
        ->order_by('material_issue.material_issue_date')
        ->get_where('material_issue_detail', array('material_issue_detail.id_id' => $id_id))
        ->result();
 
    if (!empty($result_mat_issue) && $result_mat_issue[0]->issue_quantity !== null) {
        $mat_issue = (float)$result_mat_issue[0]->issue_quantity;
    }
 
    $total_issue_quantity = $mat_issue + $sum_plating;
 
    // ── Opening stock ─────────────────────────────────────────────────────────
    $result_opening_stock_row1 = $this->db
        ->select('item_master.im_id, item_master.item, colors.c_id, colors.color,
                  item_dtl.opening_stock, item_dtl.opening_rate, item_dtl.id_id')
        ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
        ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
        ->where('item_dtl.opening_stock >', 0)
        ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))
        ->result();
 
    if (!empty($result_opening_stock_row1)) {
        $opening_stock_quantity1 = (float)$result_opening_stock_row1[0]->opening_stock;
    }
 
    // ══════════════════════════════════════════════════════════════════════════
    // BUILD $data_array
    // ══════════════════════════════════════════════════════════════════════════
 
    if (count($result1) == 0) {
 
        // ── No purchase receipts ──────────────────────────────────────────────
        $result_opening_stock = $this->db
            ->select('item_master.im_id, item_master.item, colors.c_id, colors.color,
                      item_dtl.opening_stock, item_dtl.opening_rate, item_dtl.id_id')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->where('item_dtl.opening_stock >', 0)
            ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))
            ->result();
 
        if (!empty($result_opening_stock)) {
            $opn       = $result_opening_stock[0];
            $item_rate = (float)$opn->opening_rate;
 
            if ($total_issue_quantity > 0) {
                $qnty = (float)$opn->opening_stock - $total_issue_quantity;
                if ($qnty > 0) {
                    $data_array[] = array(
                        'im_id'                            => $opn->im_id,
                        'id_id'                            => $opn->id_id,
                        'item_name'                        => $opn->item,
                        'c_id'                             => $opn->c_id,
                        'color'                            => $opn->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $qnty,
                        'item_rate'                        => $item_rate,
                    );
                }
            } else {
                $data_array[] = array(
                    'im_id'                            => $opn->im_id,
                    'id_id'                            => $opn->id_id,
                    'item_name'                        => $opn->item,
                    'c_id'                             => $opn->c_id,
                    'color'                            => $opn->color,
                    'purchase_order_receive_id'        => 0,
                    'purchase_order_receive_detail_id' => 0,
                    'item_quantity'                    => (float)$opn->opening_stock,
                    'item_rate'                        => $item_rate,
                );
            }
 
            // ── Challan row — actual rate (0.00 if not set) ───────────────────
            if ($sum_challan > 0) {
                $data_array[] = array(
                    'im_id'                            => $opn->im_id,
                    'id_id'                            => $opn->id_id,
                    'item_name'                        => $opn->item,
                    'c_id'                             => $opn->c_id,
                    'color'                            => $opn->color,
                    'purchase_order_receive_id'        => 0,
                    'purchase_order_receive_detail_id' => 0,
                    'item_quantity'                    => $sum_challan,
                    'item_rate'                        => $challan_item_rate, // actual challan rate
                );
            }
 
            // ── Stock In row ──────────────────────────────────────────────────
            if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                $data_array[] = array(
                    'im_id'                            => $opn->im_id,
                    'id_id'                            => $opn->id_id,
                    'item_name'                        => $opn->item,
                    'c_id'                             => $opn->c_id,
                    'color'                            => $opn->color,
                    'purchase_order_receive_id'        => 0,
                    'purchase_order_receive_detail_id' => 0,
                    'item_quantity'                    => $sum_stock_in,
                    'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                );
            }
 
        } else {
 
            // No opening stock
            $result_opening_stock1 = $this->db
                ->select('item_master.im_id, item_master.item, colors.c_id, colors.color,
                          item_dtl.opening_stock, item_dtl.opening_rate, item_dtl.id_id')
                ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))
                ->result();
 
            if (!empty($result_opening_stock1)) {
                $opn1 = $result_opening_stock1[0];
 
                // ── Challan row ───────────────────────────────────────────────
                if ($sum_challan > 0) {
                    $challan_remaining = $sum_challan - $total_issue_quantity;
                    if ($challan_remaining > 0) {
                        $data_array[] = array(
                            'im_id'                            => $opn1->im_id,
                            'id_id'                            => $opn1->id_id,
                            'item_name'                        => $opn1->item,
                            'c_id'                             => $opn1->c_id,
                            'color'                            => $opn1->color,
                            'purchase_order_receive_id'        => 0,
                            'purchase_order_receive_detail_id' => 0,
                            'item_quantity'                    => $challan_remaining,
                            'item_rate'                        => $challan_item_rate, // actual challan rate
                        );
                    }
                }
 
                // ── Stock In row ──────────────────────────────────────────────
                if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                    $qnty = $sum_stock_in - $total_issue_quantity;
                    if ($qnty > 0) {
                        $data_array[] = array(
                            'im_id'                            => $opn1->im_id,
                            'id_id'                            => $opn1->id_id,
                            'item_name'                        => $opn1->item,
                            'c_id'                             => $opn1->c_id,
                            'color'                            => $opn1->color,
                            'purchase_order_receive_id'        => 0,
                            'purchase_order_receive_detail_id' => 0,
                            'item_quantity'                    => $qnty,
                            'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                        );
                    }
                }
            }
        }
 
    } else {
 
        // ── Has purchase receipts ─────────────────────────────────────────────
        $result = $this->db
            ->select('purchase_order_receive_detail.*, item_master.im_id, item_master.item,
                      colors.c_id, colors.color, item_dtl.opening_stock, item_dtl.opening_rate')
            ->join('purchase_order_receive',
                'purchase_order_receive.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id', 'left')
            ->join('item_dtl', 'item_dtl.id_id = purchase_order_receive_detail.id_id', 'left')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->where('purchase_order_receive.purchase_order_receive_date <=', $issue_date_add)
            ->order_by('purchase_order_receive_detail.purchase_order_receive_detail_id')
            ->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail.id_id' => $id_id))
            ->result();
 
        $result_mat_issue_row = $this->db
            ->select('material_issue_detail.*')
            ->join('material_issue', 'material_issue.material_issue_id = material_issue_detail.material_issue_id', 'left')
            ->where('material_issue.material_issue_date <=', $issue_date_add)
            ->order_by('material_issue.material_issue_date')
            ->get_where('material_issue_detail', array('material_issue_detail.id_id' => $id_id))
            ->result();
 
        $result_opening_stock = $this->db
            ->select('item_master.im_id, item_master.item, colors.c_id, colors.color,
                      item_dtl.opening_stock, item_dtl.opening_rate, item_dtl.id_id')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->where('item_dtl.opening_stock >', 0)
            ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))
            ->result();
 
        $result_opening_stock1 = $this->db
            ->select('item_master.im_id, item_master.item, colors.c_id, colors.color,
                      item_dtl.opening_stock, item_dtl.opening_rate, item_dtl.id_id')
            ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
            ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
            ->get_where('item_dtl', array('item_dtl.id_id' => $id_id))
            ->result();
 
        if (!empty($result_opening_stock)) {
            $opn = $result_opening_stock[0];
 
            if (!empty($result_mat_issue_row)) {
 
                $result_mat_issue_sum = $this->db
                    ->select_sum('issue_quantity')
                    ->join('material_issue', 'material_issue.material_issue_id = material_issue_detail.material_issue_id', 'left')
                    ->where('material_issue.material_issue_date <=', $issue_date_add)
                    ->order_by('material_issue.material_issue_date')
                    ->get_where('material_issue_detail', array('material_issue_detail.id_id' => $id_id))
                    ->result();
 
                $result_mat_issue_without_purchase_row = $this->db
                    ->select_sum('issue_quantity')
                    ->join('material_issue', 'material_issue.material_issue_id = material_issue_detail.material_issue_id', 'left')
                    ->where('material_issue.material_issue_date <=', $issue_date_add)
                    ->where('material_issue_detail.purchase_order_receive_detail_id', 0)
                    ->order_by('material_issue.material_issue_date')
                    ->get_where('material_issue_detail', array('material_issue_detail.id_id' => $id_id))
                    ->result();
 
                $result_mat_issue_without_purchase = (!empty($result_mat_issue_without_purchase_row) && $result_mat_issue_without_purchase_row[0]->issue_quantity !== null)
                    ? ((float)$result_mat_issue_without_purchase_row[0]->issue_quantity + $sum_plating)
                    : $sum_plating;
 
                if ((float)$opn->opening_stock > $total_issue_quantity) {
                    $qnty = (float)$opn->opening_stock - $result_mat_issue_without_purchase;
                    if ($qnty > 0) {
                        $data_array[] = array(
                            'im_id'                            => $opn->im_id,
                            'id_id'                            => $opn->id_id,
                            'item_name'                        => $opn->item,
                            'c_id'                             => $opn->c_id,
                            'color'                            => $opn->color,
                            'purchase_order_receive_id'        => 0,
                            'purchase_order_receive_detail_id' => 0,
                            'item_quantity'                    => $qnty,
                            'item_rate'                        => (float)$opn->opening_rate,
                        );
                    }
 
                    // ── Challan row ───────────────────────────────────────────
                    if ($sum_challan > 0) {
                        $data_array[] = array(
                            'im_id'                            => $opn->im_id,
                            'id_id'                            => $opn->id_id,
                            'item_name'                        => $opn->item,
                            'c_id'                             => $opn->c_id,
                            'color'                            => $opn->color,
                            'purchase_order_receive_id'        => 0,
                            'purchase_order_receive_detail_id' => 0,
                            'item_quantity'                    => $sum_challan,
                            'item_rate'                        => $challan_item_rate, // actual challan rate
                        );
                    }
 
                    // ── Stock In row ──────────────────────────────────────────
                    if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                        $without_purchase_stock_quantity = ((float)$opn->opening_stock + $sum_stock_in) - $result_mat_issue_without_purchase;
                        $qnty1 = $without_purchase_stock_quantity - $qnty;
                        if ($qnty1 > 0) {
                            $data_array[] = array(
                                'im_id'                            => $result_opening_stock1[0]->im_id,
                                'id_id'                            => $result_opening_stock1[0]->id_id,
                                'item_name'                        => $result_opening_stock1[0]->item,
                                'c_id'                             => $result_opening_stock1[0]->c_id,
                                'color'                            => $result_opening_stock1[0]->color,
                                'purchase_order_receive_id'        => 0,
                                'purchase_order_receive_detail_id' => 0,
                                'item_quantity'                    => $qnty1,
                                'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                            );
                        }
                    }
 
                } else {
 
                    // ── Challan row ───────────────────────────────────────────
                    if ($sum_challan > 0) {
                        $data_array[] = array(
                            'im_id'                            => $opn->im_id,
                            'id_id'                            => $opn->id_id,
                            'item_name'                        => $opn->item,
                            'c_id'                             => $opn->c_id,
                            'color'                            => $opn->color,
                            'purchase_order_receive_id'        => 0,
                            'purchase_order_receive_detail_id' => 0,
                            'item_quantity'                    => $sum_challan,
                            'item_rate'                        => $challan_item_rate, // actual challan rate
                        );
                    }
 
                    if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                        $without_purchase_stock_quantity = ((float)$opn->opening_stock + $sum_stock_in) - $result_mat_issue_without_purchase;
                        if ($without_purchase_stock_quantity > 0) {
                            $data_array[] = array(
                                'im_id'                            => $result_opening_stock1[0]->im_id,
                                'id_id'                            => $result_opening_stock1[0]->id_id,
                                'item_name'                        => $result_opening_stock1[0]->item,
                                'c_id'                             => $result_opening_stock1[0]->c_id,
                                'color'                            => $result_opening_stock1[0]->color,
                                'purchase_order_receive_id'        => 0,
                                'purchase_order_receive_detail_id' => 0,
                                'item_quantity'                    => $without_purchase_stock_quantity,
                                'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                            );
                        }
                    }
                }
 
            } else {
 
                // No material issues
                $qnty = (float)$opn->opening_stock - $total_issue_quantity;
                if ($qnty > 0) {
                    $data_array[] = array(
                        'im_id'                            => $opn->im_id,
                        'id_id'                            => $opn->id_id,
                        'item_name'                        => $opn->item,
                        'c_id'                             => $opn->c_id,
                        'color'                            => $opn->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $qnty,
                        'item_rate'                        => (float)$opn->opening_rate,
                    );
                }
 
                // ── Challan row ───────────────────────────────────────────────
                if ($sum_challan > 0) {
                    $data_array[] = array(
                        'im_id'                            => $opn->im_id,
                        'id_id'                            => $opn->id_id,
                        'item_name'                        => $opn->item,
                        'c_id'                             => $opn->c_id,
                        'color'                            => $opn->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $sum_challan,
                        'item_rate'                        => $challan_item_rate, // actual challan rate
                    );
                }
 
                // ── Stock In row ──────────────────────────────────────────────
                if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                    $data_array[] = array(
                        'im_id'                            => $opn->im_id,
                        'id_id'                            => $opn->id_id,
                        'item_name'                        => $opn->item,
                        'c_id'                             => $opn->c_id,
                        'color'                            => $opn->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $sum_stock_in,
                        'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                    );
                }
            }
 
        } else {
 
            // No opening stock
            // ── Challan row ───────────────────────────────────────────────────
            if ($sum_challan > 0) {
                $challan_remaining = $sum_challan - $total_issue_quantity;
                if ($challan_remaining > 0 && !empty($result_opening_stock1)) {
                    $data_array[] = array(
                        'im_id'                            => $result_opening_stock1[0]->im_id,
                        'id_id'                            => $result_opening_stock1[0]->id_id,
                        'item_name'                        => $result_opening_stock1[0]->item,
                        'c_id'                             => $result_opening_stock1[0]->c_id,
                        'color'                            => $result_opening_stock1[0]->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $challan_remaining,
                        'item_rate'                        => $challan_item_rate, // actual challan rate
                    );
                }
            }
 
            // ── Stock In row ──────────────────────────────────────────────────
            if (!empty($sum_stock_in_row) && $sum_stock_in > 0) {
                $qnty = $sum_stock_in - $total_issue_quantity;
                if ($qnty > 0 && !empty($result_opening_stock1)) {
                    $data_array[] = array(
                        'im_id'                            => $result_opening_stock1[0]->im_id,
                        'id_id'                            => $result_opening_stock1[0]->id_id,
                        'item_name'                        => $result_opening_stock1[0]->item,
                        'c_id'                             => $result_opening_stock1[0]->c_id,
                        'color'                            => $result_opening_stock1[0]->color,
                        'purchase_order_receive_id'        => 0,
                        'purchase_order_receive_detail_id' => 0,
                        'item_quantity'                    => $qnty,
                        'item_rate'                        => (float)$sum_stock_in_row->item_rate,
                    );
                }
            }
        }
 
        // ── Purchase receipt rows ─────────────────────────────────────────────
        $newly_added_quantity_purchase_order = 0;
        $item_quantity                       = 0;
 
        foreach ($result as $r) {
            $newly_added_quantity_purchase_order += (float)$r->item_quantity;
 
            if (($newly_added_quantity_purchase_order + $sum_stock_in + $sum_challan + $opening_stock_quantity1) <= $total_issue_quantity) {
                continue;
            }
 
            $newly_added_quantity = 0;
            foreach ($data_array as $da) {
                $newly_added_quantity += (float)$da['item_quantity'];
            }
 
            $new_quantity  = $newly_added_quantity - ($opening_stock_quantity1 + $sum_stock_in + $sum_challan - $total_issue_quantity);
            $item_quantity += (float)$r->item_quantity;
 
            if ($new_quantity < 0) {
                $actual_quantity = $new_quantity + $item_quantity;
                if ($actual_quantity > 0) {
                    $data_array[] = array(
                        'im_id'                            => $r->im_id,
                        'id_id'                            => $r->id_id,
                        'item_name'                        => $r->item,
                        'c_id'                             => $r->c_id,
                        'color'                            => $r->color,
                        'purchase_order_receive_id'        => $r->purchase_order_receive_id,
                        'purchase_order_receive_detail_id' => $r->purchase_order_receive_detail_id,
                        'item_quantity'                    => (float)$r->item_quantity,
                        'item_rate'                        => (float)$r->item_rate,
                    );
                }
            } else {
                $item_quantity = ($newly_added_quantity_purchase_order + $opening_stock_quantity1 + $sum_stock_in + $sum_challan) - $total_issue_quantity;
                $data_array[] = array(
                    'im_id'                            => $r->im_id,
                    'id_id'                            => $r->id_id,
                    'item_name'                        => $r->item,
                    'c_id'                             => $r->c_id,
                    'color'                            => $r->color,
                    'purchase_order_receive_id'        => $r->purchase_order_receive_id,
                    'purchase_order_receive_detail_id' => $r->purchase_order_receive_detail_id,
                    'item_quantity'                    => $item_quantity,
                    'item_rate'                        => (float)$r->item_rate,
                );
            }
        }
 
    } // end else (has purchase receipts)
 
    // ══════════════════════════════════════════════════════════════════════════
    // BUILD $preview_data from $data_array
    // ══════════════════════════════════════════════════════════════════════════
    $a = (float)$issue_quantity_preview;
 
    if (!empty($data_array)) {
        for ($i = 0; $i < count($data_array); $i++) {
            if ($a <= 0) break;
 
            $b         = (float)($data_array[$i]['item_quantity'] ?? 0);
            $item_rate = (float)($data_array[$i]['item_rate']     ?? 0);
 
            if ($b <= 0) continue; // skip zero rows
 
            $preview             = new stdClass();
            $preview->item_rate  = $item_rate;
 
            if ($b <= $a) {
                $preview->consumed   = $b;
                $preview->total_rate = $item_rate * $b;
                $a                   = $a - $b;
            } else {
                $preview->consumed   = $a;
                $preview->total_rate = $item_rate * $a;
                $a                   = 0;
            }
 
            $preview->im_id                            = $data_array[$i]['im_id'];
            $preview->id_id                            = $data_array[$i]['id_id'];
            $preview->item_name                        = $data_array[$i]['item_name'];
            $preview->c_id                             = $data_array[$i]['c_id'];
            $preview->color                            = $data_array[$i]['color'];
            $preview->purchase_order_receive_id        = $data_array[$i]['purchase_order_receive_id'];
            $preview->purchase_order_receive_detail_id = $data_array[$i]['purchase_order_receive_detail_id'];
 
            $preview_data[] = $preview;
        }
    }
 
    $data['preview_data'] = $preview_data;
    $data['status']       = !empty($preview_data);
 
    return $data;
}

    public function all_items_on_supp_purchase_order() {
        $sup_id = $this->input->post('sup_id');

        $this->db->select('supp_purchase_order_detail.id_id, supp_purchase_order_detail.item_qty as pod_quantity, supp_purchase_order_detail.item_rate as pod_rate, supp_purchase_order_detail.total_amount as pod_total, item_master.item as item_name, item_master.im_code, units.unit, colors.color');
        $this->db->join('item_dtl', 'item_dtl.id_id = supp_purchase_order_detail.id_id', 'left');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('units', 'units.u_id = item_master.u_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('supp_purchase_order_detail', array('supp_purchase_order_detail.status' => '1', 'supp_purchase_order_detail.sup_id' => $sup_id))->result_array();
    }

    public function ajax_all_colors_on_item_master() {
        $item_id = $this->input->post('item_id');
        $this->db->select('item_dtl.id_id as item_dtl_id, colors.*');
        $this->db->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        return $this->db->get_where('item_dtl', array('item_dtl.status' => '1', 'item_dtl.im_id' => $item_id, 'color <>' => null))->result_array();
    }

    public function ajax_get_remaining_item_quantity() {
        $id_id_add = $this->input->post('id_id_add');
        $po_id = $this->input->post('po_id');

        $item_quantity = 0;

        $item_quantity1 = $this->db->select_sum('item_quantity')->get_where('stock_in_detail', array('id_id' => $id_id_add, 'po_id' => $po_id))->result()[0]->item_quantity;
        if ($item_quantity1 > 0) {
            $item_quantity = $item_quantity1;
        }

        return $item_quantity;
    }

    public function ajax_all_purchase_order() {
        $data = array();
        $pur_order_date = $this->input->post('pur_order_date');
        $rs = $this->db->get_where('purchase_order', array('purchase_order.po_date >= ' => $pur_order_date, 'purchase_order.status' => '1'))->num_rows();
        $all_po = $this->db->get_where('purchase_order', array('purchase_order.po_date >= ' => $pur_order_date, 'purchase_order.status' => '1'))->result_array();

        if ($rs > 0) {
            $data['status'] = true;
            $data['all_po'] = $all_po;
        } else {
            $data['status'] = false;
            $data['message'] = 'No Purchase order available';
            $data['all_po'] = array();
        }
        return $data;
    }

    public function form_add_material_issue_details() {
        $mat_is_id = $this->input->post('mat_is_id');
        $material_issue_id = $this->input->post('material_issue_id');
        $issue_date = date('Y-m-d', strtotime($this->input->post('material_issue_date1')));
        $im_id = $this->input->post('im_id');
        $id_id_hidden = $this->input->post('id_id_hidden');
        $c_id = $this->input->post('c_id');
         $issue_quantity_preview = $this->input->post('issue_quantity_preview');
         $co_id = $this->input->post('co_id');
         $issue_quantity_enter = $this->input->post('issue_quantity_enter');
         $remain_quantity_for_material_issue_new = ((float)$issue_quantity_preview - (float)$issue_quantity_enter);

        //Array
        $c_id = $this->input->post('c_id');
        $issue_quantity = $this->input->post('issue_quantity');
        $issue_rate = $this->input->post('issue_rate');
        $total_amount = $this->input->post('total_amount');
        $id_id = $this->input->post('id_id');
        $im_id = $this->input->post('im_id');
        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');
        $tot_amount = 0;
        $tot_qnty = 0;
        // $remain_quantity_for_material_issue_new = 0;

        for ($i = 0; $i < sizeof($id_id); $i++) {

            $tot_amount = $tot_amount + $total_amount[$i];
            $tot_qnty = $tot_qnty + $issue_quantity[$i];

            $insertArray = array(
                'material_issue_id' => $material_issue_id,
                'purchase_order_receive_detail_id' => $purchase_order_receive_detail_id[$i],
                'id_id' => $id_id[$i],
                'im_id' => $im_id[$i],
                'c_id' => $c_id[$i],
                'co_id' => $co_id,
                'issue_quantity' => $issue_quantity[$i],
                'issue_rate' => $issue_rate[$i],
                'total_amount' => $total_amount[$i],
                'issue_date' => $issue_date,
                'user_id' => $this->session->user_id
            );

            $this->db->insert('material_issue_detail', $insertArray);
            $insert_id = $this->db->insert_id();

            // $remain_quantity_for_material_issue_old = $this->db->select('remain_quantity_for_material_issue')->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id[$i]))->result()[0]->remain_quantity_for_material_issue;

        }//end for
        
        // echo $remain_quantity_for_material_issue_new; die(); 
    if($purchase_order_receive_detail_id[0] != null) {
            
         $query = "UPDATE purchase_order_receive_detail
        INNER JOIN
    purchase_order_receive ON purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id 
SET `remain_quantity_for_material_issue` = '$remain_quantity_for_material_issue_new' 
WHERE `purchase_order_receive`.`purchase_order_receive_date` <= '$issue_date' AND `id_id` = '$id_id[0]'";

    $this->db->query($query);        


           
            if ($remain_quantity_for_material_issue_new == 0) {

                $query1 = "UPDATE purchase_order_receive_detail
        INNER JOIN
    purchase_order_receive ON purchase_order_receive_detail.purchase_order_receive_id = purchase_order_receive_detail.purchase_order_receive_id 
SET `material_issue_status` = '1' 
WHERE `purchase_order_receive`.`purchase_order_receive_date` <= '$issue_date' AND `id_id` = '$id_id[0]'";

    $this->db->query($query1);

            }
        }
         // echo $this->db->last_query(); die();
           $prev_tot = $this->db->select('total_value')->get_where('material_issue', array('material_issue_id' => $mat_is_id))->result()[0]->total_value;
           $total_amount = ((float)$prev_tot + (float)$tot_amount);
        $update_array_tot = array(
            'total_value' => $total_amount
        );
        $this->db->update('material_issue', $update_array_tot, array('material_issue_id' => $mat_is_id));
        $data['insert_id'] = $insert_id;

        if ($insert_id > 0) {
            $data['type'] = 'success';
            $data['tot_amount'] = $tot_amount;
            $data['msg'] = 'Material issue details added successfully.';
        } else {
            $data['type'] = 'error';
            $data['msg'] = 'Data Insert Error';
        }
        // echo '<pre>', print_r($data), '</pre>';die;
        return $data;
    }

    public function ajax_fetch_material_issue_details_on_pk() {
        $material_issue_detail_id = $this->input->post('material_issue_detail_id');

        return $this->db->select('material_issue_detail.material_issue_detail_id, material_issue_detail.material_issue_id, material_issue_detail.purchase_order_receive_detail_id, material_issue_detail.issue_quantity, material_issue_detail.issue_rate, material_issue_detail.total_amount, colors.color, material_issue_detail.co_id, colors.c_code, item_master.item')
                        ->join('item_dtl', 'item_dtl.id_id = material_issue_detail.id_id', 'left')
                        ->join('colors', 'colors.c_id = item_dtl.c_id', 'left')
                        ->join('item_master', 'item_master.im_id = item_dtl.im_id', 'left')
                        ->get_where('material_issue_detail', array('material_issue_detail.material_issue_detail_id' => $material_issue_detail_id))->result();
    }

    public function purchase_order_print_with_code($po_id) {

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
        return array('page' => 'purchase_order/purchase_order_print_with_code_v', 'data' => $data);
    }

    public function purchase_order_print_without_code($po_id) {

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
        return array('page' => 'purchase_order/purchase_order_print_without_code_v', 'data' => $data);
    }

    public function form_edit_material_issue_details() {

        $old_array = $this->db->get_where('material_issue_detail', array('material_issue_detail_id' => $this->input->post('material_issue_detail_id')))->row();

        // echo '<pre>', print_r($old_array), '</pre>'; die();

        $this->log_before_update($old_array, $this->input->post('material_issue_detail_id'), 'material_issue_detail');

        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id_edit');
        $material_issue_detail_id = $this->input->post('material_issue_detail_id');
        $issue_quantity_new = $this->input->post('issue_quantity_edit');
        $co_id = $this->input->post('co_id_edit');
        $issue_quantity_old = $this->input->post('issue_quantity_hidden_edit');
        $total_amount_edit = $this->input->post('total_amount_edit');

        $updateArray = array(
            'issue_quantity' => $issue_quantity_new,
            'issue_rate' => $this->input->post('issue_rate_edit'),
            'co_id' => $co_id,
            'total_amount' => $total_amount_edit,
            'user_id' => $this->session->user_id
        );

        $this->db->update('material_issue_detail', $updateArray, array('material_issue_detail_id' => $material_issue_detail_id));

        //Update purchase order receive detail table
        $result = $this->db->select('remain_quantity_for_material_issue')->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id))->result()[0];

        $remain_quantity_for_material_issue = $result->remain_quantity_for_material_issue;

        $remain_quantity_for_material_issue_new = ($remain_quantity_for_material_issue + $issue_quantity_old - $issue_quantity_new);

        if ($remain_quantity_for_material_issue_new > 0) {
            $material_issue_status = 0;
        } else {
            $material_issue_status = 1;
        }

        $updateArray = array(
            'remain_quantity_for_material_issue' => $remain_quantity_for_material_issue_new,
            'material_issue_status' => $material_issue_status,
            'user_id' => $this->session->user_id
        );
        $this->db->update('purchase_order_receive_detail', $updateArray, array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id));


        $data['type'] = 'success';
        $data['msg'] = 'Material Issue details updated successfully.';
        return $data;
    }

    public function form_edit_delivery_sgst_cgst_value() {

        $purchase_order_receive_id = $this->input->post('purchase_order_receive_id');

        $updateArray = array(
            'total_amount' => $this->input->post('total_amount'),
            'delivery_charge' => $this->input->post('delivery_charge'),
            'sgst_percent' => $this->input->post('sgst_percent'),
            'cgst_percent' => $this->input->post('cgst_percent'),
            'delivery_sgst_cgst_amount' => $this->input->post('delivery_sgst_cgst_amount'),
            'net_amount' => $this->input->post('net_amount'),
            'user_id' => $this->session->user_id
        );

        $this->db->update('stock_in', $updateArray, array('purchase_order_receive_id' => $purchase_order_receive_id));

        $data['type'] = 'success';
        $data['msg'] = 'Net Amount updated successfully.';
        return $data;
    }

    // ---------------------------------------working-----------------------------------------



    public function ajax_unique_purchase_order_number() {
        $order_no = $this->input->post('order_no');
        $rs = $this->db->get_where('purchase_order', array('co_no' => $order_no))->num_rows();
        // echo $this->db->last_query();die;

        if ($rs != '0') {
            $data = 'Order no. already exists.';
        } else {
            $data = 'true';
        }

        return $data;
    }

    public function delete_receive_purchase_order_details() {
        $tab = $this->input->post('tab');
        $ref_table = $this->input->post('ref_tab');
        $pk_name = $this->input->post('pk_name');
        $pk_value = $this->input->post('pk_value');

        $this->db->where($pk_name, $pk_value)->delete($tab);
        $this->db->where($pk_name, $pk_value)->delete($ref_table);

        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Stock In Successfully Deleted';
        return $data;
    }

    public function delete_material_issue_details_list() {
        $tab = $this->input->post('tab');
        $tab_pk = $this->input->post('tab_pk');
        $data_pk = $this->input->post('data_pk');

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

        $reference_tab = $this->input->post('reference_tab');
        $reference_pk = $this->input->post('reference_pk');
        $reference_data_pk = $this->input->post('reference_data_pk');

        $issue_quantity = $this->input->post('issue_quantity');
        $total_amount = $this->input->post('total_amount');
        $purchase_order_receive_detail_id = $this->input->post('purchase_order_receive_detail_id');

        $purchase_order_receive_id = $reference_data_pk;

        if($purchase_order_receive_detail_id != 0) {
        $result = $this->db->select('remain_quantity_for_material_issue')->get_where('purchase_order_receive_detail', array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id))->result()[0];

        $remain_quantity_for_material_issue = $result->remain_quantity_for_material_issue;
        } else {
          
        $remain_quantity_for_material_issue = 0;
            
        }

        $remain_quantity_for_material_issue_new = ($remain_quantity_for_material_issue + $issue_quantity);

        $updateArray = array(
            'remain_quantity_for_material_issue' => $remain_quantity_for_material_issue_new,
            'material_issue_status' => 0,
            'user_id' => $this->session->user_id
        );
        $this->db->update('purchase_order_receive_detail', $updateArray, array('purchase_order_receive_detail_id' => $purchase_order_receive_detail_id));

        $result_tot = $this->db->select('total_value')->get_where('material_issue', array('material_issue_id' => $reference_data_pk))->result()[0]->total_value;
     
      $total_amount;

      $result_tot_new = ($result_tot - $total_amount);
    
        $updateArrayTot = array(
            'total_value' => $result_tot_new
        );

        $this->db->update('material_issue', $updateArrayTot, array('material_issue_id' => $reference_data_pk));

        $this->db->where($tab_pk, $data_pk)->delete($tab);


        $data['title'] = 'Deleted!';
        $data['type'] = 'success';
        $data['msg'] = 'Material issue list Successfully Deleted';
        return $data;
    }

    // purchase ORDER ENDS 
}
