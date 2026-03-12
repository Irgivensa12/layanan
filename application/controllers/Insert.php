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

    public function update()
    {
        $id = $this->input->post('tikNomor');

        $data = array(
            'tikNomor'      => $this->input->post('tikNomor'),
            'tikTanggal'           => $this->input->post('tikTanggal'),
            'tikUserClose'           => $this->input->post('tikUserClose'),
            'tikRingkasan'        => $this->input->post('tikRingkasan'),
            'tiketStatus'           => $this->input->post('tiketStatus')
        );

        $this->db->where('tik_Nomor', $id);
        $this->db->update('tb_tiket', $data);

        redirect('menu');
    }

    public function tambah_tiket_sarpras()
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

        $this->Model->insertData('tb_tiket', $data);
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

        $this->Model->insertData('tb_tiket', $data);

        redirect('tiket_sim');
    }
}
