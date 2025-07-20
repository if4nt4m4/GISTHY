<?php

namespace App\Models;

use CodeIgniter\Model;

class TitikKoordinatModel extends Model
{
    protected $table = 'tbl_Titik_koordinat';
    protected $primaryKey = 'id_titik_koordinat';
    protected $allowedFields = ['id_tanah', 'longitude', 'latitude'];

    public function getKoordinatByTanah($id_tanah)
    {
        return $this->where('id_tanah', $id_tanah)->findAll();
    }
}
