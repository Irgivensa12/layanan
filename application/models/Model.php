<?php

class model extends CI_Model
{
    // contoh login
    public function login(){
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        
        $query = "SELECT id, password FROM tabel WHERE id = '".$id."'";
        $user = $this->db->query($query)->row_array();
        
        // jika user ditemukan
        if ($user) {
            if (md5($password) == $user['password']){
                $_SESSION['login'] = true;
                $_SESSION['id_layanan'] = $user['userId'];
				
                $this->session->set_flashdata('type', 'alert-success');
                $this->session->set_flashdata('pesan', '<strong>Sukses!</strong> Anda berhasil login.');
                redirect('menu');
            }
            // jika password salah
            else {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', '<strong>Gagal!</strong> ID atau Password salah');
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
        $query = "SELECT kolom FROM tabel";
        return $this->db->query($query);
    }
	
    //contoh query dengan input dari AJAX
    public function contoh2($inputAjax){
        $query = "SELECT kolom FROM tabel WHERE kolom = '".$inputAjax."'";
        return $this->db->query($query);
    }
}

