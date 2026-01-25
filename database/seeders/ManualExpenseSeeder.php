<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\ExpenseType;
use App\Models\Volunteer;
use Illuminate\Support\Str;

class ManualExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            ['2024-08-11', 'Pengeluaran', 'Kesekretariatan', null, null, '150.000', 'Cetak Banner Lailatul Ijtima ( Bahan Albartos, 200 x 80 cm )'],
            ['2024-09-08', 'Pengeluaran', 'Insentif Relawan', null, 'Rohadi Kurnia', '475.000', 'Rohadi Kurnia'],
            ['2024-09-08', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '534.000', 'Bachtiar'],
            ['2024-09-10', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '350.000', 'Hadi Ali'],
            ['2024-09-10', 'Pengeluaran', 'Insentif Relawan', null, 'Ainul Yaqin', '100.000', 'Ainul Yaqin'],
            ['2024-09-22', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '200.000', 'Perbaikan Jalan Di Link. RT.001/021 Baktijaya'],
            ['2024-09-28', 'Pengeluaran', 'Kesekretariatan', null, null, '89.000', '3 Karton Air Mineral (Untuk Lailatul Ijtima)'],
            ['2024-10-02', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', '4 Karton Air Mineral (Ta\'ziah A. Suhaidi bin Marzuki Rw. 26)'],
            ['2024-10-06', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '460.000', 'Rizky Fauzi'],
            ['2024-10-06', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '500.000', 'Bachtiar'],
            ['2024-10-06', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '380.000', 'Hadi Ali'],
            ['2024-10-14', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '50.000', 'Hadi Ali'],
            ['2024-10-22', 'Pengeluaran', 'Insentif Relawan', null, 'Dwi Purnomo Subekti', '150.000', 'Dwi Purnomo Subekti'],
            ['2024-10-31', 'Pengeluaran', 'Pendidikan', null, null, '400.000', 'Pembayaran SPP Yatim Baktijaya (1 Orang)'],
            ['2024-11-05', 'Pengeluaran', 'Kesekretariatan', null, null, '413.500', 'Jasa Desain, Cetak & Ongkir Logo Plang PRNU 4 Buah'],
            ['2024-11-10', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'Operasional Pelantikan PRNU Baktijaya'],
            ['2024-11-10', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '500.000', 'Bachtiar'],
            ['2024-11-10', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '360.000', 'Hadi Ali'],
            ['2024-11-11', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '495.000', 'Rizky Fauzi'],
            ['2024-11-11', 'Pengeluaran', 'Pendidikan', null, null, '1.400.000', 'Pembayaran SAS di MI Al Islamiyah ( 17 Siswa Yatim )'],
            ['2024-11-11', 'Pengeluaran', 'Pendidikan', null, null, '1.000.000', 'Pembayaran SAS & SPP di MI Miftahul Falah ( 5 Siswa Yatim )'],
            ['2024-11-11', 'Pengeluaran', 'Pendidikan', null, null, '1.125.000', 'Pembayaran SAS & SPP di MI Nurul Irfan ( 5 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '1.750.000', 'Pembayaran PAS di MTs Al Islamiyah ( 7 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '350.000', 'Pelunasan PAS Semester Ganjil di MTs Al Islamiyah ( 2 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '350.000', 'Biaya Administrasi Penerimaan Siswa Baru di MTs Al Islamiyah (1 Siswa Yatim)'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '1.875.000', 'Pembayaran @3 Bulan SPP di MTs Al Kautsar ( 5 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '1.750.000', 'Pembayaran @2 Bulan SPP di MTs Nur Al Zahra ( 3 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '500.000', 'Pembayaran Kegiatan Kelas IX di MTs Nur Al Zahra ( 1 Siswa Yatim )'],
            ['2024-11-12', 'Pengeluaran', 'Pendidikan', null, null, '1.275.000', 'Pembayaran Buku LKS & Daftar Ulang di MI Al Islamiya ( 6 Siswa Yatim )'],
            ['2024-11-15', 'Pengeluaran', 'Insentif Relawan', null, 'Ainul Yaqin', '150.000', 'Ainul Yaqin'],
            ['2024-12-08', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '185.000', 'Hadi Ali'],
            ['2024-12-08', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '380.000', 'Rizky Fauzi'],
            ['2024-12-08', 'Pengeluaran', 'Insentif Relawan', null, 'Nazmudin', '400.000', 'Nazmudin'],
            ['2024-12-09', 'Pengeluaran', 'Kesekretariatan', null, null, '500.000', 'Bantuan Untuk Kegiatan MAKESTA IPNU & IPPNU Sukmajaya'],
            ['2024-12-10', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '1.000.000', 'Donasi Korban Bencana Sukabumi dan Sekitarnya'],
            ['2024-12-20', 'Pengeluaran', 'Insentif Relawan', null, 'Dwi Purnomo Subekti', '80.000', 'Dwi Purnomo Subekti'],
            ['2024-12-22', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '300.000', 'Bantuan Perbaikan Drainase Di Link. RT 008/021 Baktijaya'],
            ['2024-12-24', 'Pengeluaran', 'Kesehatan', null, null, '1.000.000', 'Donasi Untuk Khitanan Massal'],
            ['0204-12-24', 'Pengeluaran', 'Insentif Relawan', null, 'Ainul Yaqin', '100.000', 'Ainul Yaqin'],
            ['2025-01-05', 'Pengeluaran', 'Kesekretariatan', null, null, '125.000', 'Konsumsi Rapat Persiapan Raker 2025'],
            ['2025-01-11', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '435.000', 'Rizky Fauzi'],
            ['2025-01-12', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '300.000', 'Hadi Ali'],
            ['2025-01-12', 'Pengeluaran', 'Insentif Relawan', null, 'Nazmudin', '450.000', 'Nazmudin'],
            ['2025-01-13', 'Pengeluaran', 'Insentif Relawan', null, 'Dwi Purnomo Subekti', '140.000', 'Dwi Purnomo Subekti'],
            ['2025-01-14', 'Pengeluaran', 'Kesekretariatan', null, null, '230.000', 'Cetak Banner HARLAH NU & MUSKER 4 Pcs'],
            ['2025-01-17', 'Pengeluaran', 'Insentif Relawan', null, 'Deden Gunawan', '60.000', 'Deden Gunawan'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '500.000', 'MUSKER - Snack Box 50 Box  @10.000'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '800.000', 'MUSKER - Nasi Kotak 40 Box  @ 20.000'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'MUSKER - Kopi + Gelas'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'MUSKER - Keamanan'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '200.000', 'MUSKER - Kebersihan'],
            ['2025-01-18', 'Pengeluaran', 'Kesekretariatan', null, null, '300.000', 'MUSKER - Operasional'],
            ['2025-01-31', 'Pengeluaran', 'Pendidikan', null, null, '425.000', 'Bantuan Biaya SPP Di SMK YAPPA'],
            ['2025-01-09', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '300.000', 'Bantuan Perbaikan Jalan Di Link. RT 002/026 Baktijaya'],
            ['2025-01-09', 'Pengeluaran', 'Kesekretariatan', null, null, '15.000', 'Plastik Kiloan & Karet @1Pak'],
            ['2025-01-09', 'Pengeluaran', 'Insentif Relawan', null, 'Nazmudin', '420.000', 'Nazmudin'],
            ['2025-01-09', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '280.000', 'Hadi Ali'],
            ['2025-02-10', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '345.000', 'Rizky Fauzi'],
            ['2025-02-13', 'Pengeluaran', 'Kesekretariatan', null, null, '400.000', 'Biaya Pendaftaran PKD Ansor Baktijaya @ 4 Orang'],
            ['2025-02-14', 'Pengeluaran', 'Kesekretariatan', null, null, '251.000', 'Cetak Banner Marhaban Ya Ramadhan 6 Pcs'],
            ['2025-02-22', 'Pengeluaran', 'Kesekretariatan', null, null, '5.984.000', 'Berkah Ramadhan PRNU Baktijaya'],
            ['2025-02-22', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '300.000', 'Bantuan Kerja Bakti RT 007/021 Baktijaya'],
            ['2025-02-21', 'Pengeluaran', 'Insentif Relawan', null, 'Ainul Yaqin', '100.000', 'Ainul Yaqin'],
            ['2025-03-01', 'Pengeluaran', 'Kesekretariatan', null, null, '300.000', 'Operasional berbagi berkah Ramadhan 1446 H'],
            ['2025-03-16', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '265.000', 'Hadi Ali'],
            ['2025-03-16', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '495.000', 'Bachtiar'],
            ['2025-03-18', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '340.000', 'Rizky Fauzi'],
            ['2025-03-16', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'Gebyar Ramadhan MWC NU Sukmajaya'],
            ['2025-03-18', 'Pengeluaran', 'Kesekretariatan', null, null, '20.000', 'Pembelian Kantong Plastik'],
            ['2025-03-21', 'Pengeluaran', 'Kesekretariatan', null, null, '300.000', 'Banner Idul Fitri 1446 H'],
            ['2025-03-23', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '150.000', 'Takziyah Alm. Munadih bin Risin'],
            ['2025-04-25', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '340.000', 'Bachtiar'],
            ['2025-04-27', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '345.000', 'Rizky Fauzi'],
            ['2025-05-01', 'Pengeluaran', 'Pendidikan', null, null, '832.000', 'Pembelian 2 Set Papan Tulis & kipas angin U/ TPQ AS SA\'ADAH'],
            ['2025-05-02', 'Pengeluaran', 'Pendidikan', null, null, '750.000', 'Pembayaran Uang Ujian Sekolah di MTS Al Islamiyah AMZ'],
            ['2025-05-04', 'Pengeluaran', 'Pendidikan', null, null, '620.500', 'Pembelian 20 Buah Lekar u/ TPQ Subbanul Anwar'],
            ['2025-05-05', 'Pengeluaran', 'Pendidikan', null, null, '1.540.000', 'Pembelian 60 Buah Lekar Plastik'],
            ['2025-05-07', 'Pengeluaran', 'Insentif Relawan', null, 'Dwi Purnomo Subekti', '147.000', 'Dwi Purnomo Subekti'],
            ['2025-05-12', 'Pengeluaran', 'Kesekretariatan', null, null, '500.000', 'Media PRNU Baktijaya ( Domain Website DLL )'],
            ['2025-05-12', 'Pengeluaran', 'Kesekretariatan', null, null, '60.000', 'Registrasi Canva Pro'],
            ['2025-05-12', 'Pengeluaran', 'Kesekretariatan', null, null, '1.000.000', 'Registrasi Pelatihan Juru Sembelih Halal 10 Kader'],
            ['2025-05-14', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '380.000', 'Hadi Ali'],
            ['2025-05-26', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '325.000', 'Rizky Fauzi'],
            ['2025-05-31', 'Pengeluaran', 'Kesekretariatan', null, null, '400.000', 'Registrasi Pelatihan Juru Sembelih Halal 4 Kader'],
            ['2025-05-26', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', 'Takziyah Alm. Bpk. Nisin'],
            ['2025-06-12', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '550.000', 'Bachtiar'],
            ['2025-06-15', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '205.000', 'Hadi Ali'],
            ['2025-06-10', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', 'Air Mineral 4 Dus ( Alm. Ust. Hasan Basri )'],
            ['2025-06-19', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', 'Air Mineral 4 Dus ( Alm. Ikin Sodikin )'],
            ['2025-06-25', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', 'Air Mineral 4 Dus ( Alm. H. Harun )'],
            ['2025-06-25', 'Pengeluaran', 'Kesekretariatan', null, null, '250.000', 'Pawai Muharram 1447 H ( Masjid Al Muawanah )'],
            ['2025-06-25', 'Pengeluaran', 'Kesekretariatan', null, null, '533.000', 'Cetak Banner, Bagedrop & Kupon Pawai Muharram 1447 H'],
            ['2025-06-25', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '260.000', 'Rizky Fauzi'],
            ['2025-07-06', 'Pengeluaran', 'Kesekretariatan', null, null, '260.000', 'Air Mineral 10 Dus (Pawai Muharram 1447 H)'],
            ['2025-07-06', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-07-06', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', 'Air Mineral 4 Dus (Almh. Ibu Aisyah)'],
            ['2025-07-08', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '442.400', 'Bachtiar'],
            ['2025-07-20', 'Pengeluaran', 'Pendidikan', null, null, '2.000.000', 'Pelatihan Pemulasaran Jenazah'],
            ['2025-08-01', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-08-03', 'Pengeluaran', 'Insentif Relawan', null, 'Deden Gunawan', '60.000', 'Deden Gunawan'],
            ['2025-08-04', 'Pengeluaran', 'Insentif Relawan', null, 'Dwi Purnomo Subekti', '180.000', 'Dwi Purnomo Subekti'],
            ['2025-08-13', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '370.000', 'Rizky Fauzi'],
            ['2025-08-17', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '300.000', 'Hadi Ali'],
            ['2025-08-17', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '443.000', 'Bachtiar'],
            ['2025-09-02', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-09-21', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '330.000', 'Rizky Fauzi'],
            ['2025-09-21', 'Pengeluaran', 'Kesekretariatan', null, null, '8.000', 'Kantong Plastik 1/2 Kg'],
            ['2025-10-01', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'Lazisnu Depok'],
            ['2025-10-02', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-10-04', 'Pengeluaran', 'Kesekretariatan', null, null, '185.000', 'Air 5 Karto u/ Konferancab Fatayat Baktijaya'],
            ['2025-10-09', 'Pengeluaran', 'Kesekretariatan', null, null, '2.033.400', '7 Buah Tatakan Dan Tiang Pataka Banom NU'],
            ['2025-10-11', 'Pengeluaran', 'Kesekretariatan', null, null, '500.000', 'Proposal Ngaji Bareng Gus Iqdam (PC Ansor Depok )'],
            ['2025-10-11', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '381.600', 'Bachtiar'],
            ['2025-10-20', 'Pengeluaran', 'Kesekretariatan', null, null, '240.000', 'Baner HSN 2025'],
            ['2025-10-25', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '229.000', 'Rizky Fauzi'],
            ['2025-10-27', 'Pengeluaran', 'Kesekretariatan', null, null, '202.500', 'Donasi Muslimat NU Sukmajaya'],
            ['2025-10-31', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', '4 Dus Air Mineral ( Takziyah H. Rusdi )'],
            ['2025-10-31', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '100.000', '4 Dus Air Mineral ( Takziyah Bpk. Sanusi )'],
            ['2025-11-06', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-11-09', 'Pengeluaran', 'Insentif Relawan', null, 'Hadi Ali', '310.000', 'Hadi Ali'],
            ['2025-11-09', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '340.000', 'Bachtiar'],
            ['2025-11-11', 'Pengeluaran', 'Insentif Relawan', null, 'Nazmudin', '100.000', 'Nazmudin'],
            ['2025-11-13', 'Pengeluaran', 'Pendidikan', null, null, '500.000', 'Water Bowler'],
            ['2025-11-22', 'Pengeluaran', 'Kesekretariatan', null, null, '500.000', 'Transport Raker NU Sukmajaya Di Sukabumi'],
            ['2025-11-28', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '300.000', 'Rizky Fauzi'],
            ['2025-11-29', 'Pengeluaran', 'Kesekretariatan', null, null, '100.000', 'Infaq Lazisnu Depok'],
            ['2025-12-05', 'Pengeluaran', 'Kesiagaan Bencana', null, null, '1.500.000', 'Donasi Bencana Sumatra'],
            ['2025-12-07', 'Pengeluaran', 'Insentif Relawan', null, 'Bachtiar', '268.000', 'Bachtiar'],
            ['2025-12-07', 'Pengeluaran', 'Pendidikan', null, null, '260.000', 'Air Mineral 10 Dus (MT. Al Ikhlas)'],
            ['2025-12-26', 'Pengeluaran', 'Insentif Relawan', null, 'Ainul Yaqin', '251.000', 'Ainul Yaqin'],
            ['2026-01-04', 'Pengeluaran', 'Insentif Relawan', null, 'Rizky Fauzi', '374.000', 'Rizky Fauzi'],
            ['2026-01-20', 'Pengeluaran', 'Kesekretariatan', null, null, '1.000.000', 'Donasi HARLAH 1 Abad NU, PCNU Depok'],
            ['2026-01-24', 'Pengeluaran', 'Kesekretariatan', null, null, '360.000', 'Bagedrop Panggung Harlah NU Baktijaya Ke 100 Thn Masehi'],
            ['2026-01-24', 'Pengeluaran', 'Kesekretariatan', null, null, '820.000', 'Cetak Kupon & Stiker Manfaat Koin NU'],
            ['2026-01-25', 'Pengeluaran', 'Kesekretariatan', null, null, '120.000', 'Cetak Baner Undangan Harlah NU Baktijaya'],
        ];

        foreach ($transactions as $data) {
            try {
                // Correct 0204 -> 2024 date typo if present (from previous knowledge, better safe than sorry)
                $transactionDate = str_replace('0204-', '2024-', $data[0]);

                $type = 'expense';
                $categoryName = $data[2];
                // $regionName = $data[3] ?? null; 
                $volunteerName = $data[4] ?? null;

                // Remove dots from amount
                $amount = str_replace('.', '', $data[5]);

                $description = $data[6];

                // Resolve Expense Type
                $expenseTypeId = null;
                $expenseType = ExpenseType::firstOrCreate(
                    ['name' => $categoryName],
                    ['code' => Str::slug($categoryName)]
                );
                $expenseTypeId = $expenseType->id;

                // Resolve Volunteer (if exists defined in row)
                $volunteerId = null;
                if ($volunteerName) {
                    $volunteer = Volunteer::where('name', 'like', "%{$volunteerName}%")->first();

                    if (!$volunteer) {
                        // Region ID is required for Volunteers. Create a default 'General' region if null.
                        $generalRegion = \App\Models\Region::firstOrCreate(
                            ['name' => 'General'],
                            ['code' => 'general']
                        );
                        $volunteer = Volunteer::create(['name' => $volunteerName, 'region_id' => $generalRegion->id]);
                    }
                    $volunteerId = $volunteer->id;
                }

                Transaction::create([
                    'transaction_date' => $transactionDate,
                    'type' => $type,
                    'amount' => $amount,
                    'description' => $description,
                    'income_type_id' => null,
                    'expense_type_id' => $expenseTypeId,
                    'region_id' => $volunteerId ? $volunteer->region_id : null,
                    'volunteer_id' => $volunteerId,
                    'user_id' => null,
                ]);
            } catch (\Exception $e) {
                echo "ERROR on ROW: " . json_encode($data) . "\n";
                echo "MSG: " . $e->getMessage() . "\n";
                // don't die, just print and continue? or die? Die is safer to stop inconsistent state.
                die();
            }
        }
    }
}
