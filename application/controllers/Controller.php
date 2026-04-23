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
        $pengguna = $this->session->userdata('pengguna');
        $admin   = $this->session->userdata('admin');

        // Mengambil nama dari session
        $data['nama'] = $this->session->userdata('userNama');

        if ($admin == 'Y') {
            $data['role'] = 'Admin';
        } else {
            $data['role'] = 'Pengguna';
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
        $data['userId'] = $this->session->userdata('userId');
        $this->load->view('header');
        $this->load->view('tambah_tiket_sarpras', $data);
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

    public function admin_sim()
    {

        $this->load->view('header');
        $this->load->view('admin_sim');
    }

    public function admin_sarpras()
    {
        $this->load->view('header');
        $this->load->view('admin_sarpras');
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
        // ambil data tiket
        $tiket = $this->db->get_where('tb_tiket', ['tikNomor' => $tikNomor])->row();

        if (!$tiket) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            return;
        }

        // VALIDASI OPERATOR
        if (empty($tiket->tikOperator)) {
            echo json_encode(['status' => 'error', 'message' => 'Operator belum dipilih']);
            return;
        }

        // VALIDASI TINDAKAN
        if (empty(trim($tiket->tikTindakan))) {
            echo json_encode(['status' => 'error', 'message' => 'Tindakan masih kosong']);
            return;
        }

        // lanjut close
        $data = [
            'tikStatus' => 'Y',
            'tikUserClose' => $this->session->userdata('userId'),
            'tikDateClose' => date('Y-m-d H:i:s')
        ];

        $this->Model->update_tiket($tikNomor, $data);

        echo json_encode(['status' => 'ok']);
    }

    public function close_sarpras($layID)
    {
        // ambil data layanan
        $layanan = $this->db->get_where('tb_layanan', ['layID' => $layID])->row();

        if (!$layanan) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            return;
        }

        // ambil semua petugas
        $petugas = [
            $layanan->layPetugas1,
            $layanan->layPetugas2,
            $layanan->layPetugas3,
            $layanan->layPetugas4,
            $layanan->layPetugas5
        ];

        // filter yang tidak kosong
        $petugasValid = array_filter($petugas);

        // kalau belum ada petugas
        if (count($petugasValid) == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Penanggung jawab belum dipilih'
            ]);
            return;
        }

        // lanjut close
        $data = [
            'layStatus' => 'Y',
            'layCloseUserID' => $this->session->userdata('userId'),
            'layCloseTanggal' => date('Y-m-d H:i:s')
        ];

        $this->db->where('layID', $layID);
        $this->db->update('tb_layanan', $data);

        echo json_encode(['status' => 'ok']);
    }

    public function simpan_petugas() // function untuk menyimpan petugas ke database
    {
        $layID   = $this->input->post('layID'); // ID layanan yang akan diupdate petugasnya
        $petugas = $this->input->post('petugas'); // array petugas yang dikirim dari frontend

        // reset semua petugas menjadi null terlebih dahulu
        $data = [
            'layPetugas1' => null,
            'layPetugas2' => null,
            'layPetugas3' => null,
            'layPetugas4' => null,
            'layPetugas5' => null,
        ];

        // isi sesuai urutan
        for ($i = 0; $i < count($petugas); $i++) {
            $data['layPetugas' . ($i + 1)] = $petugas[$i];
        }

        $this->db->where('layID', $layID); // pastikan update berdasarkan layID
        $this->db->update('tb_layanan', $data);

        $this->kirimEmail("SARPRAS", $layID, "irgiv1204@gmail.com");

        echo json_encode(['status' => 'ok']);
    }

    public function simpan_tindakan() // function untuk menyimpan tindakan ke database
    {
        $tikNomor = $this->input->post('tikID'); // nomor tiket yang akan diupdate tindakannya
        $tindakan = $this->input->post('tikTindakan'); // tindakan yang dikirim dari frontend
        $operator  = $this->input->post('tikOperator'); // operator yang mengupdate tindakan (bisa dipakai untuk log)
        $data = [
            'tikTindakan' => $tindakan,
            'tikOperator' => $operator
        ];

        $this->Model->update_tiket($tikNomor, $data); // update data tindakan di database

        // ambil email user dari database
        // $tiket = $this->Model->get_tiket($tikNomor);
        // $email = $tiket->userEmail;

        // kirim email
        $this->kirimEmail("SIM", $tikNomor, "irgiv1204@gmail.com");

        echo json_encode(['status' => 'ok']);
    }

    public function kirimEmail($jenis, $nomor, $userEmail)
    {
        $judul = "Update Tiket $jenis";
        $pesan = "
                <h3>Penugasan Tiket Baru</h3>
                <p><b>Jenis:</b> $jenis</p>
                <p><b>Nomor Tiket:</b> $nomor</p>
                <p>Silakan cek sistem untuk detail lebih lanjut.</p>
            ";

        $this->load->library('email');
        // $config = array();
        $config['charset'] = 'utf-8';
        // $config['useragent'] = 'Codeigniter';
        $config['protocol'] = "smtp"; // protokol yang digunakan untuk mengirim email
        $config['mailtype'] = "html"; // tipe email yang dikirim, bisa berupa 'text' atau 'html'
        $config['smtp_host'] = "smtp.gmail.com"; //ubah host email
        $config['smtp_port'] = "587"; // host port
        $config['smtp_crypto'] = "tls"; // enkripsi yang digunakan 'tls' yang umum digunakan untuk port 587
        $config['smtp_user'] = "chandrasekhara176@gmail.com"; // email pengirim
        $config['smtp_pass'] = "abemlquqfkmuwomb"; // password email (jika menggunakan gmail, gunakan password aplikasi)
        $config['smtp_timeout'] = 120; // waktu habis untuk koneksi SMTP (dalam detik)
        $config['crlf'] = "\r\n"; // karakter untuk baris baru dalam header email
        $config['newline'] = "\r\n"; // karakter untuk baris baru dalam isi email
        //$config['wordwrap'] = TRUE;

        $this->email->initialize($config); // menginisialisasi konfigurasi email dengan parameter yang telah ditentukan
        $this->email->set_newline("\r\n"); // mengatur karakter untuk baris baru dalam email, penting untuk memastikan format email yang benar terutama saat menggunakan protokol SMTP
        $this->email->from('chandrasekhara176@gmail.com', 'PT Kanisius'); //from: email anda, nama profil email
        $this->email->to('irgiv1204@gmail.com'); // email penerima
        $this->email->subject($judul); // subject email
        $this->email->message($pesan); // isi pesan email, dalam hal ini menggunakan format HTML karena mailtype diatur ke 'html'

        if ($this->email->send()) { //kirim email, if sukses:
            $this->session->set_flashdata('type', 'alert-info');
            $this->session->set_flashdata('pesan', '<strong>Sukses!</strong> Email terkirim');
        } else { //if gagal kirim email
            $this->session->set_flashdata('type', 'alert-danger');
            $this->session->set_flashdata('pesan', '<strong>Error!</strong> pesan error: <br><br>' . $this->email->print_debugger());
        }
    }
}
