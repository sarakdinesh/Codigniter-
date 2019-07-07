<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Topten extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MasterModel', 'master');
        $this->perPage = 2;
    }

    public function index() {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "entity/" . $entity . "/" . $id;
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;


        $this->table = "tbl_top_ten_category";
        $list["tags"] = $this->master->get_all();
        if (isset($id)) {
            $listSpo = $this->master->get_by_column_row("unique_id", $id);
            $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        } else {
            $where = "";
        }

        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_top_five();

        $this->table = "tbl_top_ten";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('data_top_ten', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination($where);
            $this->load->view('top_ten_list', $list);
        }
    }

    public function index2($entity, $id) {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "entity/" . $entity . "/" . $id;
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;


        $this->table = "tbl_top_ten_category";
        $list["tags"] = $this->master->get_all();
        if (isset($id)) {
            $listSpo = $this->master->get_by_column_row("unique_id", $id);
            $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        } else {
            $where = "";
        }

        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_top_five();

        $this->table = "tbl_top_ten";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('data_top_ten', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination($where);
            $this->load->view('top_ten_list', $list);
        }
    }

    public function details($entity, $id) {

        $this->table = "tbl_top_ten";
        $this->db->query("update tbl_top_ten set no_of_views=no_of_views + 1 where unique_id= '$id'");
        $list["datas"] = $data = $this->master->get_by_column("unique_id", $id);
        $this->table = "tbl_comments";
        $list["comments"] = $this->master->get_by_column("data_id", $id);

        $list["keyword"] = $data[0]->meta_keyword;
        $list["description"] = $data[0]->meta_description;
        $list["webUrl"] = base_url() . "topten/" . $entity . "/" . $id;
        $list["webTitle"] = $data[0]->title;
        $list["webDescription"] = $data[0]->description;
        $list["webImage"] = base_url() . "uploads/tumbnail/" . $data[0]->image_path;


        $this->table = "tbl_top_ten_category";
        $list["tags"] = $this->master->get_all();

        //WHERE title LIKE '%match%'

        $this->table = "tbl_entity";
        $list["Momentum"] = $this->master->get_top_five();

        $whereMo = array("where" => "main_category != 'Slider'");
        $this->table = "tbl_home_special";
        $list["related"] = $this->master->get_by_limit(6, $whereMo);

        $this->load->view('top_ten_single', $list);
    }

}
