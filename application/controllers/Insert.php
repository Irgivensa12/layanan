<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insert extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model("Model");
    }

    public function contoh2()
    {
        $inputAjax = $this->input->post('inputAjax');

        $this->Model->contoh2($inputAjax);
        redirect('menu');
    }

    public function input()
    {

        $data = array(
            'tikNomor'      => $this->input->post('tikNomor'),
            'tikTanggal'           => $this->input->post('tikTanggal'),
            'tikUserID'           => $this->input->post('tikUserID'),
            'userNama'           => $this->input->post('userNama'),
            'tikRingkasan'        => $this->input->post('tikRingkasan'),
            'tiketStatus'           => $this->input->post('tiketStatus')
        );

        $this->Model->insertData('tb_tiket', $data);

        redirect('menu');
    }

    // public function update()
    // {
    //     $id = $this->input->post('tikNomor');

    //     $data = array(
    //         'tikNomor'      => $this->input->post('tikNomor'),
    //         'tikTanggal'           => $this->input->post('tikTanggal'),
    //         'tikUserClose'           => $this->input->post('tikUserClose'),
    //         'tikRingkasan'        => $this->input->post('tikRingkasan'),
    //         'tiketStatus'           => $this->input->post('tiketStatus')
    //     );

    //     $this->db->where('tik_Nomor', $id);
    //     $this->db->update('tb_tiket', $data);

    //     redirect('menu');
    // }

    public function tambah_tiket_sarpras()
    {
        // generate ulang nomor tiket
        $no_layanan = $this->Model->generate_no_layanan();
        $data = [
            'layID'      => $no_layanan,
            'layTanggal'    => $this->input->post('layTanggal'),
            'layUserID'     => $this->input->post('layUserID'),
            'layDivisiID'  => $this->input->post('layDivisiID'),
            'layHal'  => $this->input->post('layHal'),
            'layDiskripsi'  => $this->input->post('layDiskripsi'),
            'layStatus'  => 'N',

        ];

        $this->Model->insertData_layanan('tb_layanan', $data);
        redirect('tiket_sarpras'); // setelah simpan, diarahkan ke halaman tiket_sarpras
    }

    public function tambah_tiket_sim()
    {
        // generate ulang nomor tiket
        $no_tiket = $this->Model->generate_no_tiket();

        $data = [
            'tikNomor'      => $no_tiket,
            'tikTanggal'    => $this->input->post('tikTanggal'),
            'tikUserID'     => $this->input->post('tikUserID'),
            'tikRingkasan'  => $this->input->post('tikRingkasan'),
            'tikStatus'  => 'N',
            'tikDeskripsi'  => $this->input->post('tikDeskripsi')
        ];

        $this->Model->insertData_sim('tb_tiket', $data);

        redirect('tiket_sim');
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
        $config['smtp_user'] = "email pengirim"; // email pengirim
        $config['smtp_pass'] = "pass smtp"; // password email (jika menggunakan gmail, gunakan password aplikasi)
        $config['smtp_timeout'] = 120; // waktu habis untuk koneksi SMTP (dalam detik)
        $config['crlf'] = "\r\n"; // karakter untuk baris baru dalam header email
        $config['newline'] = "\r\n"; // karakter untuk baris baru dalam isi email
        //$config['wordwrap'] = TRUE;

        $this->email->initialize($config); // menginisialisasi konfigurasi email dengan parameter yang telah ditentukan
        $this->email->set_newline("\r\n"); // mengatur karakter untuk baris baru dalam email, penting untuk memastikan format email yang benar terutama saat menggunakan protokol SMTP
        $this->email->from('email pengirim', 'PT Kanisius'); //from: email anda, nama profil email
        $this->email->to('email penerima'); // email penerima
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
