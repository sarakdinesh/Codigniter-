
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Homecategory extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->table = "tbl_home_special_category";
        $this->load->library('upload');
        $this->load->library("session");
        $this->isLoggedIn();
    }

    public function index() {
        $this->load->view('back-office-panel/homecategory');
    }

    public function add($id) {
        if (isset($id)) {
            $data["datas"] = $this->master->get_by_column("unique_id", $id);
            $this->load->view('back-office-panel/add_homecategory', $data);
        } else {
            $this->load->view('back-office-panel/add_homecategory');
        }
    }

    public function lists() {

        $this->column_search = array('id', 'title', 'main_category', 'created_date'); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $this->column_order = array('id', 'title', 'main_category', 'created_date', null); //set column field database for datatable orderable
        $this->order = array('id'); // default order

        $list = $this->master->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $master) {
            $no++;
            $row = array();
            $row[] = $master->main_category;
            $row[] = $master->title;

            $status = "";
            if ($master->status == 1) {
                $status = '<a class="btn-floating waves-effect waves-light green lightrn-1" href="javascript:void(0)"
               title="Active" onclick="active(' . "'" . $master->unique_id . "'" . ')">'
                        . '<i class="large material-icons">power_settings_new</i></a>';
            } else {

                $status = '<a class="btn-floating waves-effect waves-light red lightrn-1" href="javascript:void(0)"
               title="Deactive" onclick="active(' . "'" . $master->unique_id . "'" . ')">'
                        . '<i class="large material-icons">power_settings_new</i></a>';
            }

//add html for action
            $row[] = '<a class="btn-floating waves-effect waves-light purple lightrn-1" href="' . base_url('back-office/homecategory-add/') . $master->unique_id . '" '
                    . 'title="Edit">
                        <i class="large material-icons">mode_edit</i></a>
	       <a class="btn-floating waves-effect waves-light orange lightrn-1" href="javascript:void(0)"
               title="Delete" onclick="delete_person(' . "'" . $master->unique_id . "'" . ')">'
                    . '<i class="large material-icons">delete</i></a>
                     ' . $status;
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

    public function save() {

        $id = $this->input->post('id');
        $unique_id = UniqueID();
        if ($id != "") {

            $data = array(
                'title' => $this->input->post('title'),
                'main_category' => $this->input->post('main_category')
            );
            $result = $this->master->update(array('id' => $this->input->post('id')), $data);
            $this->session->set_flashdata('success', 'Data Updated successfully.');
            redirect('back-office-panel/homecategory');
        } else {

            $this->form_validation->set_rules('title', 'title', 'trim|required');
            if ($this->form_validation->run() == FALSE) {

                $this->session->set_flashdata('error', validation_errors());
                $this->load->view('back-office-panel/add_homecategory');
            } else {
                $data = array(
                    'title' => $this->input->post('title'),
                    'main_category' => $this->input->post('main_category'),
                    'unique_id' => $unique_id
                );
                $result = $this->master->save($data);

                $this->session->set_flashdata('success', 'Data Saved Successfully.');
                redirect('back-office-panel/homecategory');
            }
        }
    }

    public function delete($id) {
        $this->master->delete("unique_id", $id);

        echo json_encode(array("status" => TRUE));
    }

    public function active($id) {
        $datas = $this->master->get_by_column("unique_id", $id);
        $staus = $datas->status == 1 ? 0 : 1;
        $data = array(
            'status' => $staus
        );
        $this->master->update(array('unique_id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE, "flag" => $staus));
    }

}
