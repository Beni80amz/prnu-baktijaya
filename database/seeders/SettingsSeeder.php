<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'site_name',
                'value' => 'PRNU Baktijaya',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nama Website',
                'description' => 'Nama website yang akan muncul di title bar dan header.',
            ],
            [
                'key' => 'site_description',
                'value' => 'Mewujudkan masyarakat Baktijaya yang religius, toleran, dan sejahtera melalui pengamalan nilai-nilai Ahlussunnah wal Jamaah an-Nahdliyah.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Deskripsi Singkat',
                'description' => 'Deskripsi singkat website untuk keperluan SEO dan footer.',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Logo Website',
                'description' => 'Logo utama website (Navbar & Footer).',
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Favicon',
                'description' => 'Icon yang muncul di tab browser.',
            ],

            // Contact
            [
                'key' => 'contact_address',
                'value' => 'Jl. Jasawarga RT.003/021, Kel. Baktijaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16418',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Alamat Lengkap',
                'description' => 'Alamat lengkap sekretariat.',
            ],
            [
                'key' => 'contact_phone',
                'value' => '0894-0967-7894',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Nomor Telepon',
                'description' => 'Nomor telepon yang bisa dihubungi.',
            ],
            [
                'key' => 'contact_email',
                'value' => 'prnu355@gmail.com',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Email',
                'description' => 'Email resmi sekretariat.',
            ],
            [
                'key' => 'contact_map_link',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.087220000082!2d106.8525385!3d-6.3827433!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eb0060cc451d%3A0x83cf9e3f8ce27e47!2sOffice%20PRNU%20Baktijaya!5e0!3m2!1sen!2sid!4v1769269772778!5m2!1sen!2sid" width="260" height="175" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', // Replace with real link if available
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Link Google Maps',
                'description' => 'Link lokasi Google Maps.',
            ],

            // Social Media
            [
                'key' => 'social_facebook',
                'value' => '#',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Link halaman Facebook.',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://www.instagram.com/mtnbaktijayaofficial',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Link profil Instagram.',
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://www.youtube.com/@mtnbaktijayaofficial',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Youtube URL',
                'description' => 'Link channel Youtube.',
            ],

            // Youtube Widget
            [
                'key' => 'youtube_live_url',
                'value' => 'https://www.youtube.com/live/RrceCiquvMs?si=5zFMsvLoe4m07fjq',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Link Live Streaming (Embed/Watch)',
                'description' => 'Link video YouTube untuk widget Live Streaming. Bisa link watch biasa atau link embed.',
            ],
            [
                'key' => 'youtube_live_title',
                'value' => 'Live Streaming',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Judul Live Streaming',
                'description' => 'Judul yang muncul di bawah video widget.',
            ],
            [
                'key' => 'youtube_video_url',
                'value' => 'https://youtu.be/3sPXStCe4ek?si=DeRHzi6a-tsQ-YVJ',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Link Video Terbaru',
                'description' => 'Link video yang akan tampil jika Status Live tidak aktif.',
            ],
            [
                'key' => 'youtube_live_status',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Status Live',
                'description' => 'Aktifkan jika sedang LIVE. Jika tidak, akan menampilkan Video Terbaru.',
            ],
            [
                'key' => 'youtube_api_key',
                'value' => 'AIzaSyDcqYAFVV8VJHJcYnUa66Ooxp5pdJNrf9o',
                'type' => 'text',
                'group' => 'youtube',
                'label' => 'YouTube Data API Key',
                'description' => 'API Key dari Google Cloud Console untuk akses YouTube Data API.',
            ],
            [
                'key' => 'youtube_channel_id',
                'value' => 'UCr8EV2XrhuuHhKZBpRGvViQ',
                'type' => 'text',
                'group' => 'youtube',
                'label' => 'YouTube Channel ID',
                'description' => 'ID Channel YouTube untuk mengambil data jadwal live otomatis.',
            ],
            // --- Donation Settings ---
            [
                'key' => 'donation_qris_image',
                'value' => 'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg', // Default Placeholder
                'type' => 'image',
                'group' => 'donation',
                'label' => 'QRIS Image',
                'description' => 'Gambar QR Code QRIS untuk donasi.',
            ],
            [
                'key' => 'donation_bank_name',
                'value' => 'Bank Syariah Indonesia',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'Nama Bank',
                'description' => 'Nama Bank untuk transfer donasi.',
            ],
            [
                'key' => 'donation_bank_number',
                'value' => '1234 5678 90',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'Nomor Rekening',
                'description' => 'Nomor rekening untuk transfer donasi.',
            ],
            [
                'key' => 'donation_bank_owner',
                'value' => 'PRNU Baktijaya',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'Nama Pemilik Rekening',
                'description' => 'Nama pemilik rekening bank.',
            ],
            // Additional Banks
            [
                'key' => 'donation_bank_bri',
                'value' => '-',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'No. Rekening BRI',
                'description' => 'Nomor rekening BRI (Kosongkan jika tidak ada).',
            ],
            [
                'key' => 'donation_bank_bca',
                'value' => '-',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'No. Rekening BCA',
                'description' => 'Nomor rekening BCA (Kosongkan jika tidak ada).',
            ],
            [
                'key' => 'donation_bank_mandiri',
                'value' => '-',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'No. Rekening Mandiri',
                'description' => 'Nomor rekening Mandiri (Kosongkan jika tidak ada).',
            ],
            // E-Wallets
            [
                'key' => 'donation_ewallet_ovo',
                'value' => '-',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'Nomor OVO',
                'description' => 'Nomor OVO untuk transfer (Kosongkan jika tidak ada).',
            ],
            [
                'key' => 'donation_ewallet_gopay',
                'value' => '-',
                'type' => 'text',
                'group' => 'donation',
                'label' => 'Nomor Gopay',
                'description' => 'Nomor Gopay untuk transfer (Kosongkan jika tidak ada).',
            ],
            [
                'key' => 'donation_contact_person',
                'value' => '6281234567890', // Default placeholder
                'type' => 'text',
                'group' => 'donation',
                'label' => 'WhatsApp Bendahara',
                'description' => 'Nomor WhatsApp konfirmasi donasi (Format: 628xxx).',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
