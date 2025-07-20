<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatHargaModel extends Model
{
    protected $table = 'tbl_Riwayat_harga';
    protected $primaryKey = 'id_riwayat_harga';
    protected $allowedFields = ['id_tanah', 'waktu', 'harga'];

    public function getRiwayatByTanah($id_tanah)
    {
        return $this->where('id_tanah', $id_tanah)
            ->groupStart()
                ->where('waktu !=', '0000-00-00')
                ->orWhere('harga !=', 0.00)
            ->groupEnd()
            ->orderBy('waktu', 'DESC')
            ->findAll();
    }
}
