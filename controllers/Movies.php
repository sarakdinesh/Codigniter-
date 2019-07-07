<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movies extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MasterModel', 'master');
        $this->perPage = 2;
        $this->load->library("pagination");
    }

    public function importance() {
        //$this->load->view('story');
        $this->perPage = 2;
        $list["keyword"] = "Download free movies,get free movies,ctcrowd,mp4 movies,hollywood movies,best movies,";
        $list["description"] = "ctcrowd movies,300mb Movies 8xfilms watch download bollywood movies 9xfilms TV shows katmoviehd HD Movies 720p movies HD Movies 1080p BluRay HDRip ctcrowd movies 480p movies ctcrowd movies";
        $list["webUrl"] = "politics";
        $list["webTitle"] = "ctcrowd movies,youtube movies,and videos";
        $list["webDescription"] = "";
        $list["webImage"] = "";
        $list["MainTitle"] = "ctcrowd movies";

        $this->table = "tbl_story";
        $data = array();

        // Get posts data from the database
        $conditions['order_by'] = "id DESC";
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->master->getRows_for_pagination($conditions);

        // Pass the post data to view
        $this->load->view('story', $data);
    }

    function loadMoreData() {
        $this->perPage = 2;
        $conditions = array();
        $this->table = "tbl_story";
        // Get last post ID
        $lastID = $this->input->post('id');
        // Get post rows num
        $conditions['where'] = array('id <' => $lastID);
        $conditions['returnType'] = 'count';
        $data['postNum'] = $this->master->getRows_for_pagination($conditions);

        // Get posts data from the database
        $conditions['returnType'] = '';
        $conditions['order_by'] = "id DESC";
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->master->getRows_for_pagination($conditions);

        $data['postLimit'] = $this->perPage;

        // Pass data to view
        $this->load->view('data_story', $data, false);
    }

    public function story_details($entity, $id) {
        $this->table = "tbl_story";
        $list["datas"] = $data = $this->master->get_by_column("unique_id", $id);

        $list["keyword"] = $data[0]->meta_keyword;
        $list["description"] = $data[0]->meta_description;
        $list["webUrl"] = base_url() . "story/" . $entity . "/" . $id;
        $list["webTitle"] = $data[0]->title;
        $list["webDescription"] = "";
        $list["webImage"] = "";

        $this->load->view('story_single', $list);
    }

    public function index() {
        $list["keyword"] = "Download free movies,get free movies,ctcrowd,mp4 movies,hollywood movies,best movies,";
        $list["description"] = "ctcrowd movies,300mb Movies 8xfilms watch download bollywood movies 9xfilms TV shows katmoviehd HD Movies 720p movies HD Movies 1080p BluRay HDRip ctcrowd movies 480p movies ctcrowd movies";
        $list["webUrl"] = "movies";
        $list["webTitle"] = "ctcrowd movies";
        $list["webDescription"] = "";
        $list["webImage"] = "";
        $list["MainTitle"] = "ctcrowd movies";


        $this->table = "tbl_entertainment_category";
        $list["tags"] = $this->master->get_all();
        if (isset($id)) {
            $listSpo = $this->master->get_by_column_row("unique_id", $id);
            $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        } else {
            $where = "";
        }

        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_top_five();

        $this->table = "tbl_entertainment";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination_movies($where);

            $resultssss = $this->load->view('data_movies', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination_movies($where);
            $this->load->view('movies_list', $list);
        }
    }

    public function index2($entity, $id) {
        $list["keyword"] = $entity;
        $list["description"] = $entity;
        $list["webUrl"] = base_url() . "movies";
        $list["webTitle"] = $entity;
        $list["webDescription"] = $entity;
        $list["webImage"] = "";
        $list["MainTitle"] = $entity;


        $this->table = "tbl_entertainment_category";
        $list["tags"] = $this->master->get_all();
        if (isset($id)) {
            $listSpo = $this->master->get_by_column_row("unique_id", $id);
            $where = array("where" => "menu_id = '" . $listSpo->id . "'");
        } else {
            $where = "";
        }
        $this->table = "tbl_home_special";
        $list["Momentum"] = $this->master->get_top_five();

        $this->table = "tbl_entertainment";

        if (!empty($this->input->get("page"))) {
            $list["datas"] = $this->master->get_data_for_pagination_movies($where);

            $resultssss = $this->load->view('data_movies', $list);
            echo json_encode($resultssss);
        } else {
            $list["datas"] = $this->master->get_data_for_pagination_movies($where);
            $this->load->view('movies_list', $list);
        }
    }

    public function search() {

        $list["keyword"] = "Download free movies,get free movies,ctcrowd,mp4 movies,hollywood movies,best movies,";
        $list["description"] = "ctcrowd movies,300mb Movies 8xfilms watch download bollywood movies 9xfilms TV shows katmoviehd HD Movies 720p movies HD Movies 1080p BluRay HDRip ctcrowd movies 480p movies ctcrowd movies";
        $list["webUrl"] = "movies";
        $list["webTitle"] = "ctcrowd movies";
        $list["webDescription"] = "";
        $list["webImage"] = "";
        $list["MainTitle"] = "ctcrowd movies";

        $this->table = "tbl_entertainment_category";
        $list["tags"] = $this->master->get_all();
        $like = $this->input->post('search');
        $this->table = "tbl_entertainment";

        $list["datas"] = $this->master->get_data_for_pagination_movies($where, $like);
        $this->load->view('movies_search', $list);
    }

    public function details($entity, $id) {

        $this->table = "tbl_entertainment";
        $this->db->query("update tbl_entertainment set no_of_views=no_of_views + 1 where unique_id= '$id'");
        $list["datas"] = $data = $this->master->get_by_column("unique_id", $id);
        $this->table = "tbl_comments";
        $list["comments"] = $this->master->get_by_column("data_id", $id);

        $list["keyword"] = $data[0]->meta_keyword;
        $list["description"] = $data[0]->meta_description;
        $list["webUrl"] = base_url() . "topten/" . $entity . "/" . $id;
        $list["webTitle"] = $data[0]->title;
        $list["webDescription"] = $data[0]->description;
        $list["webImage"] = base_url() . "uploads/tumbnail/" . $data[0]->image_path;


        $this->table = "tbl_entertainment_category";
        $list["tags"] = $this->master->get_all();

        //WHERE title LIKE '%match%'

        $this->table = "tbl_entertainment";
        $list["Momentum"] = $this->master->get_top_five();

        $whereMo = array("where" => "main_category != 'Slider'");
        $this->table = "tbl_home_special";
        $list["related"] = $this->master->get_by_limit(6, $whereMo);

        $this->load->view('movies_single', $list);
    }

}
