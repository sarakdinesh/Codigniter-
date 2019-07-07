<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// for back-office-panel

class Loginmodel extends CI_Model {

    public function index() {
        $query = $this->db->get("tbl_users");
        $this->db->order_by("id", "desc");
        if ($query) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    public function userslogins($username, $password) {

        $this->db->select('BaseTbl.id, BaseTbl.password, BaseTbl.name, BaseTbl.role_id, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles', 'Roles.roleId = BaseTbl.role_id');
        $this->db->where('BaseTbl.user_name', $username);
        $this->db->where('BaseTbl.status', 1);
        $query = $this->db->get();

        $user = $query->result();

        if (!empty($user)) {
            if (verifyHashedPassword($password, $user[0]->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }

    }

}
