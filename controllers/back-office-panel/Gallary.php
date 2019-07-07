
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gallary extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('back-office-panel/MasterModel', 'master');
        $this->table = "tbl_media";
        $this->load->library('upload');
        $this->load->library('image_moo');
        $this->load->library("session");
        $this->isLoggedIn();
        $this->perPage = 10;
    }

    public function index() {
        if (!empty($this->input->get("page"))) {
            $list["data"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('back-office-panel/data_media', $list);
            echo json_encode($resultssss);
        } else {
            $list["data"] = $this->master->get_data_for_pagination($where);

            $this->load->view('back-office-panel/gallary', $list);
        }
    }

    public function add($id) {
        if (isset($id)) {
            $data["datas"] = $this->master->get_by_column("unique_id", $id);
            $this->load->view('back-office-panel/add_gallary', $data);
        } else {
            $this->load->view('back-office-panel/add_gallary');
        }
    }

    public function save() {

        $id = $this->input->post('id');

        $unique_id = UniqueID();
        $this->table = "tbl_media";
        if ($id != "") {

            $this->upload->initialize(set_upload_options());
            if (!$this->upload->do_upload("image")) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error["error"]);
                $this->load->view('back-office-panel/add_gallary');
            } else {
                $datass = $this->upload->data();
                $imgname = $datass["file_name"];
                $this->image_moo->load($_FILES['image']['tmp_name'])->resize(350, 270)->save("uploads/tumbnail/" . $imgname . "", TRUE);
                $data = array(
                    'image_path' => $imgname
                );
                $result = $this->master->update(array('unique_id' => $this->input->post('id')), $data);
                $this->session->set_flashdata('success', 'Data Updated successfully.');
                redirect('back-office/gallary');
            }
        } else {

            $this->upload->initialize(set_upload_options());

            if (!$this->upload->do_upload("image")) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error["error"]);
                $this->load->view('back-office-panel/add_gallary');
            } else {
                $datass = $this->upload->data();
                $imgname = $datass["file_name"];

                //// this is very imp for image manipulation
                $this->image_moo->load($_FILES['image']['tmp_name'])->resize(350, 270)->save("uploads/tumbnail/" . $imgname . "", TRUE);
                //////

                $data = array(
                    'image_path' => $imgname,
                    'unique_id' => $unique_id
                );
                $result = $this->master->save($data);
                $this->session->set_flashdata('success', 'Data Saved Successfully.');
                redirect('back-office/gallary');
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
