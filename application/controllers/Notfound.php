<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notfound extends Home_Core_Controller{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $data['title'] = '404-Page not Found';
        $data['page_name']='404';
        $this->load->view('theme/'.$this->active_theme.'/index',$data);
    }
}

