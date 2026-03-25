<?php
/**
 * Coded by: Pran Krishna Das
 * Social: www.fb.com/pran93
 * CI: 3.0.6
 * Date: 11-03-2020
 * Time: 09:30
 * Last uploaded on 01-01-2021 at 09:55pm
 */

class Payroll_m extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }

    private function _dept_wise_module_permission($module_id,$user){

            $dept_id = $this->db->get_where('user_details', array('user_id' => $user))->result()[0]->user_dept;
            if($dept_id != NULL){
                $nr = $this->db->where('module_permission_id', $module_id)->where('FIND_IN_SET('.$dept_id.', dept_id) !=', 0)->get('module_permission')->num_rows();
                // echo $this->db->last_query(); die;
                if($nr == 0){
                    # show-all
                    return 'show';
                }else{
                    # filter according to dept id
                    return $dept_id;
                }
            }else{
                return 'show';
            }
            
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
    
    public function fetch_permission_matrix($user_id, $m_id){
        $user_id = $this->session->user_id;

        $is_initialised = $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => $m_id))->num_rows();

        if($is_initialised > 0){
            
            $blocked_by_admin = $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => $m_id, 'block_permission' => 1))->num_rows();

            if($blocked_by_admin > 0){
                $this->session->set_flashdata('title', 'Blocked or Not-set!');
                $this->session->set_flashdata('msg', 'Permission not set. Please contact admin for permission.');
                redirect(base_url('admin/dashboard'));
            }else{
                return $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => $m_id))->result();    
            }

        }else{
            
            return $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => $m_id))->result();

        }
    }

    public function advance_list_m() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Payroll/advance_list'));
            $crud->set_theme('datatables');
            $crud->set_subject('Advance');
            $crud->where('advance.user_id !=', 13);    
            $crud->set_table('advance');
            $crud->unset_read();
            $crud->unset_clone();
            
            $this->fetch_permission_matrix($user_id, $m_id = 42);
            $uvp = $this->_user_wise_view_permission(42, $user_id);

            $this->table_name = 'advance';
            $this->pk_field_name = 'advance_id';

            $crud->set_relation('emp_id', 'employees', 'name', array('user_id !=' => 13));

            $crud->columns('advance_name','emp_id','date', 'amount', 'monthly_advance_adjustment');
            $crud->fields('advance_name','emp_id','date', 'amount', 'monthly_advance_adjustment', 'user_id');
            $crud->required_fields('advance_name','emp_id','date', 'amount', 'monthly_advance_adjustment');
            $crud->unique_fields(array('advance_name'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('advance_name', 'Advance No.');
            $crud->display_as('emp_id', 'Employee Name');
            $crud->display_as('date', 'Advance Date');
            $crud->display_as('amount', 'Advance Amount');
            $crud->display_as('monthly_advance_adjustment', 'Monthly Advance Adjustment');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Advance';
            $output->section_heading = 'Advance <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Advance';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function emp_salary_list_m() {
        $user_id = $this->session->user_id;
        try{
        $crud = new grocery_CRUD();
        // $crud->set_theme('flexigrid');
        $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Payroll/emp_salary_list'));
            $crud->set_theme('datatables');
            $crud->set_subject('Salary');
            if($user_id != 13) {
            $crud->where('salary.USER_ID !=', 13);    
            }
            $crud->set_table('salary');
            $crud->unset_read();
            $crud->unset_clone();
            $crud->unset_edit();
            
            $this->fetch_permission_matrix($user_id, $m_id = 43);
            $uvp = $this->_user_wise_view_permission(43, $user_id);

            $this->table_name = 'salary';
            $this->pk_field_name = 'CODE';
        
        $crud->unset_fields('CREATED_DATE');
        $crud->columns('MON','EMPCODE', 'NET', 'T4', 'T5', 'T6', 'T7', 'LOAN');
        
        // $crud->columns('DATE', 'PARTY_SEQ', 'INVOICE_NO', 'AWB_NO', 'TOTAL_QNTY', 'TOTAL_VALUE', 'TOTAL_FOR_VAL');
        // $crud->display_as('VNAME', 'Voucher No.');
        // $crud->display_as('EMPCODE', 'Employee Name');
        // $crud->display_as('DT', 'Voucher Date');
        // $crud->display_as('AMT', 'Voucher Amount');
        $crud->display_as('T4', 'Casual Leave');
        $crud->display_as('T5', 'Earn Leave');
        $crud->display_as('T6', 'ESI Leave');
        $crud->display_as('T7', 'Absent');
        $crud->display_as('LOAN', 'Advance Deduc');
        
        // $crud->callback_before_delete(array($this,'cascade_delete_courier'));
        
        $crud->set_relation('EMPCODE', 'employees', '{e_code} - {name}');
        $crud->order_by('CODE','desc');

        $crud->add_action('Edit', '', '','ui-icon-pencil',array($this,'set_edit_path'));

        $output = $crud->render();
        //rending extra value to $output
            $output->tab_title = 'Employee Salary';
            $output->section_heading = 'Employee Salary <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Employee Salary';
            $output->add_button = '';

        return array('page'=>'payroll/salary_list', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    function set_edit_path($primary_key , $row)
{
    return base_url('admin/payroll-emp-salary-edit').'/'.$row->CODE;
}

function set_print_path($primary_key , $row)
{
    return base_url('admin/payroll-emp-salary-print').'/'.$row->CODE;
}

    // public function emp_salary_add_m() {
    //     $data[] = '';
    //     $salary_rowss = $this->db->get_where('salary', array('MON' =>  $this->input->post('month'), 'EMPCODE' => $this->input->post('emp_id')))->num_rows();
    //     if($this->input->post()){
    //         if($salary_rowss == 0) {

    //             $master_salary_data = $this->db->get_where('employees', array('e_id' => $this->input->post('emp_id')))->row();

    //             $salary_det = array(
    //             'MON' =>  $this->input->post('month'),
    //             'EMPCODE' => $this->input->post('emp_id'),

    //             'MASTER_BASIC' => $master_salary_data->basic_pay,
    //             'MASTER_DA' => $master_salary_data->da_amout,
    //             'MASTER_HRA' => $master_salary_data->hra_amount,
    //             'MASTER_CONV' => $master_salary_data->convenience,
    //             'MASTER_MED' => $master_salary_data->medical_allowance,
    //             'MASTER_OA' => $master_salary_data->special_allowance,

    //             'BASIC' => $this->input->post('abasic'),
    //             'DA' => $this->input->post('ada'),
    //             'HRA' => $this->input->post('ahra'),
    //             'CONV' => $this->input->post('con'),
    //             'MED' => $this->input->post('ma'),
    //             'OA' => $this->input->post('oa'),
    //             'OT' => $this->input->post('oh'),
    //             'OTAMT' => $this->input->post('oam'),
                
    //             'PFPER' => $this->input->post('pfper'),
    //             'PFAMT' => $this->input->post('pfamnt'),
    //             'ESIPER' => $this->input->post('esiper'),
    //             'ESIAMT' => $this->input->post('esiamnt'),
    //             'TAX' => $this->input->post('ptax'),
    //             'INS' => $this->input->post('insur'),
    //             'LOAN' => $this->input->post('loan_adj'),
                
    //             'T1' => $this->input->post('wd'),
    //             'T2' => $this->input->post('adw'),
    //             'T3' => $this->input->post('hol'),
    //             'T4' => $this->input->post('cl'),
    //             'T5' => $this->input->post('el'),
    //             'T6' => $this->input->post('esil'),
    //             'T7' => $this->input->post('abs'),
    //             'T8' => $this->input->post('td'),
                
    //             'GROSS' => $this->input->post('gross'),
    //             'DEDUC' => $this->input->post('ded'),
    //             'NET' => $this->input->post('net'),
    //             'USER_ID' => $this->session->user_id
    //         );

    //         // echo '<pre>', print_r($salary_det), '</pre>'; die;

    //         $this->db->insert('salary', $salary_det);
    //         // echo $this->db->last_query(); die();
    //         $data['error'] = false;
    //         $data['success'] = true;
    //         }
    //     }

    //     if($this->input->post('savengo')){
    //         redirect(base_url('admin/payroll-emp-salary-list'));
    //     }
        
    //     $user_id = $this->session->user_id;
        
    //     if($user_id == 13) {
        
    //     $data['fetch_all_employee'] = $this->db->get('employees')->result();
        
    //     } else {
            
    //     $data['fetch_all_employee'] = $this->db->get_where('employees', array('user_id !=' => 13))->result();    
            
    //     }
    //     return array('page'=>'payroll/salary_add', 'data'=>$data);
    
    // }
    
    


public function emp_salary_add_m() {
    $data[] = '';
    
    if($this->input->post('month') && $this->input->post('emp_id')) {
        $salary_rowss = $this->db->get_where('salary', array('MON' =>  $this->input->post('month'), 'EMPCODE' => $this->input->post('emp_id')))->num_rows();
        
        if($salary_rowss == 0) {
            $master_salary_data = $this->db->get_where('employees', array('e_id' => $this->input->post('emp_id')))->row();
            $salary_det = array(
                'MON' =>  $this->input->post('month'),
                'EMPCODE' => $this->input->post('emp_id'),
                'MASTER_BASIC' => $master_salary_data->basic_pay,
                'MASTER_DA' => $master_salary_data->da_amout,
                'MASTER_HRA' => $master_salary_data->hra_amount,
                'MASTER_CONV' => $master_salary_data->convenience,
                'MASTER_MED' => $master_salary_data->medical_allowance,
                'MASTER_OA' => $master_salary_data->special_allowance,
                'BASIC' => $this->input->post('abasic'),
                'DA' => $this->input->post('ada'),
                'HRA' => $this->input->post('ahra'),
                'CONV' => $this->input->post('con'),
                'MED' => $this->input->post('ma'),
                'OA' => $this->input->post('oa'),
                'OT' => $this->input->post('oh'),
                'OTAMT' => $this->input->post('oam'),
                
                'PFPER' => $this->input->post('pfper'),
                'PFAMT' => $this->input->post('pfamnt'),
                'ESIPER' => $this->input->post('esiper'),
                'ESIAMT' => $this->input->post('esiamnt'),
                'TAX' => $this->input->post('ptax'),
                'INS' => $this->input->post('insur'),
                'LOAN' => $this->input->post('loan_adj'),
                
                'T1' => $this->input->post('wd'),
                'T2' => $this->input->post('adw'),
                'T3' => $this->input->post('hol'),
                'T4' => $this->input->post('cl'),
                'T5' => $this->input->post('el'),
                'T6' => $this->input->post('esil'),
                'T7' => $this->input->post('abs'),
                'T8' => $this->input->post('td'),
                
                'GROSS' => $this->input->post('gross'),
                'DEDUC' => $this->input->post('ded'),
                'NET' => $this->input->post('net'),
                'USER_ID' => $this->session->user_id
            );
            
            $this->db->insert('salary', $salary_det);
            $data['error'] = false;
            $data['success'] = true;
        }
    }
    
    if($this->input->post('savengo')){
        redirect(base_url('admin/payroll-emp-salary-list'));
    }
    
    // Don't load employees initially - they'll be loaded via AJAX
    $data['fetch_all_employee'] = array();
    
    return array('page'=>'payroll/salary_add', 'data'=>$data);
}


public function get_employees_for_month() {
    $month_data = $this->input->post('month');
    
    if(empty($month_data)) {
        return array(); // Return empty array instead of echoing
    }
    
    // Parse the month data (e.g., "October~31~10")
    $month_parts = explode('~', $month_data);
    $month_name = $month_parts[0];
    $month_num = str_pad($month_parts[2], 2, '0', STR_PAD_LEFT);
    $current_year = date('Y');
    
    // Create comparison date (last day of selected month)
    $compare_date = $current_year . '-' . $month_num . '-01';
    $last_day_of_month = date('Y-m-t', strtotime($compare_date));
    
    $user_id = $this->session->user_id;
    
    // Build query
    $this->db->select('e_id, name, e_code, termination_date');
    $this->db->from('employees');
    
    if($user_id != 13) {
        $this->db->where('user_id !=', 13);
    }
    
    // Filter active employees and by termination date
    $this->db->where('status', '1');
    $this->db->where("(
        termination_date IS NULL 
        OR termination_date = '0000-00-00' 
        OR termination_date = ''
        OR termination_date >= '$compare_date'
    )");
    
    $this->db->order_by('name', 'ASC');
    $result = $this->db->get()->result();
    
    return $result; // Return the result instead of echoing
}


    
    

    public function emp_search_on_id_m(){
        $id = $this->input->post('id');
        $res = $this->db->select('*, FLOOR(hra_amount) AS hra_amount')
                    ->join('departments', 'departments.d_id = employees.d_id', 'left')
                    ->get_where('employees', array('employees.e_id' => $id))->result();
        return $res;
    }

    public function emp_leave_on_id_m(){
        $id = $this->input->post('id');
        $count_resultvalue = $this->db
                    ->select('*')
                    ->get_where('salary', array('EMPCODE' => $id))->result();
                    if(count($count_resultvalue) > 0) {
        $res = $this->db
                    ->select('SUM(T4) AS all_cl, SUM(T5) AS all_el, cl_granted, el_granted')
                    ->join('employees', 'employees.e_id = salary.EMPCODE', 'left')
                    ->group_by('EMPCODE')
                    ->get_where('salary', array('EMPCODE' => $id))->result();
                    } else {
        $res = $this->db
                    ->select('0 AS all_cl, 0 AS all_el, cl_granted, el_granted')
                    ->group_by('e_id')
                    ->get_where('employees', array('e_id' => $id))->result();                
                    }
        return $res;
    }

    public function emp_advance_on_id_m(){
        $id = $this->input->post('id');
        // $res = $this->db->select('*, SUM(amount) as amount_total')
        //             ->get_where('advance', array('emp_id' => $id))->result();
        $query = "SELECT *, (SELECT SUM(amount) FROM `advance` WHERE `emp_id` = $id ORDER BY `advance_id` DESC) as amount_total 
        FROM `advance` WHERE emp_id = $id
        ORDER BY advance_id DESC";
        $res = $this->db->query($query)->result();
        return $res;
    }

    public function emp_advance_paid_on_id_m(){
        $id = $this->input->post('id');
        $res = $this->db
                    ->select('SUM(LOAN) AS loan_paid')
                    ->group_by('EMPCODE')
                    ->get_where('salary', array('EMPCODE' => $id))->result();
        return $res;
    }

    public function emp_salary_edit_m(){
        
        $user_id = $this->session->user_id;
        
        $data[] = '';
        $sal_id = $this->uri->segment(3);
                
        if($this->input->post()){
            $salary_det = array(
                'MON' =>  $this->input->post('month'),
                'EMPCODE' => $this->input->post('emp_id'),
                'BASIC' => $this->input->post('abasic'),
                'DA' => $this->input->post('ada'),
                'HRA' => $this->input->post('ahra'),
                'CONV' => $this->input->post('con'),
                'MED' => $this->input->post('ma'),
                'OA' => $this->input->post('oa'),
                'OT' => $this->input->post('oh'),
                'OTAMT' => $this->input->post('oam'),
                
                'PFPER' => $this->input->post('pfper'),
                'PFAMT' => $this->input->post('pfamnt'),
                'ESIPER' => $this->input->post('esiper'),
                'ESIAMT' => $this->input->post('esiamnt'),
                'TAX' => $this->input->post('ptax'),
                'INS' => $this->input->post('insur'),
                'LOAN' => $this->input->post('loan_adj'),
                
                'T1' => $this->input->post('wd'),
                'T2' => $this->input->post('adw'),
                'T3' => $this->input->post('hol'),
                'T4' => $this->input->post('cl'),
                'T5' => $this->input->post('el'),
                'T6' => $this->input->post('esil'),
                'T7' => $this->input->post('abs'),
                'T8' => $this->input->post('td'),
                
                'GROSS' => $this->input->post('gross'),
                'DEDUC' => $this->input->post('ded'),
                'NET' => $this->input->post('net'),
                'USER_ID' => $this->session->user_id
            );
            $this->db->update('salary', $salary_det, array('CODE' => $sal_id));
            $data['error'] = false;
            $data['success'] = true; 
        }

        if($this->input->post('savengo')){
            redirect(base_url('admin/payroll-emp-salary-list'));
        }
        
        if($user_id == 13) {
        
        $data['fetch_all_employee'] = $this->db->get('employees')->result();
        
        } else {
            
        $data['fetch_all_employee'] = $this->db->get_where('employees', array('user_id !=' => 13))->result();    
            
        }
        $data['fetch_all_sal_details'] = $this->db
                    ->join('employees', 'employees.e_id = salary.EMPCODE', 'left')
                    ->get_where('salary', array('salary.CODE' => $sal_id))->result();
        $data['sal_id'] = $sal_id;

        return array('page'=>'payroll/salary_edit', 'data'=>$data);            
    }

    public function emp_salary_print_m(){

        $user_id = $this->session->user_id;
        $data[] = '';
        $sal_id = $this->uri->segment(3);
        
        $this->load->model('Payroll_m');
        
        if($user_id == 13) {
        
        $data['fetch_all_employee'] = $this->db->get('employees')->result();
        
        } else {
            
        $data['fetch_all_employee'] = $this->db->get_where('employees', array('user_id !=' => 13))->result();    
            
        }
        $data['fetch_all_sal_details'] = $this->db
                    ->join('employees', 'employees.e_id = salary.EMPCODE', 'left')
                    ->get_where('salary', array('salary.CODE' => $sal_id))->result();

        return array('page'=>'payroll/salary_print', 'data'=>$data);

    }
    
    public function multiple_emp_pay_slip(){
        
        $user_id = $this->session->user_id;

        $this->load->model('Payroll_m');
        
        $data['departments'] = $this->db->get_where('departments', array('user_id !=' => 13))->result();
            
        $data['fetch_all_employee'] = $this->db->get_where('employees', array('user_id !=' => 13))->result();    
            
        
        if($this->input->post()) {
            $it_arr = $this->input->post('leather[]');
            $data['month'] = $this->input->post('month');
            $data['resultss'][] = $this->_fetch_multiple_pay_slip_detail($it_arr, $this->input->post('month'));
            $data['segment'] = 'emp_pay_slip_section';
            // echo '<pre>',print_r($data['results']),'</pre>'; die();
            return array('page'=>'reports/common_print_v','data'=>$data);
        }

        return array('page'=>'payroll/multiple_pay_slip', 'data'=>$data);
    }
    
    public function emp_on_dept_id(){
        $id = $this->input->post('gr_id');

        $user_id = $this->session->user_id;
        

            $query = "
                    SELECT
                        *
                    FROM
                        `employees`
                    WHERE
                        employees.d_id = $id";

            $res = $this->db->query($query)->result();
        
        return $res;
    }
    
    public function emp_on_dept_id_new_multiple(){
        $id = $this->input->post('gr_id');

        $user_id = $this->session->user_id;

            $query = "
                    SELECT
                        *
                    FROM
                        `employees`
                    WHERE
                        employees.d_id IN ($id)";

            $res = $this->db->query($query)->result();
        
        return $res;
    }
    
    public function emp_on_dept_id_new_multiples(){
        $id = implode(",",$this->input->post('gr_id'));

        $user_id = $this->session->user_id;

            $query = "
                    SELECT
                        *
                    FROM
                        `employees`
                    WHERE
                        employees.d_id IN ($id)";

            $res = $this->db->query($query)->result();
        
        return $res;
    }
    
    public function _fetch_multiple_pay_slip_detail($it_arr, $month) {
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
           $result = $this->db->join('employees', 'employees.e_id = salary.EMPCODE', 'left')
                     ->where_in('EMPCODE', $it_arr)
                     ->get_where('salary', array('MON' => $month))->result();   
            } else {
           $result = $this->db->join('employees', 'employees.e_id = salary.EMPCODE', 'left')
                     ->where_in('EMPCODE', $it_arr)
                     ->where('salary.USER_ID !=', 13)
                     ->get_where('salary', array('MON' => $month))->result();      
            }
   
        return $result;  
            
    }
    
    // public function payroll_emp_leave_from_holiday_list() {
        
    //     $user_id = $this->session->user_id;
    //     $month = $this->input->post('month');
    //     $year = $this->input->post('year');
    //     $new_row = str_pad(($month),2,"0",STR_PAD_LEFT);
        
    //     $new_month = $year."-".$new_row;
        
    //     $total_day = $this->db->select('*')->like('DATE(holiday_list.date)', $new_month)->get('holiday_list')->num_rows();
        
    //     return $total_day;  
            
    // }
    
    public function payroll_emp_leave_from_holiday_list() {
        $user_id = $this->session->user_id;
        $month = $this->input->post('month');
        $year = date('Y');
    
        $total_day = $this->db
            ->select('*')
            ->where('YEAR(date)', $year)
            ->where('MONTH(date)', $month)
            ->where('status', 1)
            ->get('holiday_list')
            ->num_rows();
            
    // echo $this->db->last_query();
// die();
        return $total_day;
    }

    
    public function if_salary_slip_made_or_not() {
        
        $user_id = $this->session->user_id;
        $id = $this->input->post('id');
        $month = $this->input->post('month');

        $no_row = $this->db->get_where('salary', array('EMPCODE' => $id, 'MON' => $month))->num_rows();
        
        return $no_row;  
            
    }
    
    public function dept_employee_list($q){
        if($q == 'register'){
            
            return $this->db
                ->join('departments','departments.d_id=division_audit','left')
                ->where('employees.working_status', 'Working')
                ->where('employees.user_id !=', 13)
                ->where('departments.department IS NOT NULL')
                ->order_by('departments.department, employees.name')
                ->get('employees')
                ->result();
        } else{
            return $this->db
                ->select('*')
                ->join('departments','departments.d_id=division_audit','left')
                ->order_by('departments.department, employees.name')
                ->where('employees.working_status', 'Working')
                ->get_where('employees', array('employees.user_id !=' => 13))->result();        
        }
        
    }
    
    public function fetch_payroll_reports() {
        
        $user_id = $this->session->user_id;
        
        $data = array();
        
        $this->fetch_permission_matrix($user_id, $m_id = 46);
        $uvp = $this->_user_wise_view_permission(46, $user_id);
        
        if($this->input->post("adl")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
                        foreach($it_arr as $i_a) {
            $data['result'][] = $this->_fetch_advance_ledger($mon,$i_a);
                        }
            $data['segment'] = 'payroll_reports_advance_ledger';
            // echo '<pre>', print_r($data['result']), '</pre>';die();
            return array('page'=>'reports/payroll/payroll_reports_advance_ledger','data'=>$data);
        }
        if($this->input->post("lv")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            
            $new_iter = implode(",", $it_arr);
            
            $sql='SELECT *
                    FROM employees
                    WHERE employees.e_id IN ('.$new_iter.') 
                    ORDER BY employees.name';
                    
                    /*AND MON LIKE "'.$mon.'%"*/
                    
            $data['result'] = $this->db->query($sql)->result();
            
            // if(count($res) > 0) {
            //     foreach($res as $r) {
            //         $data['result'][] = $this->_fetch_leave($mon,$r->e_id);
            //     }
            // }
            
            
            
            $data['segment'] = 'Payroll Report for Leave';
            // echo '<pre>', print_r($data), '</pre>';die();
            return array('page'=>'reports/payroll/payroll_reports_leave', 'data'=>$data);
        }
        if($this->input->post("esi")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            $new_iter = implode(",", $it_arr);
            
            $sql="SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
            CAST((employees.basic_pay+employees.da_amout) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
            salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN ($new_iter)
            ORDER BY employees.name";
        
            $res = $this->db->query($sql)->result();
            if(count($res) > 0) {
                foreach($res as $r) {
                    $data['result'][] = $this->_fetch_esi_pf1($mon,$r->e_id);
                }
            }
            
            $data['segment'] = 'payroll_esi_pf';
            return array('page'=>'reports/payroll/payroll_esi_pf','data'=>$data);
        }
        if($this->input->post("reg")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            $new_arr = array();
            
            $new_iter = implode(",", $it_arr);
            
            $sql="SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,
                    salary.MASTER_BASIC AS BASIC1,salary.MASTER_DA AS DA1,salary.MASTER_HRA AS HRA1,salary.MASTER_CONV AS CONV1,salary.MASTER_MED AS MA1,salary.MASTER_OA AS OA1,CAST((salary.MASTER_BASIC+salary.MASTER_DA+salary.MASTER_HRA+salary.MASTER_CONV+salary.MASTER_MED+salary.MASTER_OA) AS DECIMAL(11,2)) AS TOTAL1,
                    salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
                    FROM salary
                    INNER JOIN(employees)
                    ON(salary.EMPCODE=employees.e_id)
                    WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN ($new_iter)
                    ORDER BY employees.name";
        
            $res = $this->db->query($sql)->result();
            if(count($res) > 0) {
                foreach($res as $r) {
                    $data['result'][] = $this->_fetch_register($mon,$r->e_id);
                }
            }
            
            if($this->input->post('month') == 'January') {
                $data['month'] = 'Januray~31~1';
            } else if($this->input->post('month') == 'February') {
                $data['month'] = 'February~28~2';
            } else if($this->input->post('month') == 'March') {
                $data['month'] = 'March~31~3';
            } else if($this->input->post('month') == 'April') {
                $data['month'] = 'April~30~4';
            } else if($this->input->post('month') == 'May') {
                $data['month'] = 'May~31~5';
            } else if($this->input->post('month') == 'June') {
                $data['month'] = 'June~30~6';
            } else if($this->input->post('month') == 'July') {
                $data['month'] = 'July~31~7';
            } else if($this->input->post('month') == 'August') {
                $data['month'] = 'August~31~8';
            } else if($this->input->post('month') == 'September') {
                $data['month'] = 'September~30~9';
            } else if($this->input->post('month') == 'October') {
                $data['month'] = 'October~31~10';
            } else if($this->input->post('month') == 'November') {
                $data['month'] = 'November~30~11';
            } else if($this->input->post('month') == 'December') {
                $data['month'] = 'December~31~12';
            }
            $data['segment'] = 'payroll_register';
            return array('page'=>'reports/payroll/payroll_register','data'=>$data);
        }
        if($this->input->post("reg_excel")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            $new_arr = array();
            
            $new_iter = implode(",", $it_arr);
            
            $sql="SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,
                    salary.MASTER_BASIC AS BASIC1,salary.MASTER_DA AS DA1,salary.MASTER_HRA AS HRA1,salary.MASTER_CONV AS CONV1,salary.MASTER_MED AS MA1,salary.MASTER_OA AS OA1,CAST((salary.MASTER_BASIC+salary.MASTER_DA+salary.MASTER_HRA+salary.MASTER_CONV+salary.MASTER_MED+salary.MASTER_OA) AS DECIMAL(11,2)) AS TOTAL1,
                    salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
                    FROM salary
                    INNER JOIN(employees)
                    ON(salary.EMPCODE=employees.e_id)
                    WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN ($new_iter)
                    ORDER BY employees.name";
        
            $res = $this->db->query($sql)->result();
            if(count($res) > 0) {
                foreach($res as $r) {
                    $data['result'][] = $this->_fetch_register($mon,$r->e_id);
                }
            }
            
            if($this->input->post('month') == 'January') {
                $data['month'] = 'Januray~31~1';
            } else if($this->input->post('month') == 'February') {
                $data['month'] = 'February~28~2';
            } else if($this->input->post('month') == 'March') {
                $data['month'] = 'March~31~3';
            } else if($this->input->post('month') == 'April') {
                $data['month'] = 'April~30~4';
            } else if($this->input->post('month') == 'May') {
                $data['month'] = 'May~31~5';
            } else if($this->input->post('month') == 'June') {
                $data['month'] = 'June~30~6';
            } else if($this->input->post('month') == 'July') {
                $data['month'] = 'July~31~7';
            } else if($this->input->post('month') == 'August') {
                $data['month'] = 'August~31~8';
            } else if($this->input->post('month') == 'September') {
                $data['month'] = 'September~30~9';
            } else if($this->input->post('month') == 'October') {
                $data['month'] = 'October~31~10';
            } else if($this->input->post('month') == 'November') {
                $data['month'] = 'November~30~11';
            } else if($this->input->post('month') == 'December') {
                $data['month'] = 'December~31~12';
            }
            $data['segment'] = 'payroll_register_excel';
            return array('page'=>'reports/payroll/payroll_register_excel','data'=>$data);
        }
        if($this->input->post("attendance")){
            $it_arr = $this->input->post('leather[]');
            $new_iter = implode(",", $it_arr);
            
            $sql="SELECT employees.e_id
                    FROM employees
                    WHERE employees.e_id IN ($new_iter)
                    ORDER BY employees.name";
        
            $res = $this->db->query($sql)->result();
            if(count($res) > 0) {
                foreach($res as $r) {
                    $data['result'][] = $this->_fetch_attendance($r->e_id);
                }
            }
            
            $data['segment'] = 'payroll_attendance';
            return array('page'=>'reports/payroll/payroll_attendance','data'=>$data);
        }
        if($this->input->post("pf")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            
            $new_iter = implode(",", $it_arr);
            
            
                
                $sql="SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                CAST((employees.basic_pay+employees.da_amout) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
                FROM salary
                INNER JOIN(employees)
                ON(salary.EMPCODE=employees.e_id)
                WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN ($new_iter)
                ORDER BY employees.name";
                
                $res = $this->db->query($sql)->result();
                if(count($res) > 0) {
                    foreach($res as $r) {
                        $data['result'][] = $this->_fetch_esi_pf($mon,$r->e_id);
                        //print_r($data['result']);
                        }
                    }
            //print_r($data['mont']);
            $data['segment'] = 'payroll_pf';
            return array('page'=>'reports/payroll/payroll_pf','data'=>$data);
        }
        if($this->input->post("ot")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            
            $new_iter = implode(",", $it_arr);
            
            
                
                $sql="SELECT employees.e_id
                FROM employees
                WHERE employees.e_id IN ($new_iter)
                ORDER BY employees.name";
                
                $res = $this->db->query($sql)->result();
                if(count($res) > 0) {
                    foreach($res as $r) {
                        $data['result'][] = $this->_fetch_ot_details_all($mon,$r->e_id);
                        }
                }
            
            $data['segment'] = 'ot_details';
            return array('page'=>'reports/payroll/ot_details','data'=>$data);
        }
        if($this->input->post("sa_otim")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            $data['mont'] = $mon;
            
            $new_iter = implode(",", $it_arr);
            
            
                
                $sql="SELECT employees.e_id
        FROM employees
        WHERE employees.e_id IN ($new_iter)
        ORDER BY employees.name";
        
        $res = $this->db->query($sql)->result();
        if(count($res) > 0) {
            foreach($res as $r) {
        $data['result'][] = $this->_fetch_salary_overtime_details_all($mon,$r->e_id);
        }
            }
            
            $data['segment'] = 'salary_overtime_details';
            return array('page'=>'reports/payroll/salary_overtime_details','data'=>$data);
        }
        if($this->input->post("bonus_report")){
            $it_arr = $this->input->post('leather[]');
            $mon = $this->input->post('month');
            
            $departments_lists = '';
            
            $it_groups = $this->input->post('group');
            $data['mont'] = $mon;
            $new_arr = array();
            
            $gets_departments = $this->db->where_in('d_id', $it_groups)->get('departments')->result();
            
            foreach($gets_departments as $g_d) {
                $departments_lists .= $g_d->department.' ,';
            }
            if(count($it_arr) == 0){
                die('You must provide at least 1 input. Please close this window and try again.');
            }else{
                $new_iter = implode(",", $it_arr);
                $data['d_id'] = $it_groups;  
                
                
                
                    $sql="SELECT employees.e_id,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                    employees.hra_amount AS HRA1,employees.convenience AS CONV1,employees.medical_allowance AS MA1,employees.special_allowance AS OA1,
                    CAST((employees.basic_pay+employees.da_amout+employees.hra_amount+employees.convenience+employees.medical_allowance+employees.special_allowance) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
                    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
                    FROM salary
                    INNER JOIN(employees)
                    ON(salary.EMPCODE=employees.e_id)
                    WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN ($new_iter)
                    ORDER BY employees.name";
            
                $res = $this->db->query($sql)->result();

                if(count($res) > 0) {
                    foreach($res as $r) {
                        $data['result'][] = $this->_fetch_bonus_report($mon,$r->e_id);
                    }
                } else {
                    echo 'No Results'; die();
                }
            }
            
            
            $data['departments_lists'] = $departments_lists;
            
            $data['segment'] = 'bonus_sheet_report_register';
            return array('page'=>'reports/payroll/bonus_report','data'=>$data);
        }
        
        $data['fetch_all_employee'] = $this->db->get_where('employees', array('user_id !=' => 13))->result();    
        $data['departments'] = $this->db->get_where('departments', array('user_id !=' => 13))->result();
        
        return array('page' => 'reports/payroll/payroll_reports_filter', 'data'=>$data);
    }
    public function _fetch_salary_overtime_details_all($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
        $sql="SELECT employees.name,overtime.*,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                employees.hra_amount AS HRA1,employees.convenience AS CONV1,employees.medical_allowance AS MA1,employees.special_allowance AS OA1,
    CAST((employees.basic_pay+employees.da_amout+employees.hra_amount+employees.convenience+employees.medical_allowance+employees.special_allowance) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
        FROM overtime
        INNER JOIN(employees)
        ON(overtime.e_id=employees.e_id)
        INNER JOIN(salary)
        ON(employees.e_id=salary.EMPCODE)
        WHERE overtime.month LIKE '".$mon."%' AND employees.e_id IN('".$i_a."') AND salary.MON LIKE '".$mon."%'
        ORDER BY employees.name";
        
        } else {
            
        $sql="SELECT employees.name,overtime.*,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                employees.hra_amount AS HRA1,employees.convenience AS CONV1,employees.medical_allowance AS MA1,employees.special_allowance AS OA1,
    CAST((employees.basic_pay+employees.da_amout+employees.hra_amount+employees.convenience+employees.medical_allowance+employees.special_allowance) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET
        FROM overtime
        INNER JOIN(employees)
        ON(overtime.e_id=employees.e_id)
        INNER JOIN(salary)
        ON(employees.e_id=salary.EMPCODE)
        WHERE overtime.month LIKE '".$mon."%' AND employees.e_id IN('".$i_a."') AND salary.MON LIKE '".$mon."%' AND employees.user_id  != '13'
        ORDER BY employees.name";    
            
        }

        $res = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        return $res;
        
    }
    public function _fetch_outstanding_report($code, $df, $dt) {

        $query = "SELECT
                office_proforma.proforma_number,
                office_proforma.proforma_date,
                acc_master.am_id,
                acc_master.name,
                DATE_FORMAT(customer_order.co_date, '%d-%m-%Y') as co_date,
                customer_order.co_no,
                DATE_FORMAT(customer_order.co_delivery_date, '%d-%m-%Y') as co_delivery_date,
                customer_order.co_delivery_date,
                article_master.art_no,
                article_master.alt_art_no,
                colors.color,
                office_proforma_detail.co_quantity,
                office_invoice_detail.quantity,
                office_proforma_detail.cod_id,
                office_proforma_detail.rate_foreign,
                departments.department
                FROM
                `office_proforma`
                LEFT JOIN office_proforma_detail ON office_proforma_detail.office_proforma_id = office_proforma.office_proforma_id
                LEFT JOIN acc_master ON acc_master.am_id = office_proforma.buyer_id
                LEFT JOIN customer_order ON customer_order.co_id = office_proforma_detail.co_id
                LEFT JOIN user_details ON user_details.user_id = customer_order.user_id
                LEFT JOIN departments ON departments.d_id = user_details.user_dept
                LEFT JOIN article_master ON article_master.am_id = office_proforma_detail.am_id
                LEFT JOIN colors ON colors.c_id = office_proforma_detail.lc_id
                LEFT JOIN office_invoice_detail ON office_invoice_detail.cod_id = office_proforma_detail.cod_id
            WHERE 
            office_proforma.buyer_id = $code AND
            STR_TO_DATE(office_proforma.proforma_date, '%Y-%m-%d') <= '$dt' AND STR_TO_DATE(office_proforma.proforma_date, '%Y-%m-%d') >= '$df'
            AND
                office_proforma.status = 1
                GROUP BY
                    office_proforma_detail.cod_id
            ORDER BY
                office_proforma.proforma_number, customer_order.co_no, article_master.art_no, colors.color";
        $res = $this->db->query($query)->result();

        return $res;

    }
    
    public function _fetch_outstanding_report_group_wise($code, $dept_arr, $df, $dt) {

        $query = "SELECT
                GROUP_CONCAT(
                    CONCAT(
                      office_proforma.proforma_number
                    ) SEPARATOR '<br>'
                  ) AS proforma_number,
                office_proforma.office_proforma_id,
                office_proforma.proforma_date,
                acc_master.am_id,
                acc_master.name,
                DATE_FORMAT(customer_order.co_date, '%d-%m-%Y') as co_date,
                customer_order.co_no,
                DATE_FORMAT(customer_order.co_delivery_date, '%d-%m-%Y') as co_delivery_date,
                article_master.art_no,
                article_master.alt_art_no,
                colors.color,
                0 AS quantity,
                0 AS invoice_amount,
                office_proforma.buyer_id,
                user_details.user_dept,
                user_details.user_id,
                departments.department,
                office_proforma_detail.co_id,
                office_proforma.total_value,
                office_proforma_detail.rate_foreign AS rate_foreign, 
                                  SUM(office_proforma_detail.co_quantity) AS co_quantity,
                SUM(office_proforma_detail.total_rate) AS proforma_total_rate_amount
                FROM
                `customer_order`
                LEFT JOIN office_proforma_detail ON office_proforma_detail.co_id = customer_order.co_id
                LEFT JOIN office_proforma ON office_proforma.office_proforma_id = office_proforma_detail.office_proforma_id
                LEFT JOIN acc_master ON acc_master.am_id = office_proforma.buyer_id
                LEFT JOIN user_details ON customer_order.user_id = user_details.user_id
                LEFT JOIN departments ON user_details.user_dept = departments.d_id
                LEFT JOIN article_master ON article_master.am_id = office_proforma_detail.am_id
                LEFT JOIN colors ON colors.c_id = office_proforma_detail.lc_id
            WHERE 
            office_proforma.buyer_id = $code AND
            customer_order.show_in_outstanding_report_or_not = '1'
            AND
            STR_TO_DATE(office_proforma.proforma_date, '%Y-%m-%d') <= '$dt' AND STR_TO_DATE(office_proforma.proforma_date, '%Y-%m-%d') >= '$df'
            AND
            user_details.user_dept IN ($dept_arr)
            AND
                office_proforma.status = 1
            GROUP BY
            office_proforma_detail.co_id
            ORDER BY
                user_details.user_dept, customer_order.co_no";
        $res = $this->db->query($query)->result();
        

        return $res;

    }
    

    public function _fetch_advance_ledger($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
        $sql="SELECT advance.emp_id,advance.amount AS ADV, MONTHNAME(advance.date) AS MONNAME, advance.date, employees.name, employees.e_code, 1 AS TAG
        FROM advance
        INNER JOIN(employees)
        ON(advance.emp_id=employees.e_id)
        WHERE advance.emp_id = '".$i_a."'
        ORDER BY employees.name, advance.date";
            
        } else {
            $sql="SELECT advance.emp_id,advance.amount AS ADV, MONTHNAME(advance.date) AS MONNAME, advance.date, employees.name, employees.e_code, 1 AS TAG
        FROM advance
        INNER JOIN(employees)
        ON(advance.emp_id=employees.e_id)
        WHERE advance.emp_id = '".$i_a."' AND advance.user_id  != '13'
        ORDER BY employees.name, advance.date";
        }

        $res = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        return $res;

    }

    public function _fetch_leave($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) { // account dept restrictions
        
        $sql="SELECT employees.*
            FROM employees
            WHERE employees.e_id='".$i_a."'
            ORDER BY employees.name";
            
        } else {
            
        $sql="SELECT employees.* 
            FROM employees
            WHERE employees.e_id='".$i_a."' AND employees.user_id  != '13'
            ORDER BY employees.name";    
            
        }

        $res = $this->db->query($sql)->result();
        return $res;
        

    }

    public function _fetch_attendance($i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        $sql="SELECT employees.e_id, employees.e_code, employees.name, salary.MON, T1, T2, T3, T4, T5, T6, T7
            FROM employees
            LEFT JOIN salary ON salary.EMPCODE = employees.e_id
            WHERE employees.e_id='".$i_a."' AND employees.user_id  != '13'
            ORDER BY employees.name";
            
        $res = $this->db->query($sql)->result();
        
        return $res;

    }    
    public function _fetch_esi_pf($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
        $sql="SELECT employees.name,e_code,employees.pf_acc_no,employees.esi_acc_no, salary.T1, salary.T7 ,salary.T2,CAST((salary.BASIC+salary.DA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL2,CAST((salary.BASIC+salary.OA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL3,salary.GROSS,employees.pf_percentage_calculation
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id='".$i_a."' AND employees.pf = '1'
            ORDER BY employees.e_code";
            
        } else {
            
        $sql="SELECT employees.name,e_code,employees.pf_acc_no,employees.esi_acc_no,salary.T1, salary.T7 ,salary.T2,CAST((salary.BASIC+salary.DA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL2,CAST((salary.BASIC+salary.OA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL3,salary.GROSS,employees.pf_percentage_calculation
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id='".$i_a."' AND employees.pf = '1' AND employees.user_id  != '13'
            ORDER BY employees.e_code";   
            
        }

        $res = $this->db->query($sql)->result();
        return $res;
        

    }
    
    public function _fetch_esi_pf1($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
        $sql="SELECT employees.name,e_code,employees.pf_acc_no,employees.esi_acc_no, salary.T1, salary.T7 ,salary.T2,CAST((salary.BASIC+salary.DA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL2,CAST((salary.BASIC+salary.OA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL3,salary.GROSS,employees.pf_percentage_calculation
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id='".$i_a."' AND employees.esi = '1'
            ORDER BY employees.e_code";
            
        } else {
            
        $sql="SELECT employees.name,e_code,employees.pf_acc_no,employees.esi_acc_no,salary.T1, salary.T7 ,salary.T2,CAST((salary.BASIC+salary.DA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL2,CAST((salary.BASIC+salary.OA+salary.CONV) AS DECIMAL(11,2)) AS TOTAL3,salary.GROSS,employees.pf_percentage_calculation
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id='".$i_a."' AND employees.esi = '1' AND employees.user_id  != '13'
            ORDER BY employees.e_code";   
            
        }

        $res = $this->db->query($sql)->result();
        return $res;
        

    }

    public function _fetch_register($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
            $sql="SELECT employees.name,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,
            salary.MASTER_BASIC AS BASIC1,salary.MASTER_DA AS DA1,salary.MASTER_HRA AS HRA1,salary.MASTER_CONV AS CONV1,salary.MASTER_MED AS MA1,salary.MASTER_OA AS OA1,CAST((salary.MASTER_BASIC+salary.MASTER_DA+salary.MASTER_HRA+salary.MASTER_CONV+salary.MASTER_MED+salary.MASTER_OA) AS DECIMAL(11,2)) AS TOTAL1,
            salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
            salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET,salary.MON,employees.e_id,employees.d_id, employees.basic_pay
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN('".$i_a."')
            ORDER BY employees.name";
        
        } else {
            
            $sql="SELECT employees.name,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,
            salary.MASTER_BASIC AS BASIC1,salary.MASTER_DA AS DA1,salary.MASTER_HRA AS HRA1,salary.MASTER_CONV AS CONV1,salary.MASTER_MED AS MA1,salary.MASTER_OA AS OA1,CAST((salary.MASTER_BASIC+salary.MASTER_DA+salary.MASTER_HRA+salary.MASTER_CONV+salary.MASTER_MED+salary.MASTER_OA) AS DECIMAL(11,2)) AS TOTAL1,
            salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
            salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS,salary.LOAN,salary.DEDUC,salary.NET,salary.MON,employees.e_id,employees.d_id, employees.basic_pay
            FROM salary
            INNER JOIN(employees)
            ON(salary.EMPCODE=employees.e_id)
            WHERE salary.MON LIKE '".$mon."%' AND employees.e_id IN('".$i_a."') AND employees.user_id  != '13'
            ORDER BY employees.name";    
            
        }

        $res = $this->db->query($sql)->result();
        // echo '<pre>', print_r($res), '</pre>'; die();
        // echo $this->db->last_query();die;
        return $res;
        

    }
    public function _fetch_ot_details_all($mon,$i_a) {
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        if($user_id == 13) {
        
        $sql="SELECT employees.name,overtime.*
        FROM overtime
        INNER JOIN(employees)
        ON(overtime.e_id=employees.e_id)
        WHERE overtime.month LIKE '".$mon."%' AND employees.e_id IN('".$i_a."')
        ORDER BY employees.name";
        
        } else {
            
        $sql="SELECT employees.name,overtime.*
        FROM overtime
        INNER JOIN(employees)
        ON(overtime.e_id=employees.e_id)
        WHERE overtime.month LIKE '".$mon."%' AND employees.e_id IN('".$i_a."') AND employees.user_id  != '13'
        ORDER BY employees.name";    
            
        }

        $res = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        return $res;
        
    }
    public function _fetch_bonus_report($mon,$i_a) {
        
        $array_bonus_sheet_report = [];
        
        if(empty($i_a)) {
            die('No Details To Show');
        }
        
        $user_id = $this->session->user_id;
        
        for ($mon = 1; $mon <= 12; $mon++) {
            
        $sql="SELECT employees.name,salary.T1,salary.T2,salary.T3,(salary.T4+salary.T5+salary.T6) AS T,salary.T7,employees.basic_pay AS BASIC1,employees.da_amout AS DA1,
                employees.hra_amount AS HRA1,employees.convenience AS CONV1,employees.medical_allowance AS MA1,employees.special_allowance AS OA1,
    CAST((employees.basic_pay+employees.da_amout+employees.hra_amount+employees.convenience+employees.medical_allowance+employees.special_allowance) AS DECIMAL(11,2)) AS TOTAL1,salary.BASIC AS BASIC2,salary.DA AS DA2,CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS TOTAL2,
    salary.HRA,salary.CONV,salary.MED,salary.OA,salary.GROSS,salary.PFAMT,salary.ESIAMT,salary.TAX,salary.INS, employees.e_id, salary.MON, employees.d_id, salary.LOAN,salary.DEDUC,salary.NET, CAST((salary.BASIC+salary.DA) AS DECIMAL(11,2)) AS actual_basic_in_bonus
        FROM salary
        INNER JOIN(employees)
        ON(salary.EMPCODE=employees.e_id)
        WHERE salary.MON LIKE '".date('F', mktime(0, 0, 0, $mon, 1))."%' AND employees.e_id IN('".$i_a."')
        ORDER BY employees.name";    

        $res = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        
        foreach($res as $r) {
            $array_bonus_sheet_report[$r->e_id]['name'] = $r->name;
            $array_bonus_sheet_report[$r->e_id][$r->MON] = $r->actual_basic_in_bonus;
            $array_bonus_sheet_report[$r->e_id]['TOTAL1'] = $r->TOTAL1;
            $array_bonus_sheet_report[$r->e_id]['d_id'] = $r->d_id;
        }
        }
        
        return $array_bonus_sheet_report;
        

    }

}