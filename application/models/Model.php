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
                    if ($row['privMenuId'] == 24 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('role', 'admin');
                    } elseif ($row['privMenuId'] == 1 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('role', 'operator');
                    } elseif ($row['privMenuId'] == 25 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('role', 'sarpras');
                    }
                }

                $this->session->set_flashdata('type', 'alert-success');
                $this->session->set_flashdata('pesan', '<strong>Berhasil!</strong> Anda Berhasil Login');
                redirect('dashboard');


                // jika password salah
            } else {
                $this->session->set_flashdata('type', 'alert-danger');
                $this->session->set_flashdata('pesan', '<strong>Gagal!</strong> ID atau Password salah');
                redirect();
            }
        } else { // jika user tidak ditemukan

            $this->session->set_flashdata('type', 'alert-danger');
            $this->session->set_flashdata('pesan', 'User tidak ditemukan');
            redirect();
        }
    }


    //contoh query biasa
    public function contoh1()
    {
        $query = "SELECT id_buku, nama_buku, email_penulis, penerbit, tanggal FROM buku";
        return $this->db->query($query);
    }

    //contoh query dengan input dari AJAX
    public function contoh2($inputAjax)
    {
        $query = "SELECT kolom FROM tabel WHERE kolom = '" . $inputAjax . "'";
        return $this->db->query($query);
    }

    public function insertData($tb_tiket, $data)
    {
        return $this->db->insert($tb_tiket, $data);
    }

    public function delete_form_coba($no)
    {
        $this->db->where('no_buku', $no);
        $this->db->delete('buku');
    }

    public function tiket_sim()
    {
        $query = "SELECT tikNomor, tikTanggal, tikTanggal2, tikUserID, tikMesinID, tikRingkasan, tikDeskripsi, tikOperator, tikTindakan, tikStatus, tikUserClose, tikDateClose FROM tb_tiket";
        return $this->db->query($query);
    }

    public function tiket_sarpras()
    {
        $query = "SELECT tikNomor, tikTanggal, tikTanggal2, tikUserID, tikMesinID, tikRingkasan, tikDeskripsi, tikOperator, tikTindakan, tikStatus, tikUserClose, tikDateClose FROM tb_tiket";
        return $this->db->query($query);
    }

    public function get_dashboard_data()
    {
        $pelanggan = $this->db->count_all('tb_pelanggan');
        $wishlist  = $this->db->count_all('tb_wishlist');

        return [
            'pelanggan_baru' => $pelanggan,
            'wishlist_pelanggan' => $wishlist
        ];
    } // blm dipakai



    public function getTiket($role, $userID = null)
    {
        $this->db->select("
            tikNomor,
            tikTanggal,
            tikUserID,
            userNama,
            tikRingkasan,
            (CASE 
                WHEN tikStatus='Y' THEN 'CLOSE' 
                ELSE 'OPEN' 
            END) AS tiketStatus
        ");

        $this->db->from('tb_tiket');
        $this->db->join('vuser', 'tikUserID = userID', 'left');

        if ($role == 'operator') {

            // OPERATOR → tanpa where
            return $this->db->get();
        } else {

            // USER → pakai where tikUserID
            $this->db->where('tikUserID', $userID);
            return $this->db->get();
        }
    }
}
