<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

class Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model("Model");
    }

    public function login()
    {
        $this->Model->login();
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }

    // http://localhost/template_penerbitan/
    public function index()
    {
        $this->load->view('login');
    }

    // http://localhost/template_penerbitan/menu
    public function menu()
    {
        $data['nama'] = $this->session->userdata('userNama');

        if ($this->session->userdata('admin') == 'Y') {
            $data['role'] = 'Admin';
        } elseif ($this->session->userdata('sarpras') == 'Y') {
            $data['role'] = 'Petugas Sarpras';
        } else {
            $data['role'] = 'User';
        }
        $this->load->view('header', $data);
        $this->load->view('dashboard', $data); // redirect ke dashboard setelah login
    }

    public function input()
    {
        $this->load->view('header');
        $this->load->view('input');
    }
    public function tiket_sim()
    {
        $this->load->view('header');
        $this->load->view('tiket_sim');
    }

    public function dashboard()
    {
        // cek login
        if (!$this->session->userdata('userId')) {
            redirect();
        }

        // cek privilege
        $sarpras = $this->session->userdata('sarpras');
        $admin   = $this->session->userdata('admin');

        // jika user biasa, tidak boleh buka dashboard
        if ($admin != 'Y' && $sarpras != 'Y') {
            redirect('Controller/tiket_sim');
        }

        // Mengambil nama dari session
        $data['nama'] = $this->session->userdata('userNama');

        if ($admin == 'Y') {
            $data['role'] = 'Admin';
        } elseif ($sarpras == 'Y') {
            $data['role'] = 'Operator';
        } else {
            $data['role'] = 'User';
        }

        $this->load->view('header', $data);
        $this->load->view('dashboard', $data);
    }

    public function tiket_sarpras()
    {
        $this->load->view('header');
        $this->load->view('tiket_sarpras');
    }

    public function tambah_tiket_sarpras()
    {
        $this->load->view('header');
        $this->load->view('tambah_tiket_sarpras');
    }

    public function tambah_tiket_sim()
    {
        $data['no_tiket'] = $this->Model->generate_no_tiket();
        $data['userId'] = $this->session->userdata('userId');
        $this->load->view('header');
        $this->load->view('tambah_tiket_sim', $data);
    }

    public function laporan_sarpras()
    {
        $this->load->view('header');
        $this->load->view('laporan_sarpras');
    }

    public function laporan_sim()
    {
        $this->load->view('header');
        $this->load->view('laporan_sim');
    }

    public function admin()
    {

        $this->load->view('header');
        $this->load->view('admin');
    }

    public function daftar_tiket()
    {
        $role   = $this->session->userdata('role');
        $userID = $this->session->userdata('userID');

        if ($role == 'operator') {

            $data = $this->Model->tiket_operator()->result();
        } elseif ($role == 'sim') {

            $data = $this->Model->tiket_sim($userID)->result();
        } elseif ($role == 'sarpras') {

            $data = $this->Model->tiket_sarpras($userID)->result();
        } else {

            $data = [];
        }

        echo json_encode($data);
    }

    public function close_tiket($tikNomor)
    {

        $data = [
            'tikStatus' => 'Y', // Set status tiket menjadi 'N' (CLOSE)
            'tikUserClose' => $this->session->userdata('userId'), // Ambil userId dari session untuk tikUserClose
            'tikDateClose' => date('Y-m-d H:i:s') // format tanggal dan waktu saat ini
        ];

        $this->Model->update_tiket($tikNomor, $data);
    }
}
