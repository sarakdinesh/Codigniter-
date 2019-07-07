
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->table = "tbl_tags";
        $this->column_search = array('id', 'name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $this->column_order = array('id', 'name', null); //set column field database for datatable orderable
        $this->order = array('id'); // default order
        $this->load->library("session");
        $this->isLoggedIn();
    }

    public function index() {
        $this->load->view('back-office-panel/tags');
    }

    public function ajax_list() {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->master->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $master) {
            $no++;
            $row = array();

            $row[] = $master->title;


            $status = "";
            if ($master->status == 1) {
                $status = '<a class="btn-floating waves-effect waves-light green lightrn-1" href="javascript:void(0)"
               title="Active" onclick="active(' . "'" . $master->id . "'" . ')">'
                        . '<i class="large material-icons">power_settings_new</i></a>';
            } else {

                $status = '<a class="btn-floating waves-effect waves-light red lightrn-1" href="javascript:void(0)"
               title="Deactive" onclick="active(' . "'" . $master->id . "'" . ')">'
                        . '<i class="large material-icons">power_settings_new</i></a>';
            }
            //add html for action
            $row[] = '<a class="btn-floating waves-effect waves-light purple lightrn-1" href="javascript:void(0)" '
                    . 'title="Edit" onclick="edit_person(' . "'" . $master->id . "'" . ')">
                        <i class="large material-icons">mode_edit</i>
	       <a class="btn-floating waves-effect waves-light orange lightrn-1" href="javascript:void(0)"
               title="Delete" onclick="delete_person(' . "'" . $master->id . "'" . ')">'
                    . '<i class="large material-icons">delete</i></a>';
            // ' . $status;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->master->count_all(),
            "recordsFiltered" => $this->master->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $data = $this->master->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $this->form_validation->set_rules('title', 'title', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, 'message' => validation_errors()));
        } else {
            $data = array(
                'title' => strtolower($this->input->post('title'))
            );
            $insert = $this->master->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $this->form_validation->set_rules('title', 'title', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, 'message' => validation_errors()));
        } else {

            $data = array(
                'title' => strtolower($this->input->post('title'))
            );

            $this->master->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_delete($id) {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $this->master->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_active($id) {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $datas = $this->master->get_by_id($id);
        $staus = $datas->status == 1 ? 0 : 1;
        $data = array(
            'status' => $staus
        );
        $this->master->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE, "flag" => $staus));
    }

}
