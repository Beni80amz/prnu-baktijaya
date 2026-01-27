<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Region;
use App\Models\IncomeType;
use App\Models\ExpenseType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            ['name' => 'Berita', 'type' => 'news', 'description' => 'Berita seputar PRNU Baktijaya'],
            ['name' => 'Kegiatan', 'type' => 'news', 'description' => 'Info kegiatan jamaah'],
            ['name' => 'Pengumuman', 'type' => 'news', 'description' => 'Pengumuman resmi organisasi'],
            ['name' => 'Opini', 'type' => 'article', 'description' => 'Opini dan pemikiran tokoh'],
            ['name' => 'Kultum', 'type' => 'article', 'description' => 'Kuliah tujuh menit / Siraman rohani'],
            ['name' => 'Sejarah', 'type' => 'article', 'description' => 'Sejarah NU dan tokoh-tokohnya'],
            ['name' => 'Dokumentasi', 'type' => 'gallery', 'description' => 'Galeri foto kegiatan'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'type' => $cat['type'],
                    'description' => $cat['description'],
                    'is_active' => true
                ]
            );
        }

        // 2. Regions (RW 01 - RW 22)
        for ($i = 1; $i <= 22; $i++) {
            $rw = str_pad($i, 2, '0', STR_PAD_LEFT);
            Region::updateOrCreate(
                ['code' => 'RW' . $rw],
                ['name' => 'RW ' . $rw]
            );
        }

        // 3. Income Types
        $incomeTypes = [
            ['name' => 'Zakat Fitrah', 'code' => 'Z-FITRAH'],
            ['name' => 'Zakat Maal', 'code' => 'Z-MAAL'],
            ['name' => 'Infaq/Shadaqah', 'code' => 'INFAQ'],
            ['name' => 'Donasi Sosial', 'code' => 'DONASI'],
            ['name' => 'Hibah', 'code' => 'HIBAH'],
            ['name' => 'Iuran Anggota', 'code' => 'IURAN'],
        ];

        foreach ($incomeTypes as $type) {
            IncomeType::updateOrCreate(
                ['code' => $type['code']],
                ['name' => $type['name']]
            );
        }

        // 4. Expense Types
        $expenseTypes = [
            ['name' => 'Operasional Sekretariat', 'code' => 'OP-SEKRE'],
            ['name' => 'Bantuan Sosial', 'code' => 'SOSIAL'],
            ['name' => 'Kegiatan Keagamaan', 'code' => 'KEGIATAN'],
            ['name' => 'Pembangunan/Renovasi', 'code' => 'BANGUNAN'],
            ['name' => 'Biaya Umum/Lainnya', 'code' => 'UMUM'],
        ];

        foreach ($expenseTypes as $type) {
            ExpenseType::updateOrCreate(
                ['code' => $type['code']],
                ['name' => $type['name']]
            );
        }
    }
}
