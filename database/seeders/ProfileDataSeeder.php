<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\OrganizationStructure;
use App\Models\Banom;

class ProfileDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Settings
        $settings = [
            [
                'key' => 'profile_hero_title',
                'value' => 'Khidmah NU Ranting Baktijaya Untuk Masyarakat',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Judul Hero Profil',
            ],
            [
                'key' => 'profile_description',
                'value' => 'Pengurus Ranting Nahdlatul Ulama (PRNU) Baktijaya adalah ujung tombak organisasi dalam melayani jamaah, menjaga amaliyah Ahlussunnah wal Jamaah, dan memberdayakan ekonomi umat di lingkungan Baktijaya.',
                'type' => 'textarea',
                'group' => 'profile',
                'label' => 'Deskripsi Profil',
            ],
            [
                'key' => 'profile_image',
                'value' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAcHOgGOrTTa4ki4MwQX8OO_PB_2zj1jDvj2NXLCEZRdeiSDvjSQHuyetUr_GP-oPVzxzR7gsny_UuYW1Rv-ge22s3r45n8lzh8tDSXgQ_WgK1JnMiKHMj_YWNDZ8-v3NC31LKapRwt5z2cqJzSojYKcJAzlrUpiRQPtZplYo06cEzUdSwdwlNDMGppYOnDykQH66Hh64LSpn6Dp_Wrx972duo5qzvEtAGBHtXBpcTHpJspzq9_7KegTievfj_MeQ9iJF1aVXKjBPbQ',
                'type' => 'image',
                'group' => 'profile',
                'label' => 'Gambar Profil',
            ],
            [
                'key' => 'stats_legalitas',
                'value' => 'Terakreditasi PCNU',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Status Legalitas',
            ],
            [
                'key' => 'stats_basis_massa',
                'value' => '2.500+ Jamaah',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Basis Massa',
            ],
            [
                'key' => 'stats_tahun_khidmat',
                'value' => '15+',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Tahun Berkhidmat',
            ],
            [
                'key' => 'visi',
                'value' => '"Terwujudnya kemandirian umat yang religius, sejahtera, dan moderat berlandaskan nilai-nilai Aswaja An-Nahdliyah menuju Baldatun Tayyibatun Warabbun Ghafur."',
                'type' => 'textarea',
                'group' => 'profile',
                'label' => 'Visi Organisasi',
            ],
            [
                'key' => 'misi_1',
                'value' => 'Penguatan Akidah: Menjaga dan melestarikan tradisi serta amaliyah Ahlussunnah wal Jamaah di tengah masyarakat.',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Misi 1',
            ],
            [
                'key' => 'misi_2',
                'value' => 'Pemberdayaan Ekonomi: Membangun kemandirian ekonomi jamaah melalui program UMKM dan optimalisasi ZISWAF.',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Misi 2',
            ],
            [
                'key' => 'misi_3',
                'value' => 'Layanan Sosial: Menyelenggarakan layanan sosial dan pendidikan yang berkualitas bagi seluruh lapisan masyarakat.',
                'type' => 'text',
                'group' => 'profile',
                'label' => 'Misi 3',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // 2. Organization Structure
        // Syuriyah
        OrganizationStructure::firstOrCreate(
            ['name' => 'KH. Ahmad Syafii'],
            [
                'position' => 'Rais',
                'type' => 'syuriyah',
                'description' => 'Bertanggung jawab dalam memberikan arahan kebijakan keagamaan dan hukum syariat.',
                'order' => 1
            ]
        );
        OrganizationStructure::firstOrCreate(
            ['name' => 'Ust. Mansyur Ali'],
            [
                'position' => 'Katib',
                'type' => 'syuriyah',
                'description' => 'Mengelola administrasi kebijakan dan korespondensi dewan pertimbangan.',
                'order' => 2
            ]
        );
        OrganizationStructure::firstOrCreate(
            ['name' => 'H. Badruzaman'],
            [
                'position' => 'A\'wan',
                'type' => 'syuriyah',
                'description' => 'Memberikan masukan dan pendampingan teknis kepada jajaran pengurus.',
                'order' => 3
            ]
        );

        // Tanfidziyah
        OrganizationStructure::firstOrCreate(
            ['name' => 'Ust. H. Fauzan, S.Pd.I'],
            [
                'position' => 'Ketua Tanfidziyah',
                'type' => 'tanfidziyah',
                'order' => 1
            ]
        );
        OrganizationStructure::firstOrCreate(
            ['name' => 'Bpk. Ahmad Fauzi'],
            [
                'position' => 'Sekretaris',
                'type' => 'tanfidziyah',
                'order' => 2
            ]
        );
        OrganizationStructure::firstOrCreate(
            ['name' => 'H. Muhammad Ridwan'],
            [
                'position' => 'Bendahara',
                'type' => 'tanfidziyah',
                'order' => 3
            ]
        );
        OrganizationStructure::firstOrCreate(
            ['name' => 'Ust. Abdul Hakim'],
            [
                'position' => 'Ketua Lazisnu',
                'type' => 'tanfidziyah',
                'order' => 4
            ]
        );

        // 3. Banom
        $banoms = [
            ['name' => 'Muslimat NU', 'icon' => 'family_restroom', 'order' => 1],
            ['name' => 'Fatayat NU', 'icon' => 'woman_2', 'order' => 2],
            ['name' => 'GP Ansor', 'icon' => 'shield', 'order' => 3],
            ['name' => 'IPNU', 'icon' => 'school', 'order' => 4],
            ['name' => 'IPPNU', 'icon' => 'book_2', 'order' => 5],
            ['name' => 'LAZISNU', 'icon' => 'volunteer_activism', 'order' => 6],
        ];

        foreach ($banoms as $banom) {
            Banom::firstOrCreate(
                ['name' => $banom['name']],
                $banom
            );
        }
    }
}
