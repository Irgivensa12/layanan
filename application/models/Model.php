<?php

class model extends CI_Model
{
    // contoh login
    // public function login()
    // {
    //     $id = $this->input->post('id');
    //     $password = $this->input->post('password');

    //     $query = "
    // SELECT 
    //     a.*,

    //     IFNULL(MAX(CASE WHEN p.privMenuId='1' 
    //     THEN p.privAkses END),'N') AS sim,

    //     IFNULL(MAX(CASE WHEN p.privMenuId='25' 
    //     THEN p.privAkses END),'N') AS sarpras,

    //     IFNULL(MAX(CASE WHEN p.privMenuId='24' 
    //     THEN p.privAkses END),'N') AS admin

    // FROM vUser a
    // LEFT JOIN tb_user_priv p 
    //     ON p.privUserId = a.userId

    // WHERE a.userId = '$id'
    // GROUP BY a.userId
    // ";

    //     $user = $this->db->query($query)->row_array();

    //     if ($user) {

    //         if (md5($password) == $user['userPass']) {

    //             $this->session->set_userdata('helpdesk_login', true);
    //             $this->session->set_userdata('helpdesk_userId', $user['userId']);
    //             $this->session->set_userdata('helpdesk_userNama', $user['userNama']);

    //             // ROLE
    //             $this->session->set_userdata('admin', $user['admin']);
    //             $this->session->set_userdata('sim', $user['sim']);
    //             $this->session->set_userdata('sarpras', $user['sarpras']);

    //             redirect('dashboard');
    //         } else {

    //             $this->session->set_flashdata('type', 'alert-danger');
    //             $this->session->set_flashdata(
    //                 'pesan',
    //                 '<strong>Gagal!</strong> Password salah'
    //             );

    //             redirect();
    //         }
    //     } else {

    //         $this->session->set_flashdata('type', 'alert-danger');
    //         $this->session->set_flashdata(
    //             'pesan',
    //             'User tidak ditemukan'
    //         );

    //         redirect();
    //     }
    // }

    public function login()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $query = "
    SELECT 
        a.userId,
        a.userNama,
        a.userPass,

        IFNULL(
            MAX(
                CASE
                    WHEN p.privMenuId='1'
                    THEN p.privAkses
                END
            ),
        'N') AS sim,

        IFNULL(
            MAX(
                CASE
                    WHEN p.privMenuId='25'
                    THEN p.privAkses
                END
            ),
        'N') AS sarpras,

        IFNULL(
            MAX(
                CASE
                    WHEN p.privMenuId='24'
                    THEN p.privAkses
                END
            ),
        'N') AS admin

    FROM vUser a

    LEFT JOIN tb_user_priv p
        ON p.privUserId = a.userId

    WHERE a.userId = '$id'

    GROUP BY
        a.userId,
        a.userNama,
        a.userPass
    ";

        $user = $this->db->query($query)->row_array();

