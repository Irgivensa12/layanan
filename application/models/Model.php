<?php

class model extends CI_Model
{
    // contoh login
    public function login(){
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        
        $query = "SELECT a.*,privMenuId,privModif,privValidasi FROM vUser a 
            LEFT JOIN (SELECT * FROM ref_menu LEFT JOIN tb_user_priv ON privMenuID=MenuId WHERE menuJenis='Tiket') tbPriv  ON privUserId=userId WHERE `userId`='9605' AND userPass=MD5('9605');
            ";
        //$user = $this->db->query($query)->row_array();
        $user = $this->db->query($query)->result_array();
        
        // jika user ditemukan
        if ($user) {

            // ambil baris pertama untuk cek password
            $firstRow = $user[0];

            // if (md5($password) == $user['userPass']) {
            if (md5($password) == $firstRow['userPass']) {    
                // $_SESSION['login'] = true;
                // $_SESSION['id_layanan'] = $user['userId'];
                // $_SESSION['nama_layanan'] = $user['userNama'];
                // $_SESSION['privMenuId'] = $user['privMenuId'];
                // $_SESSION['privModif'] = $user['privModif'];
                // $_SESSION['privValidasi'] = $user['privValidasi'];
                
                foreach ($user as $row) // untuk menyimpan data user ke session
                {
                    
                    $this->session->set_userdata('login', true);
                    $this->session->set_userdata('userId', $firstRow['userId']);
                    $this->session->set_userdata('userNama', $firstRow['userNama']);

                    // Simpan privilege
                    if ($row['privMenuId'] == 1) {
                        $this->session->set_userdata('tiket', $row['privAkses']);
                    }

                    if ($row['privMenuId'] == 24) {
                        $this->session->set_userdata('admin', $row['privAkses']);
                    }

                    if ($row['privMenuId'] == 25) {
                        $this->session->set_userdata('sarpras', $row['privAkses']);
                    }
                }
            
				
                $this->session->set_flashdata('type', 'alert-success');
                $this->session->set_flashdata('pesan', '<strong>Sukses!</strong> Anda berhasil login.');
                redirect('menu');
        }
            // jika password salah
            else {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', '<strong>Gagal!</strong> ID atau Password ini salah');
                redirect();
            }
        }
        // jika user tidak ditemukan
        else {
            $this->session->set_flashdata('type', 'alert-danger');
            $this->session->set_flashdata('pesan', '<strong>Gagal!</strong> ID atau Password salah');
            redirect();
        }
    }
	
    //contoh query biasa
    public function contoh1(){
        $query = "SELECT tikNomor, tikTanggal, tikUserID, userNama, tikRingkasan, tiketStatus FROM tb_tiket";
        return $this->db->query($query);
    }
	
    //contoh query dengan input dari AJAX
    public function contoh2($inputAjax){
        $query = "SELECT kolom FROM tabel WHERE kolom = '".$inputAjax."'";
        return $this->db->query($query);
    }
}

