
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->table = "tbl_story";
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->load->library("session");
        $this->load->helper("network");
        $this->isLoggedIn();
    }

    public function index() {
        $this->load->view('back-office-panel/story');
    }

    public function add($id) {
        if (isset($id)) {
            $data["datas"] = $this->master->get_by_column("unique_id", $id);
            $this->load->view('back-office-panel/add_story', $data);
        } else {
            $this->load->view('back-office-panel/add_story');
        }
    }

    public function lists() {

        $this->column_search = array('id', 'title'); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $this->column_order = array('id', 'title', null); //set column field database for datatable orderable
        $this->order = array('id'); // default order

        $list = $this->master->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $master) {
            $no++;
            $row = array();
            $image = '<iframe width="120" height="100" src="https://www.youtube.com/embed/' . $master->url . '" frameborder="0" allowfullscreen></iframe>';

            $row[] = $image;
            $row[] = $master->title;
            $row[] = $master->page_name;
            $row[] = $master->created_date;

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


            $url = base_url() . "story/" . replaceAll($master->page_name) . "/" . $master->unique_id;

            $copy = '<a class="btn-floating waves-effect waves-light red lightrn-1" href="javascript:void(0)"
               title="Deactive" onclick="copy(' . "'" . $url . "'" . ')">'
                    . '<i class="large material-icons">all_out</i></a>';
//add html for action
            $row[] = '<a class="btn-floating waves-effect waves-light purple lightrn-1" href="' . base_url('back-office/story-add/') . $master->unique_id . '" '
                    . 'title="Edit">
                        <i class="large material-icons">mode_edit</i></a>
	       <a class="btn-floating waves-effect waves-light orange lightrn-1" href="javascript:void(0)"
               title="Delete" onclick="delete_person(' . "'" . $master->unique_id . "'" . ')">'
                    . '<i class="large material-icons">delete</i></a>
                     ' . $status . $copy;


            $data[] = $row;
        }
        $this->table = "tbl_story";
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
        $url = YoutubeID($this->input->post('url'));

        $unique_id = UniqueID();
        $this->table = "tbl_story";
        if ($id != "") {
            $data = array(
                'title' => $this->input->post('title'),
                'page_name' => $this->input->post('page_name'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keyword' => $this->input->post('meta_keyword'),
                'url' => $url,
                'modified_by' => $this->global['user_id'],
                'modified_date' => DATESTIME(),
            );
            $resultaaaaa = $this->master->update(array('unique_id' => $id), $data);
            $this->session->set_flashdata('success', 'Data Updated successfully.');

            redirect('back-office/story');
        } else {

            $this->form_validation->set_rules('title', 'title', 'required');
            if ($this->form_validation->run() == FALSE) {

                $this->session->set_flashdata('error', validation_errors());
                $this->load->view('back-office-panel/add_story');
            } else {
                $data = array(
                    'title' => $this->input->post('title'),
                    'page_name' => $this->input->post('page_name'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'url' => $url,
                    'unique_id' => $unique_id,
                    'modified_by' => $this->global['user_id'],
                    'modified_date' => DATESTIME(),
                );

                $result = $this->master->save($data);
                $this->session->set_flashdata('success', 'Data Saved Successfully.');
                redirect('back-office/story');
            }
        }
    }

    private function set_upload_options() {
//upload an image options
        $config = array();
        $config['upload_path'] = 'uploads';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 5000;
        $config['overwrite'] = FALSE;
        return $config;
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
        $this->master->update(array('unique_id' => $id), $data);
        echo json_encode(array("status" => TRUE, "flag" => $staus));
    }

}
