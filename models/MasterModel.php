<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MasterModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getRows($params = array()) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by("id", "desc");
        if (array_key_exists("id", $params)) {
            $this->db->where('id', $params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        } else {
            //set start and limit
            if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit'], $params['start']);
            } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit']);
            }

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
                $result = $this->db->count_all_results();
            } else {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
            }
        }
        //return fetched data
        return $result;
    }

    public function get_current_page_records($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    function get_all() {
        $this->db->where("status", "1");
        $this->db->order_by("id", "desc");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function getRows_for_pagination($params = array()) {
        $this->db->select('*');
        $this->db->from($this->table);

        //fetch data by conditions
        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (array_key_exists("order_by", $params)) {
            $this->db->order_by($params['order_by']);
        }

        if (array_key_exists("id", $params)) {
            $this->db->where('id', $params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        } else {
            //set start and limit
            if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit'], $params['start']);
            } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit']);
            }

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
                $result = $this->db->count_all_results();
            } else {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
            }
        }

        //return fetched data
        return $result;
    }

    function get_data_for_pagination_movies($param = null, $like = null) {
        $where = @$param['where'];
        $this->db->where('status', '1');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($like)) {
            $this->db->like('title', $like);
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
            $this->db->order_by("no_of_views", "desc");
            $query = $this->db->limit($this->perPage, 0)->get($this->table);
            return $query->result();
        }
    }

    function get_top_five() {
        $this->db->where("status", "1");
        $this->db->limit(10);
        $this->db->order_by("id", "desc");

        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_all_by() {
        $this->db->from($this->table);
        $this->db->where('dealer_id', $this->dealeridssss);
        return $this->db->count_all_results();
    }

    public function get_menu($column, $data) {
        $this->db->from("tbl_menu");
        $this->db->where($column, $data);
        $query = $this->db->get();
        return $query->result();
    }

    function get_by_limit($limit, $param) {

        $where = @$param['where'];
        $this->db->where('status', '1');
        $this->db->where($where);
        $this->db->limit($limit);
        $this->db->order_by("id", "desc");
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function get_distinct($column) {
        $this->db->distinct();
        $this->db->select("*", $column);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function get_all_with_limit($limit, $menuId) {
        $this->db->where('status', '1');
        $this->db->where('menu_id', $menuId);
        $this->db->limit($limit);
        $this->db->order_by("id", "desc");
        $query = $this->db->get("tbl_entity");
        return $query->result();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function check_user($mobile) {

        $query = $this->db->where("mobile", $mobile)
                ->get("tbl_customer");

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            $data = array('mobile' => $mobile
            );
            $str = $this->db->insert('tbl_customer', $data);
            $unique_id = $this->db->insert_id();

            $query1 = $this->db->where("id", $unique_id)
                    ->get("tbl_customer");
            return $query1->row();
        }
    }

    public function get_by_column($column, $data) {
        $this->db->from($this->table);
        $this->db->where($column, $data);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_column_row($column, $data) {
        $this->db->from($this->table);
        $this->db->where($column, $data);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_All_comments($column, $data) {
        $this->db->from("tbl_comments");
        $this->db->where($column, $data);
        $query = $this->db->get();
        return $query->result();
    }

}
