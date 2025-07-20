<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaanModel extends Model
{
    protected $table = 'tbl_penggunaan';
    protected $primaryKey = 'id_penggunaan';
    protected $allowedFields = ['penggunaan'];
}