<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DatabaseTa extends Migration
{
    public function up()
    {
        // Tabel Users
        $this->forge->addField([
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 60],
            'username' => ['type' => 'VARCHAR', 'constraint' => 30],
            'password' => ['type' => 'VARCHAR', 'constraint' => 60],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive']],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'user']],
            'gambar' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addPrimaryKey('id_user');
        $this->forge->createTable('tbl_Users');

        // Tabel Tanah
        $this->forge->addField([
            'id_tanah' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'kawasan' => ['type' => 'VARCHAR', 'constraint' => 20],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 30],
            'wilayah' => ['type' => 'VARCHAR', 'constraint' => 20],
            'kelurahan' => ['type' => 'VARCHAR', 'constraint' => 30],
            'harga_terkini' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'waktu_terkini' => ['type' => 'DATE'],
            'indikasi' => ['type' => 'ENUM', 'constraint' => ['nilai pasar tanah', 'nilai tanah data pembanding', 'permeter tanah data pembanding']],
            'titik_latitude' => ['type' => 'DOUBLE'],
            'titik_longitude' => ['type' => 'DOUBLE'],
            'luas_tanah' => ['type' => 'INT', 'constraint' =>5, 'unsigned' => true],
            'luas_bangunan' => ['type' => 'INT', 'constraint' =>5, 'unsigned' => true],
            'no_telp' => ['type' => 'VARCHAR', 'constraint' => 13],
            'nama_pemilik' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addPrimaryKey('id_tanah');
        $this->forge->addForeignKey('id_user', 'tbl_Users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_Tanah');

        // Tabel Riwayat Harga
        $this->forge->addField([
            'id_riwayat_harga' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'id_tanah' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'waktu' => ['type' => 'DATE'],
            'harga' => ['type' => 'DECIMAL', 'constraint' => '15,2'],
        ]);
        $this->forge->addPrimaryKey('id_riwayat_harga');
        $this->forge->addForeignKey('id_tanah', 'tbl_Tanah', 'id_tanah', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_Riwayat_harga');

        // Tabel Titik Koordinat
        $this->forge->addField([
            'id_titik_koordinat' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'id_tanah' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'longitude' => ['type' => 'DOUBLE'],
            'latitude' => ['type' => 'DOUBLE'],
        ]);
        $this->forge->addPrimaryKey('id_titik_koordinat');
        $this->forge->addForeignKey('id_tanah', 'tbl_Tanah', 'id_tanah', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_Titik_koordinat');

        // Tabel Profil Perusahaan
        $this->forge->addField([
            'id_profil_perusahaan' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'gambar_about' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nama_perusahaan' => ['type' => 'VARCHAR', 'constraint' => 100],
            'deskripsi' => ['type' => 'TEXT'],
            'logo' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addPrimaryKey('id_profil_perusahaan');
        $this->forge->addForeignKey('id_user', 'tbl_Users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_Profil_perusahaan');

        // Tabel Slider
        $this->forge->addField([
            'id_slider' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'gambar' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addPrimaryKey('id_slider');
        $this->forge->createTable('tbl_Slider');

        $this->forge->addField([
            'id_penggunaan' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'penggunaan' => ['type' => 'VARCHAR', 'constraint' => 45],
        ]);
        $this->forge->addPrimaryKey('id_penggunaan');
        $this->forge->createTable('tbl_penggunaan');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_Slider');
        $this->forge->dropTable('tbl_Profil_perusahaan');
        $this->forge->dropTable('tbl_Titik_koordinat');
        $this->forge->dropTable('tbl_Riwayat_harga');
        $this->forge->dropTable('tbl_Tanah');
        $this->forge->dropTable('tbl_Users');
        $this->forge->dropTable('tbl_penggunaan');
    }
}
