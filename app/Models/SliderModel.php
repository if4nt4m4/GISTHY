<?php

namespace App\Models;

use CodeIgniter\Model;

class SliderModel extends Model
{
    protected $table = 'tbl_slider';
    protected $primaryKey = 'id_slider';
    protected $allowedFields = ['gambar'];
}