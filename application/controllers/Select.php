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

    public function dashboard()
    {
        $userId = $this->session->userdata('helpdesk_userId');
        $admin  = $this->session->userdata('admin');

        // ================= SIM =================

        $this->db->from('tb_tiket');
        if ($admin != 'Y') {
            $this->db->where('tikUserID', $userId);
        }
        $tiket_sim_total = $this->db->count_all_results();

        $this->db->from('tb_tiket');
        $this->db->where('tikStatus', 'N');
        if ($admin != 'Y') {
            $this->db->where('tikUserID', $userId);
        }
        $tiket_sim_open = $this->db->count_all_results();

        $this->db->from('tb_tiket');
        $this->db->where('tikStatus', 'Y');
        if ($admin != 'Y') {
            $this->db->where('tikUserID', $userId);
        }
        $tiket_sim_close = $this->db->count_all_results();

        // ================= SARPRAS =================

        $this->db->from('tb_layanan');
        if ($admin != 'Y') {
            $this->db->where('layUserID', $userId);
        }
        $tiket_sarpras_total = $this->db->count_all_results();

        $this->db->from('tb_layanan');
        $this->db->where('layStatus', 'N');
        if ($admin != 'Y') {
            $this->db->where('layUserID', $userId);
        }
        $tiket_sarpras_open = $this->db->count_all_results();

        $this->db->from('tb_layanan');
        $this->db->where('layStatus', 'Y');
        if ($admin != 'Y') {
            $this->db->where('layUserID', $userId);
        }
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

    public function tiket_sarpras()
    {
       $data = $this->Model->tiket_sarpras()->result_array(); // buka class tiket_sim di Model
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

    public function get_petugas()
    {
        $this->db->select('pegNoreg, pegNama');
        $this->db->from('ref_operator');
        $this->db->join('db_kbs_hris.ref_pegawai', 'pegNoreg = opNoreg', 'left');
        $this->db->where('opJenis', 2);

        $query = $this->db->get()->result();

        echo json_encode($query);
    }

    public function get_operator_sim()
    {
        $this->db->select('pegNoreg, pegNama');
        $this->db->from('db_kbs_hris.ref_pegawai');
        $this->db->where_in('pegNoreg', [9722, 2054]);

        $data = $this->db->get()->result();

        echo json_encode($data);
    }

    public function detail_tiket($tikNomor = null)
    {
        $this->db->select("
        t.tikNomor,
        t.tikTanggal,
        t.tikUserID,
        u.userNama,
        t.tikRingkasan,
        t.tikDeskripsi,
        (CASE 
            WHEN t.tikStatus='Y' THEN 'CLOSE' 
            ELSE 'OPEN' 
        END) AS tikStatus,
        t.tikOperator,
        p.pegNama as operatorNama,  
        t.tikTindakan
    ");

        $this->db->from('tb_tiket t');
        $this->db->join('vuser u', 't.tikUserID = u.userID', 'left');


        $this->db->join('db_kbs_hris.ref_pegawai p', 't.tikOperator = p.pegNoreg', 'left');

        $this->db->where('t.tikNomor', $tikNomor);

        $data = $this->db->get()->row_array();
        $data['tikTindakan'] = strip_tags($data['tikTindakan']); // Menghapus tag HTML dari tikTindakan

        echo json_encode($data);
    }

    public function detail_tiket_sarpras($tikNomor = null)
    {
        $this->db->select("
        layID,
        layTanggal,
        layUserID,
        userNama,
        layHal,
        layDiskripsi,
        layPetugas1,
        layPetugas2,
        layPetugas3,
        layPetugas4,
        layPetugas5,
        (CASE 
            WHEN layStatus='Y' THEN 'CLOSE' 
            ELSE 'OPEN' 
        END) AS layStatus
    ");

        $this->db->from('tb_layanan');
        $this->db->join('vuser', 'layUserID = userID', 'left');
        $this->db->where('layID', $tikNomor);

        $data = $this->db->get()->row_array();
        $data['layTindakan'] = strip_tags($data['layTindakan']); // Menghapus tag HTML dari layTindakan

        $data['petugas'] = [
            $data['layPetugas1'],
            $data['layPetugas2'],
            $data['layPetugas3'],
            $data['layPetugas4'],
            $data['layPetugas5']
        ];


        $petugasNama = [];

        foreach ($data['petugas'] as $p) {
            if (!empty($p)) {
                $nama = $this->db
                    ->select('userNama')
                    ->from('vuser')
                    ->where('userID', $p)
                    ->get()
                    ->row();

                if ($nama) {
                    $petugasNama[] = $nama->userNama;
                }
            }
        }

        // tetap gabung jadi 1 baris + tetap ada default
        $data['penanggungJawab'] = !empty($petugasNama)
            ? implode(', ', $petugasNama)
            : 'Belum ditentukan';

        echo json_encode($data);
    }
    public function form_sarpras($layID = null)
    {
        $this->db->select("
        layID,
        layTanggal,
        layUserID,
        userNama,
        layHal,
        layDiskripsi,
        layTarget,
        layPetugas1,
        layPetugas2,
        layPetugas3,
        layPetugas4,
        layPetugas5,
        (CASE 
            WHEN layStatus='Y' THEN 'CLOSE' 
            ELSE 'OPEN' 
        END) AS layStatus
    ");

        $this->db->from('tb_layanan');
        $this->db->join('vuser', 'layUserID = userID', 'left');
        $this->db->where('layID', $layID);

        $data = $this->db->get()->row_array();

        // ambil nama petugas satu per satu (TETAP)
        $petugas = [];

        $listPetugas = [
            $data['layPetugas1'],
            $data['layPetugas2'],
            $data['layPetugas3'],
            $data['layPetugas4'],
            $data['layPetugas5']
        ];

        foreach ($listPetugas as $p) {
            if (!empty($p)) {
                $nama = $this->db
                    ->select('userNama')
                    ->from('vuser')
                    ->where('userID', $p)
                    ->get()
                    ->row();

                if ($nama) {
                    $petugas[] = $nama->userNama;
                }
            }
        }

        // tetap gabung jadi 1 baris + tetap ada default
        $data['penanggungJawab'] = !empty($petugas)
            ? implode(', ', $petugas)
            : 'Belum ditentukan';

        echo json_encode($data);
    }
}
