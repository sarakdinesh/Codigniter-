<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MasterModel extends CI_Model {

    // var $column_order = array('name', null); //set column field database for datatable orderable
    // var $column_search = array('id', 'name', 'category_name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    // var $order = array('id' => 'desc'); // default order

    public function __construct() {
        parent::__construct();
    }

    private function _get_datatables_query() {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    function get_all() {
        $query = $this->db->get($this->table);
        $this->db->order_by("id", "desc");
        return $query->result_array();
    }

    function get_all_status() {
        $this->db->where('status', '1');
        $this->db->order_by("id", "desc");
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function check_is_exit($field_name) {

        $this->db->from($this->table);
        $this->db->where($this->field_name, $field_name);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_by() {
        $this->db->from($this->table);
        $this->db->where('dealer_id', $this->dealeridssss);
        return $this->db->count_all_results();
    }

    function count_filtered_by() {
        $this->_get_datatables_query_by();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_by_column($column, $data) {
        $this->db->from($this->table);
        $this->db->where($column, $data);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_max_id() {
        $this->db->select_max("id");
        $this->db->from($this->table);
        $query = $this->db->get()->row();
        return $query->id;
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($column, $id) {
        $this->db->where($column, $id);
        $this->db->delete($this->table);
    }

    function get_data_for_pagination($param = null) {
        $where = @$param['where'];
        $this->db->where('status', '1');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($this->input->get("page"))) {
            $this->db->order_by("id", "desc");
            $start = ceil($this->input->get("page") * $this->perPage);
            $queryss = $this->db->limit($this->perPage, $start)->get($this->table);
            return $queryss->result();
        } else {
            $this->db->order_by("id", "desc");
            $query = $this->db->limit($this->perPage, 0)->get($this->table);
            return $query->result();
        }
    }

    //  (1) user role based menuitem id
    public function userRoleMapping() {

        $this->db->select('rm.menu_item_id');
        $this->db->from('tbl_role_menu_item_map rm');
        $searchCondition = "(
                 rm.role_id   = " . $this->session->userdata("zprole") . "
            )";
        $this->db->where($searchCondition);
        $this->db->where('rm.status', 1);

        return $this->db->get()->result_array();
    }

    // (2) use main menu master menu id
    public function menuItemMapping($menuItemId) {

        $this->db->select('rm.menu_master_id,mm.title,mm.icon,mm.menu_active_name');
        $this->db->from('tbl_menu_items rm');
        $this->db->join('tbl_menu_master mm', 'mm.id = rm.menu_master_id', 'left');
        $searchCondition = "(
                 rm.id  = " . $menuItemId . "
            )";
        $this->db->where('rm.status', 1);
        $this->db->where('mm.status', 1);
        $this->db->where($searchCondition);

        return $this->db->get()->result_array();
    }

    // (3) once get menu id then check for next menuitemid which reference of menu id
    public function menuMasterMenuItemMapping($menuID) {
        $this->db->select('*');
        $this->db->from('tbl_menu_items rm');
        $this->db->join('tbl_role_menu_item_map rm1', 'rm1.menu_item_id = rm.id', 'left');

        $searchCondition = "(
                 rm.menu_master_id  = " . $menuID . " and
                 rm1.role_id  = " . $this->session->userdata("zprole") . "

            )";
        $this->db->where('rm.status', 1);
        $this->db->where($searchCondition);
        return $this->db->get()->result_array();
    }

}
