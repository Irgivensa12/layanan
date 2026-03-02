<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Select extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model("Model");
    }

    public function contoh1()
    {
        $data = $this->Model->contoh1()->result_array(); // buka class contoh1 di Model
        echo json_encode($data);
    }
    public function contoh2()
    {
        $inputAjax = $this->input->get('inputAjax');

        $data = $this->Model->contoh2($inputAjax)->result_array(); // buka class contoh2 di Model dengan inputAjax
        echo json_encode($data);
    }

    public function lapor_kerusakan() // Function untuk menampilkan data dari tabel tb_tiket ke halaman lapor_kerusakan.php
    {
        $data = $this->Model->lapor_kerusakan()->result_array(); // buka class lapor_kerusakan di Model
        echo json_encode($data);
    }

    public function dashboard() // Function untuk menampilkan data dari tabel tb_tiket ke halaman dashboard.php
    {
        $data = $this->Model->dashboard()->result_array(); // buka class dashboard di Model
        echo json_encode($data);
}
