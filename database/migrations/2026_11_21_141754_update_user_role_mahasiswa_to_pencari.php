<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Perluas atau ubah enum role pada tabel users agar mendukung 'pencari'
        // Pada MySQL, ubah kolom enum role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pencari', 'pemilik', 'admin', 'mahasiswa') DEFAULT 'pencari'");

        // 2. Update data lama yang memiliki role 'mahasiswa' menjadi 'pencari'
        DB::table('users')->where('role', 'mahasiswa')->update(['role' => 'pencari']);

        // 3. Finalisasi enum hanya menjadi ('pencari', 'pemilik', 'admin')
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pencari', 'pemilik', 'admin') DEFAULT 'pencari'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'pemilik', 'admin', 'pencari') DEFAULT 'mahasiswa'");
        DB::table('users')->where('role', 'pencari')->update(['role' => 'mahasiswa']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'pemilik', 'admin') DEFAULT 'mahasiswa'");
    }
};
