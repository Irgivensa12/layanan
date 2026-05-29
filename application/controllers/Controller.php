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
        $admin   = $this->session->userdata('admin');
        $sim     = $this->session->userdata('sim');
        $sarpras = $this->session->userdata('sarpras');

        $data['nama'] = $this->session->userdata('helpdesk_userNama');

        if ($admin == 'Y') {

            $data['role'] = 'Admin Hak Akses';
        } elseif ($sim == 'Y') {

            $data['role'] = 'Admin SIM';
        } elseif ($sarpras == 'Y') {

            $data['role'] = 'Admin Sarpras';
        } else {

            $data['role'] = 'User';
        }

        $this->load->view('header');
        $this->load->view('dashboard', $data);
    }

    public function tiket_sarpras()
    {
        $this->load->view('header');
        $this->load->view('tiket_sarpras');
    }

    public function tambah_tiket_sarpras()
    {
        $data['no_layanan'] = $this->Model->generate_no_layanan();
        $data['userId'] = $this->session->userdata('helpdesk_userId');
        $this->load->view('header');
        $this->load->view('tambah_tiket_sarpras', $data);
    }

    public function tambah_tiket_sim()
    {
        $data['no_tiket'] = $this->Model->generate_no_tiket();
        $data['userId'] = $this->session->userdata('helpdesk_userId');
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

    public function menu_user()
    {
        $data['title'] = 'Menu User';

        $data['user'] =
            $this->Model
            ->get_user_hak_akses()
            ->result();

        $this->load->view('header');
        $this->load->view('menu_user', $data);
    }
    public function switchToUser()
    {
        $this->session->set_userdata('as_user_biasa', true);

        redirect('dashboard');
    }
    public function backToAdmin()
    {
        $this->session->unset_userdata('as_user_biasa');

        redirect('dashboard');
    }
}
