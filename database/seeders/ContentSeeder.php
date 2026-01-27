<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\Dawuh;
use App\Models\News;
use App\Models\Article;
use App\Models\Category;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        if (!$admin)
            return;

        // 1. Sliders
        $sliders = [
            [
                'title' => 'Selamat Datang di Portal Resmi PRNU Baktijaya',
                'description' => 'Membangun umat, menjaga tradisi, dan memperkuat ukhuwah Nahdliyah di lingkungan Baktijaya.',
                'image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=2070&auto=format&fit=crop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Khidmah NU untuk Bangsa',
                'description' => 'Meneladani semangat para Kiai dalam menjaga NKRI dan menyebarkan Islam Rahmatan Lil Alamin.',
                'image' => 'https://images.unsplash.com/photo-1590073844006-369302634356?q=80&w=2070&auto=format&fit=crop',
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::updateOrCreate(
                ['title' => $slider['title']],
                $slider
            );
        }

        // 2. Dawuhs
        $dawuhs = [
            [
                'ulama_name' => 'KH. Hasyim Asy\'ari',
                'quote' => 'Siapa yang mau mengurusi NU, saya anggap santriku. Siapa yang menjadi santriku, saya doakan husnul khatimah beserta anak cucunya.',
                'is_active' => true,
            ],
            [
                'ulama_name' => 'KH. Abdurrahman Wahid (Gus Dur)',
                'quote' => 'Sabar itu tidak ada batasnya, kalau ada batasnya berarti bukan sabar.',
                'is_active' => true,
            ],
        ];

        foreach ($dawuhs as $dawuh) {
            Dawuh::updateOrCreate(
                ['quote' => $dawuh['quote']],
                $dawuh
            );
        }

        // 3. News
        $newsCategories = Category::where('type', 'news')->get();
        foreach ($newsCategories as $cat) {
            if ($cat->name == 'Berita') {
                News::updateOrCreate(
                    ['slug' => Str::slug('Lailatul Ijtima Rutin PRNU Baktijaya')],
                    [
                        'title' => 'Lailatul Ijtima Rutin PRNU Baktijaya',
                        'content' => '<p>PRNU Baktijaya kembali mengadakan kegiatan rutin Lailatul Ijtima yang dihadiri oleh ratusan jamaah dari berbagai RW di lingkungan Baktijaya. Kegiatan ini diisi dengan pembacaan Istighosah dan kajian kitab kuning.</p>',
                        'category_id' => $cat->id,
                        'user_id' => $admin->id,
                        'status' => 'published',
                        'published_at' => now(),
                    ]
                );
            } elseif ($cat->name == 'Kegiatan') {
                News::updateOrCreate(
                    ['slug' => Str::slug('Penyaluran Bantuan Sembako Lazisnu Baktijaya')],
                    [
                        'title' => 'Penyaluran Bantuan Sembako Lazisnu Baktijaya',
                        'content' => '<p>Lazisnu Baktijaya menyalurkan paket sembako kepada dhuafa di lingkungan RW 04. Program ini merupakan bagian dari komitmen PRNU untuk terus hadir melayani masyarakat.</p>',
                        'category_id' => $cat->id,
                        'user_id' => $admin->id,
                        'status' => 'published',
                        'published_at' => now(),
                    ]
                );
            } elseif ($cat->name == 'Pengumuman') {
                News::updateOrCreate(
                    ['slug' => Str::slug('Pendaftaran Kartu Anggota NU (KARTANU) Baktijaya')],
                    [
                        'title' => 'Pendaftaran Kartu Anggota NU (KARTANU) Baktijaya',
                        'content' => '<p>Diberitahukan kepada seluruh warga Nahdliyin di wilayah Baktijaya untuk segera mendaftarkan diri dalam pembuatan KARTANU. Pendaftaran dibuka setiap hari Sabtu di Kantor Sekretariat.</p>',
                        'category_id' => $cat->id,
                        'user_id' => $admin->id,
                        'status' => 'published',
                        'published_at' => now(),
                    ]
                );
            }
        }

        // 4. Articles
        $articleCategory = Category::where('type', 'article')->first();
        if ($articleCategory) {
            $articleTitle = 'Mengenal Tradisi Tahlilan di Masyarakat Nahdliyin';
            $articleSlug = Str::slug($articleTitle);
            Article::updateOrCreate(
                ['slug' => $articleSlug],
                [
                    'title' => $articleTitle,
                    'slug' => $articleSlug,
                    'content' => '<p>Tahlilan merupakan salah satu tradisi yang erat kaitannya dengan warga NU. Artikel ini akan membahas sejarah dan makna mendalam di balik pembacaan tahlil.</p>',
                    'category_id' => $articleCategory->id,
                    'user_id' => $admin->id,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }

        // 5. Gallery
        $galleryCategory = Category::where('type', 'gallery')->first();
        if ($galleryCategory) {
            $galleries = [
                [
                    'title' => 'Dokumentasi Harlah NU',
                    'description' => 'Foto-foto kegiatan Hari Lahir Nahdlatul Ulama yang diselenggarakan oleh PRNU Baktijaya.',
                    'type' => 'photo',
                    'images' => [
                        'https://images.unsplash.com/photo-1590073844006-369302634356?q=80&w=2070&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=2070&auto=format&fit=crop'
                    ],
                    'category_id' => $galleryCategory->id,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'title' => 'Pengajian Akbar Baktijaya',
                    'description' => 'Dokumentasi video Pengajian Akbar bersama para Masyayikh.',
                    'type' => 'video',
                    'video_url' => 'https://www.youtube.com/live/RrceCiquvMs?si=5zFMsvLoe4m07fjq',
                    'category_id' => $galleryCategory->id,
                    'is_active' => true,
                    'is_featured' => false,
                ],
            ];

            foreach ($galleries as $gallery) {
                $gallery['slug'] = Str::slug($gallery['title']);
                \App\Models\Gallery::updateOrCreate(
                    ['slug' => $gallery['slug']],
                    $gallery
                );
            }
        }

        // 6. UMKM
        $umkms = [
            [
                'business_name' => 'Warung Berkah NU',
                'owner_name' => 'Hj. Aminah',
                'phone' => '081234567890',
                'category' => 'makanan',
                'description' => 'Menyediakan nasi uduk dan kue tradisional yang halal dan barokah.',
                'is_active' => true,
            ],
            [
                'business_name' => 'Tailor Makmur',
                'owner_name' => 'Bpk. Sholihin',
                'phone' => '081298765432',
                'category' => 'jasa',
                'description' => 'Jasa jahit pakaian seragam batik NU dan busana muslim.',
                'is_active' => true,
            ],
        ];

        foreach ($umkms as $umkm) {
            $umkm['slug'] = Str::slug($umkm['business_name']);
            Umkm::updateOrCreate(
                ['slug' => $umkm['slug']],
                $umkm
            );
        }
    }
}
