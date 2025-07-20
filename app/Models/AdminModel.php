<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'tbl_Users';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['nama', 'username', 'password', 'status', 'role', 'gambar'];
    protected $useSoftDeletes = true;

    // Hash password sebelum insert
    protected $beforeInsert = ['hashPasswordBeforeInsert'];
    protected $beforeUpdate = ['hashPasswordBeforeUpdate'];

    /**
     * Hash password sebelum menyimpan data baru.
     */
    protected function hashPasswordBeforeInsert(array $data)
    {
        if (isset($data['data']['password']) && !password_get_info($data['data']['password'])['algo']) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    /**
     * Hash password hanya jika diperbarui dan tidak kosong.
     */
    protected function hashPasswordBeforeUpdate(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password']) && !password_get_info($data['data']['password'])['algo']) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }else {
            unset($data['data']['password']); // Jangan mengubah password jika kosong
        }
        return $data;
    }

    /**
     * Verifikasi password saat login.
     */
    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    /**
     * Mendapatkan nilai ENUM dari kolom tertentu dengan validasi kolom.
     */
    public function getEnumValues($column)
    {
        $db = \Config\Database::connect();

        // **1️ Validasi apakah kolom tersebut ada dalam tabel**
        $fields = $db->getFieldNames($this->table);
        if (!in_array($column, $fields)) {
            return []; // Jika kolom tidak ditemukan, kembalikan array kosong
        }

        // **2️ Query menggunakan parameterized query agar lebih aman**
        $query = $db->query("SHOW COLUMNS FROM " . $db->escapeString($this->table) . " WHERE Field = ?", [$column]);
        $result = $query->getRow();

        if (!$result) {
            return [];
        }

        // **3️ Parsing ENUM values**
        if (preg_match("/^enum\((.*)\)$/", $result->Type, $matches)) {
            return explode(",", str_replace("'", "", $matches[1]));
        }

        return [];
    }

    public function countUsers()
    {
        return $this->db->table('tbl_Users')->countAllResults();
    }
}
