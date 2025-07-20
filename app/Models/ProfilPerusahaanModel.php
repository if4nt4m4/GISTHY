<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPerusahaanModel extends Model
{
    protected $table = 'tbl_Profil_perusahaan';
    protected $primaryKey = 'id_profil_perusahaan';
    protected $allowedFields = ['id_user', 'gambar_about', 'nama_perusahaan', 'deskripsi', 'logo'];

    public function getUser($id_user)
    {
        return $this->db->table('users')->where('id_user', $id_user)->get()->getRowArray();
    }
}