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

    public function tiket_sim() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sim.php
    {
        $data = $this->Model->tiket_sim()->result_array(); // buka class tiket_sim di Model
        echo json_encode($data);
    }

    public function dashboard() // Function untuk menampilkan data dari tabel tb_tiket ke halaman dashboard.php
    {
        $data = $this->Model->dashboard()->result_array(); // buka class dashboard di Model
        echo json_encode($data);
    }

    public function tiket_sarpras() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sarpras.php
    {
        $data = $this->Model->tiket_sarpras()->result_array(); // buka class tiket_sarpras di Model
        echo json_encode($data);
    }

    public function detail_tiket($tikNomor = null)
    {
        $this->db->select("
        tikNomor,
        tikTanggal,
        tikUserID,
        userNama,
        tikRingkasan,
        (CASE 
            WHEN tikStatus='Y' THEN 'CLOSE' 
            ELSE 'OPEN' 
        END) AS tiketStatus
    ");

        $this->db->from('tb_tiket');
        $this->db->join('vuser', 'tikUserID = userID', 'left');
        $this->db->where('tikNomor', $tikNomor);

        $data = $this->db->get()->row_array();

        echo json_encode($data);
    }
}
