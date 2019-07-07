<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class : Error (ErrorController)
 * Error class to redirect on errors
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Error extends CI_Controller {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
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
            redirect('pagenotfound');
        }
    }

}

?>