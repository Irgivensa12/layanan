<?php

class model extends CI_Model
{
    // contoh login
    public function login()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $query = "SELECT a.*, p.privMenuId, p.privAkses
        FROM vUser a
        LEFT JOIN tb_user_priv p ON p.privUserId = a.userId
        WHERE a.userId = '$id'";

        $user = $this->db->query($query)->result_array();

        if ($user) {
            // ambil baris pertama untuk cek password
            $firstRow = $user[0];

            if (md5($password) == $firstRow['userPass']) {
                $this->session->set_userdata('helpdesk_login', true);
                $this->session->set_userdata('helpdesk_userId', $firstRow['userId']);
                $this->session->set_userdata('helpdesk_userNama', $firstRow['userNama']);
                foreach ($user as $row) {

                    // MENU ADMIN
                    if ($row['privMenuId'] == 24 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('admin', 'Y');
                    }

                    // MENU TIKET SIM
                    if ($row['privMenuId'] == 1 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('sim', 'Y');
                    }

                    // MENU SARPRAS
                    if ($row['privMenuId'] == 25 && $row['privAkses'] == 'Y') {
                        $this->session->set_userdata('sarpras', 'Y');
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


    public function insertData_sim($tb_tiket, $data)
    {
        return $this->db->insert($tb_tiket, $data);
    }

    public function insertData_layanan($tb_layanan, $data)
    {
        return $this->db->insert($tb_layanan, $data);
    }

    public function generate_no_tiket() // Untuk memuat nomor tiket baru dengan format HD-001, HD-002, dst (auto increment)
    {
        $query = $this->db->query("
        SELECT CONCAT('HD-', COUNT(*) + 1) AS no_tiket
        FROM tb_tiket
    ");

        return $query->row()->no_tiket;
    }

    public function generate_no_layanan()
    {
        $query = $this->db->query("
        SELECT CONCAT('26-', LPAD(COUNT(*) + 1, 3, '0')) AS no_layanan
        FROM tb_layanan
    ");

        return $query->row()->no_layanan;
    }

    public function delete_form_coba($no)
    {
        $this->db->where('no_buku', $no);
        $this->db->delete('buku');
    }

    public function tiket_sim()
    {
        $admin = $this->session->userdata('admin');
        $user  = $this->session->userdata('helpdesk_userId');

        if ($admin == 'Y') {
            // Mengambil semua kolom dari tabel tiket dan nama user dari tabel vUser
            // dan LEFT JOIN untuk menggabungkan tabel tiket dan vUser berdasarkan tikUserID dan userID
            $query = $this->db->query("
            SELECT 
                t.*, 
                u.userNama
            FROM tb_tiket t
            LEFT JOIN vUser u 
                ON u.userId = t.tikUserID

            WHERE t.tikNomor IS NOT NULL 
            AND t.tikNomor != ''

            ORDER BY CAST(SUBSTRING(t.tikNomor, 4) AS UNSIGNED) ASC
        ");
        } else {

            $query = $this->db->query("
            SELECT 
                t.*,
                u.userNama
            FROM tb_tiket t
            LEFT JOIN vUser u 
                ON u.userId = t.tikUserID

            WHERE t.tikUserID='$user'
            AND t.tikNomor IS NOT NULL 
            AND t.tikNomor != ''

            ORDER BY CAST(SUBSTRING(t.tikNomor, 4) AS UNSIGNED) ASC
        ");
        }

        return $query;
    }

    public function tiket_sarpras()
    {
        $admin = $this->session->userdata('admin');
        $user  = $this->session->userdata('helpdesk_userId');

        if ($admin == 'Y') {

            // Admin bisa lihat semua tiket + nama user
            $query = $this->db->query("
            SELECT l.*, u.userNama
            FROM tb_layanan l
            LEFT JOIN vUser u ON u.userId = l.layUserID
            ORDER BY l.layStatus DESC
        ");
        } else {

            // User hanya lihat tiket sendiri
            $query = $this->db->query("
            SELECT l.*, u.userNama
            FROM tb_layanan l
            LEFT JOIN vUser u ON u.userId = l.layUserID
            WHERE l.layUserID = '$user'
            ORDER BY l.layTanggal DESC
        ");
        }

        return $query;
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
    public function admin_sim()
    {
        $query = "SELECT tikNomor, tikTanggal, tikTanggal2, tikUserID, tikMesinID, tikRingkasan, tikDeskripsi, tikOperator, tikTindakan, tikStatus, tikUserClose, tikDateClose FROM tb_tiket";
        return $this->db->query($query);
    }
    public function admin_sarpras()
    {
        $query = "SELECT layID, layTanggal, layUserID, pegNama, layHal, layDiskripsi, layTarget, layPetugas1, layPetugas2, layPetugas3, layPetugas4, layPetugas5, layStatus, layCreateUserID, layCloseUserID, layCloseTanggal, FROM tb_layanan";
        return $this->db->query($query);
    }
    public function update_tiket($tikNomor, $data)
    {
        $this->db->where('tikNomor', $tikNomor);
        $this->db->update('tb_tiket', $data);
    }
    public function update_tiket_lay($layID, $data)
    {
        $this->db->where('layID', $layID);
        $this->db->update('tb_layanan', $data);
    }
}
