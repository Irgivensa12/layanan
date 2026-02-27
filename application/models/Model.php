<?php

class model extends CI_Model
{
    // contoh login
    public function login()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $query = "SELECT a.*, privMenuId, privAkses FROM vUser a LEFT JOIN tb_user_priv ON privUserId=userId WHERE userId='$id'";

        $user = $this->db->query($query)->result_array();

        if ($user) {
            // ambil baris pertama untuk cek password
            $firstRow = $user[0];

            if (md5($password) == $firstRow['userPass']) {
                $this->session->set_userdata('login', true);
                $this->session->set_userdata('userId', $firstRow['userId']);
                $this->session->set_userdata('userNama', $firstRow['userNama']);
                foreach ($user as $row) {

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
                $this->session->set_flashdata('pesan', '<strong>Berhasil!</strong> Anda Berhasil Login');
                redirect('menu');


                // jika password salah
            } else {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', '<strong>Gagal!</strong> ID atau Password salah');
                redirect();
            }
        }
    }


    //contoh query biasa
    public function contoh1()
    {
        $query = "SELECT tikNomor, tikTanggal, tikUserID, userNama, tikRingkasan, tiketStatus FROM tb_tiket";
        return $this->db->query($query);
    }



    //contoh query dengan input dari AJAX
    public function contoh2($inputAjax)
    {
        $query = "SELECT kolom FROM tabel WHERE kolom = '" . $inputAjax . "'";
        return $this->db->query($query);
    }

    public function insertData($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function delete_form_coba($no)
    {
        $this->db->where('no_buku', $no);
        $this->db->delete('buku');
    }
}
