<?php

defined('BASEPATH') or die('No direct script access.');

class Otp {

    public function __construct() {

    }

    function verify_otp($data) {

        $this->CI = & get_instance();
        extract($data);

        if ($mobile != "") {
            $this->CI->db->where("$field_name", "$mobile");
            $this->CI->db->where('otp', "$otp");
        }
        $this->CI->db->from("$table_name");
        $query1 = $this->CI->db->get();
        //echo $this->CI->db->get_compiled_select();
        if ($query1) {
            return $query1->row();
        } else {
            return false;
        }
    }

}

// END OTP Class