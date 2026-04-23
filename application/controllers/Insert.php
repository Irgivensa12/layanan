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
            $keluhan   = trim($this->input->post('layHal'));
            $deskripsi = trim($this->input->post('layDiskripsi'));

            // VALIDASI KOSONG
            if (empty($keluhan) && empty($deskripsi)) {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', 'Mohon isi keluhan anda terlebih dahulu');
                redirect('Controller/tambah_tiket_sarpras'); // sesuaikan route
                return;
            }

            // VALIDASI TANGGAL
            $tanggal = $this->input->post('layTanggal');
            $target  = $this->input->post('layTarget');

            if ($tanggal > date('Y-m-d')) {
                echo "Tanggal tidak boleh lebih dari hari ini";
                return;
            }

            if ($target < $tanggal) {
                echo "Target tidak boleh sebelum tanggal";
                return;
            }

            // BARU INSERT
            $no_layanan = $this->Model->generate_no_layanan();

            $data = [
                'layID'        => $no_layanan,
                'layTanggal'   => $tanggal,
                'layUserID'    => $this->input->post('layUserID'),
                'layHal'       => $keluhan,
                'layDiskripsi' => $deskripsi,
                'layTarget'    => $target,
                'layStatus'    => 'N',
            ];

            $this->Model->insertData_layanan('tb_layanan', $data);

            redirect('tiket_sarpras');
        }

        public function tambah_tiket_sim()
        {
            $ringkasan = trim($this->input->post('tikRingkasan'));
            $deskripsi = trim($this->input->post('tikDeskripsi'));

            if (empty($ringkasan) && empty($deskripsi)) {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', 'Mohon isi keluhan anda terlebih dahulu');
                redirect('Controller/tambah_tiket_sim');
                return;
            }

            // baru generate & insert
            $no_tiket = $this->Model->generate_no_tiket();

            $data = [
                'tikNomor'      => $no_tiket,
                'tikTanggal'    => $this->input->post('tikTanggal'),
                'tikUserID'     => $this->input->post('tikUserID'),
                'tikRingkasan'  => $ringkasan,
                'tikStatus'     => 'N',
                'tikDeskripsi'  => $deskripsi
            ];

            $this->Model->insertData_sim('tb_tiket', $data);

            redirect('tiket_sim');
        }
    }