        if ($user) {

            if (md5($password) == $user['userPass']) {

                $this->session->set_userdata('helpdesk_login', true);

                $this->session->set_userdata(
                    'helpdesk_userId',
                    $user['userId']
                );

                $this->session->set_userdata(
                    'helpdesk_userNama',
                    $user['userNama']
                );

                // ROLE
                $this->session->set_userdata(
                    'admin',
                    $user['admin']
                );

                $this->session->set_userdata(
                    'sim',
                    $user['sim']
                );

                $this->session->set_userdata(
                    'sarpras',
                    $user['sarpras']
                );

                redirect('dashboard');
            } else {

                $this->session->set_flashdata(
                    'type',
                    'alert-danger'
                );

                $this->session->set_flashdata(
                    'pesan',
                    '<strong>Gagal!</strong> Password salah'
                );

                redirect();
            }
        } else {

            $this->session->set_flashdata(
                'type',
                'alert-danger'
            );

            $this->session->set_flashdata(
                'pesan',
                'User tidak ditemukan'
            );

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
        $sim   = $this->session->userdata('sim');
        $user  = $this->session->userdata('helpdesk_userId');

        // PENTING
        $as_user_biasa = $this->session->userdata('as_user_biasa');

        $status = $this->input->get('status');

        $filterStatus = "";

        if ($status == 'open') {

            $filterStatus = " AND t.tikStatus='N' ";
        } elseif ($status == 'close') {

            $filterStatus = " AND t.tikStatus='Y' ";
        }

        // ADMIN ASLI
        if (($admin == 'Y' || $sim == 'Y') && !$as_user_biasa) {

            $query = $this->db->query("
            SELECT 
                t.*, 
                u.userNama
            FROM tb_tiket t
            LEFT JOIN vUser u 
                ON u.userId = t.tikUserID

            WHERE t.tikNomor IS NOT NULL 
            AND t.tikNomor != ''
            $filterStatus

            ORDER BY CAST(SUBSTRING(t.tikNomor, 4) AS UNSIGNED) ASC
        ");
        } else {

            // USER BIASA / ADMIN MODE USER

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
            $filterStatus

            ORDER BY CAST(SUBSTRING(t.tikNomor, 4) AS UNSIGNED) ASC
        ");
        }

        return $query;
    }
    public function tiket_sarpras()
    {
        $admin = $this->session->userdata('admin');
        $user  = $this->session->userdata('helpdesk_userId');

        $status = $this->input->get('status');

        $filterStatus = "";

        if ($status == 'open') {

            $filterStatus = " AND l.layStatus='N' ";
        } elseif ($status == 'close') {

            $filterStatus = " AND l.layStatus='Y' ";
        }

        // ADMIN HAK AKSES ATAU ADMIN SARPRAS ASLI
        if (($admin == 'Y' || $sarpras == 'Y' || $sarpras == 'Y') && !$as_user_biasa) {

            $query = $this->db->query("
            SELECT l.*, u.userNama
            FROM tb_layanan l
            LEFT JOIN vUser u 
                ON u.userId = l.layUserID

            WHERE 1=1
            $filterStatus

            ORDER BY l.layTanggal DESC
        ");
        } else {

            $query = $this->db->query("
            SELECT l.*, u.userNama
            FROM tb_layanan l
            LEFT JOIN vUser u 
                ON u.userId = l.layUserID

            WHERE l.layUserID = '$user'
            $filterStatus

            ORDER BY l.layTanggal DESC
        ");
        }

        return $query;
    }
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

    public function get_user_hak_akses()
    {
        $query = $this->db->query("
        SELECT
            userId,
            userNama,

            MAX(
                CASE
                    WHEN privMenuId='1'
                    THEN privAkses
                END
            ) AS Tiket,

            MAX(
                CASE
                    WHEN privMenuId='25'
                    THEN privAkses
                END
            ) AS Sarpras,

            MAX(
                CASE
                    WHEN privMenuId='24'
                    THEN privAkses
                END
            ) AS Admin

        FROM tb_user_priv

        LEFT JOIN ref_menu
            ON privMenuId = menuID

        LEFT JOIN vUser
            ON privUserId = userID

        WHERE menuJenis='Tiket'

        GROUP BY userId, userNama

        ORDER BY userNama ASC
    ");

        return $query;
    }

    public function update_hak_akses(
        $userId,
        $menuId,
        $akses
    ) {

        $cek = $this->db
            ->where('privUserId', $userId)
            ->where('privMenuId', $menuId)
            ->get('tb_user_priv');

        if ($cek->num_rows() > 0) {

            $this->db->where('privUserId', $userId);
            $this->db->where('privMenuId', $menuId);

            return $this->db->update(
                'tb_user_priv',
                [
                    'privAkses' => $akses
                ]
            );
        } else {

            return $this->db->insert(
                'tb_user_priv',
                [
                    'privUserId' => $userId,
                    'privMenuId' => $menuId,
                    'privAkses' => $akses
                ]
            );
        }
    }

    public function insert_hak_akses($data)
    {
        return $this->db->insert(
            'tb_user_priv',
            $data
        );
    }
    // public function getTiketSIMByUser($userId)
    // {
    //     return $this->db
    //         ->where('tikUserID', $userId)
    //         ->get('tb_tiket_sim')
    //         ->result();
    // }
}
