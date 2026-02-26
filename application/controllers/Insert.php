<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Controller {
    
    Public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model("Model");
    }
    
    public function contoh2(){
        $inputAjax = $this->input->post('inputAjax');
		
        $this->Model->contoh2($inputAjax);
        redirect('menu');
    }

    public function input(){

        $data = array(
            'nama_buku'      => $this->input->post('nama_buku'),
            'penulis'           => $this->input->post('penulis'),
            'penerbit'        => $this->input->post('penerbit'),
            'tanggal'           => $this->input->post('tanggal')
        );

        $this->Model->insertData('buku', $data);

        redirect('menu');
    }

    public function update()
    {
        $id = $this->input->post('no_buku');

        $data = array(
            'nama_buku'      => $this->input->post('nama_buku'),
            'jenis_buku'     => $this->input->post('jenis_buku'),
            'tahun_terbit'   => $this->input->post('tahun_terbit'),
            'penulis'        => $this->input->post('penulis'),
            'tanggal_pinjam' => $this->input->post('tanggal_pinjam')
        );

        $this->db->where('no_buku', $id);
        $this->db->update('buku', $data);

        redirect('menu');
    }

    
}