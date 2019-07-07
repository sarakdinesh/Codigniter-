<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_all() {
        $this->db->from("tbl_notification");
        $this->db->where('is_read', '0');
        return $this->db->count_all_results();
    }

    public function get() {
        $this->db->from("tbl_notification");
        $this->db->order_by("id", "desc");
        $this->db->where('is_read', '0');
        $query = $this->db->get();
        return $query->result();
    }

}
