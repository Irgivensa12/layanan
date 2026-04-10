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

    public function dashboard()
    {
        // hitung tiket SIM
        $this->db->from('tb_tiket');
        $tiket_sim_total = $this->db->count_all_results();

        // hitung tiket Sarpras (kalau tabelnya sama bisa diganti)
        $this->db->from('tb_layanan');
        $tiket_sarpras_total = $this->db->count_all_results();

        // tiket open
        $this->db->from('tb_tiket');
        $this->db->where('tikStatus', 'N');
        $tiket_sim_open = $this->db->count_all_results();

        // tiket close
        $this->db->from('tb_tiket');
        $this->db->where('tikStatus', 'Y');
        $tiket_sim_close = $this->db->count_all_results();

        // sarpras open
        $this->db->from('tb_layanan');
        $this->db->where('layStatus', 'N');
        $tiket_sarpras_open = $this->db->count_all_results();

        // sarpras close
        $this->db->from('tb_layanan');
        $this->db->where('layStatus', 'Y');
        $tiket_sarpras_close = $this->db->count_all_results();

        echo json_encode([
            "tiket_sim_total" => $tiket_sim_total,
            "tiket_sim_open" => $tiket_sim_open,
            "tiket_sim_close" => $tiket_sim_close,
            "tiket_sarpras_total" => $tiket_sarpras_total,
            "tiket_sarpras_open" => $tiket_sarpras_open,
            "tiket_sarpras_close" => $tiket_sarpras_close
        ]);
    }

    public function tiket_sim() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sim.php
    {
        $data = $this->Model->tiket_sim()->result_array(); // buka class tiket_sim di Model
        echo json_encode($data);
    }

    public function tiket_sarpras() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sarpras.php
    {
        $data = $this->Model->tiket_sarpras()->result_array(); // buka class tiket_sarpras di Model
        echo json_encode($data);
    }

    public function admin_sim() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sarpras.php
    {
        $data = $this->Model->admin_sim()->result_array(); // buka class admin_sim di Model
        echo json_encode($data);
    }
    public function admin_sarpras() // Function untuk menampilkan data dari tabel tb_tiket ke halaman tiket_sarpras.php
    {
        $data = $this->Model->admin_sarpras()->result_array(); // buka class admin_sarpras di Model
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
        tikDeskripsi,
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
