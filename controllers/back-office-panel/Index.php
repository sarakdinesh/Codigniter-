<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('back-office-panel/Loginmodel');
        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->load->library("session");
    }

    public function index() {
        $this->isLoggedIn();
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn() {
        $isLoggedIn = $this->session->userdata('zpisLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $this->load->view('back-office-panel/login');
        } else {
            redirect('/back-office-panel/dashboard');
        }
    }

    /**
     * This function used to logged in user
     */
    public function login() {

        $arrLoginRules = array
            (
            array(
                "field" => "username",
                "label" => "User name",
                "rules" => "trim|required"
            ),
            array(
                "field" => "password",
                "label" => "Password",
                "rules" => "trim|required"
            )
        );


        $this->form_validation->set_rules($arrLoginRules);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $this->load->view('back-office-panel/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $result = $this->Loginmodel->userslogins($username, $password);

            if (count($result) > 0) {
                foreach ($result as $res) {
                    $sessionArray = array('zpuserId' => $res->id,
                        'zprole' => $res->role_id,
                        'zproleText' => $res->role,
                        'zpname' => $res->name,
                        'zpisLoggedIn' => TRUE,
                        'zpda' => $datasssssssss
                    );
                    $this->session->set_userdata($sessionArray);
                    redirect('/back-office-panel/dashboard');
                }
            } else {
                $this->session->set_flashdata('error', 'username or password mismatch');
                $this->load->view('back-office-panel/login');
            }
        }
    }

}
