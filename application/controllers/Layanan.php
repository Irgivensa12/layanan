<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Layanan extends CI_Controller
{

    // public function index()
    // {
    //     $data['layanan'] = $this->db->query("
    //         SELECT layID, layTanggal, layUserID, pegNama, layHal, layDiskripsi, layTarget 
    //         FROM tb_layanan 
    //         LEFT JOIN db_kbs_hris.ref_pegawai 
    //         ON layUserID = pegNoreg
    //     ")->result();

    //     $this->load->view('layanan_view', $data);
    // }

    public function input()
    {
        // data pegawai untuk dropdown
        $data['pegawai'] = $this->db->query("
        SELECT userID, pegNama 
        FROM ref_user 
        LEFT JOIN db_kbs_hris.ref_pegawai 
        ON userID = pegNoreg
    ")->result();

        $this->load->view('layanan_input', $data);
    }
}
