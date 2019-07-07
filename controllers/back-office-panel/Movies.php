
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movies extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->table = "tbl_entertainment";
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->load->library("session");
        $this->load->helper("network");
        $this->isLoggedIn();
    }

    public function index() {
        $this->load->view('back-office-panel/movies');
    }

    public function add($id) {
        if (isset($id)) {
            $data["datas"] = $this->master->get_by_column("unique_id", $id);
            $this->load->view('back-office-panel/add_movies', $data);
        } else {
            $this->load->view('back-office-panel/add_movies');
        }
    }

    public function lists() {

        $this->column_search = array('id', 'name', 'email', 'address', 'mobile'); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $this->column_order = array('id', 'name', null); //set column field database for datatable orderable
        $this->order = array('id'); // default order

        $list = $this->master->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $master) {
            $no++;
            $row = array();

            $this->table = "tbl_entertainment_category";
            $menus = $this->master->get_by_column("id", $master->menu_id);
            $image = "<img src='../uploads/tumbnail/" . $master->image_path . "' style='width:50px;height:50px;'/>";

            $row[] = $image;
            $row[] = $menus->title;
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

            //https://ctcrowd.com/movies/game-of-thrones-s03e05-dual-audio-720p-bluray-400mb-ctcrowd/E9TVIpnJF8mgLNDh6MAs#


            $url = base_url() . "movies/" . replaceAll($master->page_name) . "/" . $master->unique_id;

            $copy = '<a class="btn-floating waves-effect waves-light red lightrn-1" href="javascript:void(0)"
               title="Deactive" onclick="copy(' . "'" . $url . "'" . ')">'
                    . '<i class="large material-icons">all_out</i></a>';
//add html for action
            $row[] = '<a class="btn-floating waves-effect waves-light purple lightrn-1" href="' . base_url('back-office/movies-add/') . $master->unique_id . '" '
                    . 'title="Edit">
                        <i class="large material-icons">mode_edit</i></a>
	       <a class="btn-floating waves-effect waves-light orange lightrn-1" href="javascript:void(0)"
               title="Delete" onclick="delete_person(' . "'" . $master->unique_id . "'" . ')">'
                    . '<i class="large material-icons">delete</i></a>
                     ' . $status . $copy;


            $data[] = $row;
        }
        $this->table = "tbl_entertainment";
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
        $this->table = "tbl_entertainment";
        if ($id != "") {
            if (empty($_FILES['image']['name'])) {

                $data = array(
                    'title' => $this->input->post('title'),
                    'page_name' => $this->input->post('page_name'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'menu_id' => $this->input->post('menu_id'),
                    'description' => $this->input->post('description'),
                    'modified_by' => $this->global['user_id'],
                    'modified_date' => DATESTIME(),
                );
                $resultaaaaa = $this->master->update(array('unique_id' => $id), $data);
                $this->session->set_flashdata('success', 'Data Updated successfully.');

                redirect('back-office/movies');
            } else {
                $this->upload->initialize(set_upload_options());


                if (!$this->upload->do_upload("image")) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error', $error["error"]);
                    $this->load->view('back-office-panel/add_movies');
                } else {
                    $datass = $this->upload->data();
                    $imgname = $datass["file_name"];
                    $this->image_moo->load($_FILES['image']['tmp_name'])->resize(350, 270)->save("uploads/tumbnail/" . $imgname . "", TRUE);
                    $data = array(
                        'title' => $this->input->post('title'),
                        'page_name' => $this->input->post('page_name'),
                        'meta_description' => $this->input->post('meta_description'),
                        'meta_keyword' => $this->input->post('meta_keyword'),
                        'menu_id' => $this->input->post('menu_id'),
                        'description' => $this->input->post('description'),
                        'image_path' => $imgname,
                        'modified_by' => $this->global['user_id'],
                        'modified_date' => DATESTIME(),
                    );
                    gallary_image($imgname);
                    $result = $this->master->update(array('unique_id' => $this->input->post('id')), $data);
                    $this->session->set_flashdata('success', 'Data Updated successfully.');
                    redirect('back-office/movies');
                }
            }
        } else {

            $this->form_validation->set_rules('title', 'title', 'required');
            if ($this->form_validation->run() == FALSE) {

                $this->session->set_flashdata('error', validation_errors());
                $this->load->view('back-office-panel/add_movies');
            } else {

                if (empty($_FILES['image']['name'])) {

                    $data = array(
                        'title' => $this->input->post('title'),
                        'page_name' => $this->input->post('page_name'),
                        'meta_description' => $this->input->post('meta_description'),
                        'meta_keyword' => $this->input->post('meta_keyword'),
                        'menu_id' => $this->input->post('menu_id'),
                        'description' => $this->input->post('description'),
                        'unique_id' => $unique_id,
                        'created_by' => $this->global['user_id']
                    );

                    $result = $this->master->save($data);
                    $this->session->set_flashdata('success', 'Data Saved Successfully.');
                    redirect('back-office/movies');
                } else {


                    $this->upload->initialize(set_upload_options());

                    if (!$this->upload->do_upload("image")) {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error', $error["error"]);
                        $this->load->view('back-office-panel/add_movies');
                    } else {
                        $datass = $this->upload->data();
                        $imgname = $datass["file_name"];

                        //// this is very imp for image manipulation
                        $this->image_moo->load($_FILES['image']['tmp_name'])->resize(350, 270)->save("uploads/tumbnail/" . $imgname . "", TRUE);
                        //////

                        $data = array(
                            'title' => $this->input->post('title'),
                            'page_name' => $this->input->post('page_name'),
                            'meta_description' => $this->input->post('meta_description'),
                            'meta_keyword' => $this->input->post('meta_keyword'),
                            'menu_id' => $this->input->post('menu_id'),
                            'description' => $this->input->post('description'),
                            'image_path' => $imgname,
                            'unique_id' => $unique_id,
                            'created_by' => $this->global['user_id']
                        );
                        gallary_image($imgname);
                        $result = $this->master->save($data);
                        $this->session->set_flashdata('success', 'Data Saved Successfully.');
                        redirect('back-office/movies');
                    }
                }
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
