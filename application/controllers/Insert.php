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



        public function tambah_tiket_sarpras()
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('layTanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('layHal', 'Keluhan', 'required');
            $this->form_validation->set_rules('layDiskripsi', 'Deskripsi', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', validation_errors());
                redirect('Controller/tambah_tiket_sarpras');
                return;
            }

            $no_layanan = $this->Model->generate_no_layanan();

            $data = [
                'layID'         => $no_layanan,
                'layTanggal'    => $this->input->post('layTanggal'),
                'layUserID'     => $this->input->post('layUserID'),
                'layHal'        => trim($this->input->post('layHal')),
                'layDiskripsi'  => trim($this->input->post('layDiskripsi')),
                'layTarget'     => $this->input->post('layTarget'),
                'layStatus'     => 'N'
            ];

            $this->Model->insertData_layanan('tb_layanan', $data);

            $this->session->set_flashdata('type', 'alert-success');
            $this->session->set_flashdata('pesan', 'Layanan berhasil ditambahkan!');

            redirect('tiket_sarpras');
        }

        public function tambah_tiket_sim()
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('tikTanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('tikRingkasan', 'Ringkasan', 'required');
            $this->form_validation->set_rules('tikDeskripsi', 'Deskripsi', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', validation_errors());
                redirect('Controller/tambah_tiket_sim');
                return;
            }

            $no_tiket = $this->Model->generate_no_tiket();

            $data = [
                'tikNomor'      => $no_tiket,
                'tikTanggal'    => $this->input->post('tikTanggal'),
                'tikUserID'     => $this->input->post('tikUserID'),
                'tikRingkasan'  => trim($this->input->post('tikRingkasan')),
                'tikStatus'     => 'N',
                'tikDeskripsi'  => trim($this->input->post('tikDeskripsi'))
            ];

            $this->Model->insertData_sim('tb_tiket', $data);

            $this->session->set_flashdata('type', 'alert-success');
            $this->session->set_flashdata('pesan', 'Tiket berhasil dibuat!');

            redirect('tiket_sim');
        }

        public function close_tiket($tikNomor)
        {
            $admin = $this->session->userdata('admin');
            $sim   = $this->session->userdata('sim');

            if ($admin == 'Y' || $sim == 'Y') {

                echo json_encode([
                    'status' => false,
                    'pesan' => 'Admin tidak boleh close tiket!'
                ]);
                return;
            }
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
            $admin   = $this->session->userdata('admin');
            $sarpras = $this->session->userdata('sarpras');
            $user    = $this->session->userdata('helpdesk_userId');

            // hanya admin sarpras atau user biasa yang boleh
            if ($admin == 'Y' && $sarpras != 'Y') {

                echo json_encode([
                    'status' => false,
                    'pesan'  => 'Anda tidak memiliki akses!'
                ]);
                return;
            }

            // ambil data layanan
            $layanan = $this->db->get_where('tb_layanan', ['layID' => $layID])->row();

            if (!$layanan) {

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Data tidak ditemukan'
                ]);
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

            // validasi penanggung jawab
            if (count($petugasValid) == 0) {

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Penanggung jawab belum dipilih'
                ]);
                return;
            }

            // USER BIASA hanya boleh close miliknya sendiri
            if ($sarpras != 'Y') {

                if ($layanan->layUserID != $user) {

                    echo json_encode([
                        'status' => false,
                        'pesan'  => 'Anda tidak bisa close tiket ini!'
                    ]);
                    return;
                }
            }

            // lanjut close
            $data = [
                'layStatus'        => 'Y',
                'layCloseUserID'   => $user,
                'layCloseTanggal'  => date('Y-m-d H:i:s')
            ];

            $this->db->where('layID', $layID);
            $this->db->update('tb_layanan', $data);

            echo json_encode([
                'status' => 'ok'
            ]);
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
