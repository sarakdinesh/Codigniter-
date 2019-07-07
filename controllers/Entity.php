<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entity extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MasterModel', 'master');
        $this->perPage = 2;
    }

    public function index($entity, $id) {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "entity/" . $entity . "/" . $id;
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;

        //Momentum
        $this->table = "tbl_home_special_category";
        $listMo = $this->master->get_by_column_row("main_category", "Momentum");
        $whereMo = array("where" => "menu_id = '" . $listMo->id . "'");
        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_by_limit(5, $whereMo);


        $this->table = "tbl_menu";
        $listSpo = $this->master->get_by_column_row("unique_id", $id);
        $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        $this->table = "tbl_entity";
        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('data_entity', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination($where);
            $this->load->view('entity_list', $list);
        }
    }

    public function details($entity, $id) {
        $this->table = "tbl_entity";
        $this->db->query("update tbl_entity set no_of_views=no_of_views + 1 where unique_id= '$id'");
        $list["datas"] = $data = $this->master->get_by_column("unique_id", $id);
        $this->table = "tbl_comments";
        $list["comments"] = $this->master->get_by_column("data_id", $id);

        $list["keyword"] = $data[0]->meta_keyword;
        $list["description"] = $data[0]->meta_description;
        $list["webUrl"] = base_url() . "entity/" . $entity . "/" . $id;
        $list["webTitle"] = $data[0]->title;
        $list["webDescription"] = $data[0]->description;
        $list["webImage"] = base_url() . "uploads/tumbnail/" . $data[0]->image_path;
        $this->table = "tbl_menu";
        $list["tags"] = $this->master->get_by_column("main_category", $entity);

        $this->table = "tbl_top_ten";
        $list["Momentum"] = $this->master->get_top_five();

        $whereMo1 = array("where" => "main_category != '$entity'");
        $this->table = "tbl_entity";
        $list["related"] = $this->master->get_by_limit(5, $whereMo1);

        $this->load->view('entity_single', $list);
    }

    public function entitymain($entity) {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "entity/" . $entity;
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;

        //Momentum
        $this->table = "tbl_home_special_category";
        $listMo = $this->master->get_by_column_row("main_category", "Momentum");
        $whereMo = array("where" => "menu_id = '" . $listMo->id . "'");
        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_by_limit(5, $whereMo);
        $this->table = "tbl_menu";

        $list["tags"] = $this->master->get_by_column("main_category", $entity);

        //$listSpo = $this->master->get_by_column_row("main_category", $entity);
        $where = array("where" => "main_category = '" . $entity . "'");
        $this->table = "tbl_entity";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination($where);

            $resultssss = $this->load->view('data_entity', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination($where);
            $this->load->view('entity_list', $list);
        }
    }

    public function privacy() {
        $list["keyword"] = "sdasd,sadas,dasdasd,asd";
        $list["description"] = "sdasd,sadas,dasdasd,asd";
        $this->load->view('privacy', $list);
    }

}
