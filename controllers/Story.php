<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MasterModel', 'master');
        $this->perPage = 1;
        $this->load->library("pagination");
    }

    public function index() {

        $page = 0;
        $data = array();
        $this->table = "tbl_story";
        //get rows count
        $conditions['returnType'] = 'count';
        $totalRec = $this->master->getRows($conditions);

        //pagination config
        $config['base_url'] = base_url() . 'story-custom';
        $config['uri_segment'] = 3;
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        //initialize pagination library
        $this->pagination->initialize($config);

        //define offset
        $page = $page;
        $offset = !$page ? 0 : $page;

        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->master->getRows($conditions);

        //load the list page view
        $this->load->view('story_1', $data);
    }

    public function custom($page) {
        $data = array();
        $this->table = "tbl_story";
        //get rows count
        $conditions['returnType'] = 'count';
        $totalRec = $this->master->getRows($conditions);

        //pagination config
        $config['base_url'] = base_url() . 'story-custom';
        $config['uri_segment'] = 3;
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        //initialize pagination library
        $this->pagination->initialize($config);

        //define offset
        $page = $page;
        $offset = !$page ? 0 : $page;

        //get rows
        $conditions['returnType'] = '';
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->master->getRows($conditions);

        //load the list page view
        $this->load->view('story_1', $data);
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

}
