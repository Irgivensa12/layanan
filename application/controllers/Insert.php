<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Controller {
    
    Public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model("Model");
    }
    
    public function contoh2(){
        $inputAjax = $this->input->post('inputAjax');
		
        $this->Model->contoh2($inputAjax);
        redirect('menu');
    }
}