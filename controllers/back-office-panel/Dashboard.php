<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->isLoggedIn();
    }

    public function index() {
        $this->load->view('back-office-panel/home');
    }

    public function logout() {
        $this->session->unset_userdata('zpisLoggedIn');
        $this->session->unset_userdata('zpda');
        redirect('back-office-panel/login');
    }

    public function pagenotfound() {
        $this->load->view('back-office-panel/error');
    }

}
