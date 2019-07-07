<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MasterModel', 'master');
        $this->perPage = 2;
    }

    public function index() {
        $list["keyword"] = "CTCROWD,CITY,Blog,Adsense,PHP, SEO, Make money Blogging, Affiliate marketing,News,Technology,Social,Science,Sports,";
        $list["description"] = "CTCROWD is an award winning blog that
talks about living a boss free life with blogging.
We cover about Adsense,PHP, SEO, Make money Blogging, Affiliate marketing,News,Technology,Social,Science,Sports,Everything will get in this blog.";
        $this->table = "tbl_home_special_category";
//slider
        $listSpo = $this->master->get_by_column_row("main_category", "Slider");
        $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        $this->table = "tbl_home_special";
        $list["slider"] = $this->master->get_by_limit(3, $where);

// Trending
        $this->table = "tbl_home_special_category";
        $listTre = $this->master->get_by_column_row("main_category", "Trending");
        $whereTre = array("where" => "menu_id = '" . $listTre->id . "'");
        $this->table = "tbl_home_special";
        $list["Trending"] = $this->master->get_by_limit(6, $whereTre);


//        //Entertainment
        $this->table = "tbl_home_special_category";
        $listEnt = $this->master->get_by_column_row("main_category", "Entertainment");
        $whereEnt = array("where" => "menu_id = '" . $listEnt->id . "'");
        $this->table = "tbl_home_special";
        $list["Entertainment"] = $this->master->get_by_limit(6, $whereEnt);

        $this->table = "tbl_home_special_category";
        $listMo = $this->master->get_by_column_row("main_category", "Momentum");
        $whereMo = array("where" => "menu_id = '" . $listMo->id . "'");
        $this->table = "tbl_entity";
        $list["Momentum"] = $this->master->get_by_limit(5, $whereMo);
//
        $this->table = "tbl_home_special_category";
        $list["tags"] = $this->master->get_distinct("main_category");
        $this->load->view('index', $list);
    }

    public function lists($entity, $id) {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "special-list/" . $entity . "/" . $id;
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;

        $this->table = "tbl_home_special_category";
        $list["tags"] = $this->master->get_all();
        if (isset($id)) {
            $listSpo = $this->master->get_by_column_row("unique_id", $id);
            $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        } else {
            $where = "";
        }

        $this->table = "tbl_entity";
        $list["Momentum"] = $this->master->get_top_five();

        $this->table = "tbl_home_special";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('data_home_special', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination($where);
            $this->load->view('home_special_list', $list);
        }
    }

    public function details($entity, $id) {
        $this->table = "tbl_home_special";
        $this->db->query("update tbl_home_special set no_of_views=no_of_views + 1 where unique_id= '$id'");
        $list["datas"] = $data = $this->master->get_by_column("unique_id", $id);
        $this->table = "tbl_comments";
        $list["comments"] = $this->master->get_by_column("data_id", $id);

        $list["keyword"] = $data[0]->meta_keyword;
        $list["description"] = $data[0]->meta_description;
        $list["webUrl"] = base_url() . "special/" . $entity . "/" . $id;
        $list["webTitle"] = $data[0]->title;
        $list["webDescription"] = $data[0]->description;
        $list["webImage"] = base_url() . "uploads/tumbnail/" . $data[0]->image_path;

        $this->table = "tbl_menu";
        $list["tags"] = $this->master->get_distinct("main_category");

        $whereMo = array("where" => "main_category != 'Entertainment'");
        $this->table = "tbl_entity";
        $list["Momentum"] = $this->master->get_by_limit(5, $whereMo);
        $list["related"] = $this->master->get_by_limit(6, $whereMo);

        $this->load->view('home_special', $list);
    }

    public function privacy() {
        $list["keyword"] = "Privacy and Policy";
        $list["description"] = "Privacy and Policy";
        $this->load->view('privacy', $list);
    }

    public function terms() {
        $list["keyword"] = "Terms and Condition";
        $list["description"] = "Terms and Condition";
        $this->load->view('terms_condition', $list);
    }

    public function disclaimer() {
        $list["keyword"] = "Disclaimer";
        $list["description"] = "Disclaimer";
        $this->load->view('disclaimer', $list);
    }

    public function comments_save() {

//        if (!$this->input->is_ajax_request()) {
//            exit('No direct script access allowed');
//        }

        $this->table = "tbl_comments";
        $unique_id = UniqueID();

        $parent_id = $this->input->post('parent_id') == "" ? 0 : $this->input->post('parent_id');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('mobile', 'mobile', 'required|min_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, 'message' => validation_errors()));
        } else {
            $data = array(
                'name' => strtolower($this->input->post('name')),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'unique_id' => $unique_id,
                'data_id' => $this->input->post('data_id'),
                'parent_id' => $parent_id,
                'description' => $this->input->post('message')
            );
            $insert = $this->master->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

}
