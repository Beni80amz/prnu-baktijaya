<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KasDigitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Income Types
        $incomeTypes = [
            ['name' => 'Infaq/Shodaqoh', 'code' => 'A1'],
            ['name' => 'Penghimpunan KOIN NU', 'code' => 'A2'],
        ];
        foreach ($incomeTypes as $type) {
            \App\Models\IncomeType::firstOrCreate(['code' => $type['code']], $type);
        }

        // Expense Types
        $expenseTypes = [
            ['name' => 'Insentif Relawan', 'code' => 'B1'],
            ['name' => 'Pendidikan', 'code' => 'B2'],
            ['name' => 'Keorganisasian', 'code' => 'B3'],
            ['name' => 'Kesehatan', 'code' => 'B4'],
            ['name' => 'Kesiagaan Bencana', 'code' => 'B5'],
            ['name' => 'Kesekretariatan', 'code' => 'B6'],
        ];
        foreach ($expenseTypes as $type) {
            \App\Models\ExpenseType::firstOrCreate(['code' => $type['code']], $type);
        }

        // Regions
        for ($i = 1; $i <= 29; $i++) {
            $code = (string) $i;
            $name = 'RW.' . str_pad($code, 3, '0', STR_PAD_LEFT);
            \App\Models\Region::firstOrCreate(['code' => $code], ['name' => $name]);
        }

        // Volunteers
        $volunteersData = [
            ['name' => 'Dwi Purnomo Subekti', 'region_name' => 'RW.004'],
            ['name' => 'Riansyah', 'region_name' => 'RW.020'],
            ['name' => 'Bachtiar', 'region_name' => 'RW.021'],
            ['name' => 'Rizky Fauzi', 'region_name' => 'RW.021'],
            ['name' => 'Rohadi Kurnia', 'region_name' => 'RW.021'],
            ['name' => 'Hadi Ali', 'region_name' => 'RW.026'],
            ['name' => 'Achmad Ghifari Arrizki', 'region_name' => 'RW.026'],
            ['name' => 'Romdi', 'region_name' => 'RW.022'],
            ['name' => 'Ainul Yaqin', 'region_name' => 'RW.020'],
            ['name' => 'Najmuddin', 'region_name' => 'RW.021'],
            ['name' => 'Ahmad Jaya', 'region_name' => 'RW.026'],
            ['name' => 'Deden Gunawan', 'region_name' => 'RW.005'],
        ];

        foreach ($volunteersData as $data) {
            $region = \App\Models\Region::where('name', $data['region_name'])->first();
            if ($region) {
                \App\Models\Volunteer::firstOrCreate(
                    ['name' => $data['name'], 'region_id' => $region->id]
                );
            }
        }

        // Munfiquns
        $munfiqunsData = [
            ['name' => 'ROHADI', 'code' => '21001', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'RIFQI FAUZAN RAJBA', 'code' => '21002', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HJ. JANNATIN', 'code' => '21003', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'WARKOP', 'code' => '21004', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BU ISTIQOMAH', 'code' => '21005', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ES THE UMMI', 'code' => '21006', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ANDALAS RAYA', 'code' => '21007', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'WARUNG MISBAH', 'code' => '21008', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PAK JAMAL HH', 'code' => '21009', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MUSTHOFA KAMAL HH', 'code' => '21010', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'H. DENI', 'code' => '21011', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BENI SOLEHUDIN', 'code' => '21012', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'JUFRIYADI HH', 'code' => '21013', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'RAHMAT HIDAYAT ( KOMAT )', 'code' => '21014', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AMAT SIAN', 'code' => '21015', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MAMAN ( BENGKEL )', 'code' => '21016', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'WARUNG ACEH', 'code' => '21017', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'SITI MAESAROH', 'code' => '21018', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ILHAM MAULANA', 'code' => '21019', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'JAFAR SIAN', 'code' => '21020', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'FATIHIN', 'code' => '21021', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PAK SOBARI', 'code' => '21022', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ADI TRI WALUYO', 'code' => '21023', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ARI BENGKEL ( RW SAIMIN )', 'code' => '21024', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HERMAN', 'code' => '21025', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BANG OPICK HS', 'code' => '21026', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'OKTI', 'code' => '21027', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MURYANA ( RYAN IBRAHIM )', 'code' => '21028', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'OM AAL', 'code' => '21029', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'JAFAR HS', 'code' => '21030', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'SAROHMAN', 'code' => '21031', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'M AMIN', 'code' => '21032', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MAS YANTO', 'code' => '21033', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'THE DEWI', 'code' => '21034', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ASEP SYAEPUDIN', 'code' => '21035', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'H. SYABANI AHMAD', 'code' => '21036', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HJ. BARIYAH', 'code' => '21037', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AHMAD RIVA\'I', 'code' => '21038', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'SULTAN AL HAFIDZ', 'code' => '21039', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AHMAD APRIANSYAH ( RYAN AKI )', 'code' => '21040', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AZIS MUHLIS', 'code' => '21041', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MAHMUD ( BANG ANJUH )', 'code' => '21042', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'DELA AMELIA ULHAQ', 'code' => '21043', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'THE WINDA', 'code' => '21044', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'DIDI KUSNADI ( RW )', 'code' => '21045', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MASITOH', 'code' => '21046', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'EUIS', 'code' => '21047', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MALIA', 'code' => '21048', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'IWAN SETIAWAN ( PAK IWAN )', 'code' => '21049', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BI OOK', 'code' => '21050', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'IBU SURYANIH', 'code' => '210100', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BPK. SAHURI', 'code' => '210101', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'MOH. TUKIMAN (ALDO)', 'code' => '210102', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'RISKA AULIA NISA', 'code' => '210104', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'ABDUL HARIS SAYUDI', 'code' => '210105', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'DADANG MULYADI', 'code' => '210106', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'TEGUH P', 'code' => '210107', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'MUSTAQIM', 'code' => '210108', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'AYAT RU\'YAT', 'code' => '210109', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'ISKAK', 'code' => '210110', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'MAD NUR', 'code' => '210111', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SYAKURI', 'code' => '210112', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'USTADZ DEDE', 'code' => '210113', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'DIDIN BADRUDIN 1', 'code' => '210114', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BPK. ALI', 'code' => '210115', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'ANDI SURYANSYAH', 'code' => '210116', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'KONG AMIT', 'code' => '210117', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG CEPI', 'code' => '210118', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK DE SUATMA', 'code' => '210119', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'TIRTAYASA', 'code' => '210120', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'FIQRI', 'code' => '210121', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG SOLEH', 'code' => '210122', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'NAJMUDDIN', 'code' => '210123', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SOLAHUDIN', 'code' => '210124', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG BADRUDIN', 'code' => '210125', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SUJONO', 'code' => '210126', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SUKANTA', 'code' => '210127', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BUDE ALIFIA', 'code' => '210128', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'IBU PUTRI', 'code' => '210129', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'H. HAIRUDIN', 'code' => '210130', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG KARDO', 'code' => '210131', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'RESMI MILAWATI', 'code' => '210132', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG JOHAN', 'code' => '210133', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'KUSTIAWAN', 'code' => '210134', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'FAHRUROJI', 'code' => '210135', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'H. SARIPUDIN', 'code' => '210136', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG IBRAHIM', 'code' => '210137', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK DE SUNARTA', 'code' => '210138', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK DE SUTARNO', 'code' => '210139', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK DE DARYONO', 'code' => '210140', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK SARIF', 'code' => '210141', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SAIFUL', 'code' => '210142', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'FADILAH', 'code' => '210143', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BANG PUDIN', 'code' => '210145', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'FAJAR', 'code' => '210147', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'DIDIN BADRUDIN 2', 'code' => '210149', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'AMIH', 'code' => '210151', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BU LARAS (GURU TK)', 'code' => '210152', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'WAHIDUN AL ALIF', 'code' => '210153', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PAK HERU', 'code' => '210154', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'NALIM AMIN', 'code' => '210155', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HASYIM', 'code' => '210156', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ODING NURYADIN', 'code' => '210157', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'H. DARYONO', 'code' => '210158', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BPK. DARWANTO', 'code' => '210159', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'UST. HASAN BASRI', 'code' => '210160', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AHMAD SOBARI', 'code' => '210161', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HJ. UUN SANWANI', 'code' => '210162', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'AMRIJAL', 'code' => '210163', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MA\'RUF HAS', 'code' => '210164', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'RIZKI FAUZI ( BULE )', 'code' => '210165', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'NURCAHAYA', 'code' => '210166', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PAK JAFARUDIN', 'code' => '210167', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'MBA IPAT', 'code' => '210168', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'DRS. AZHARI', 'code' => '210169', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'ANDI SUHARDI', 'code' => '210170', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'H. M FURQON', 'code' => '210171', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HJ. MARHUMAH', 'code' => '210172', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'SUJONO', 'code' => '210173', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'CU AMIN', 'code' => '210174', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'TEH ELA', 'code' => '210175', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'CANG ENDIN', 'code' => '210176', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PHOTO COPY', 'code' => '210177', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'COKI (BOLONG)', 'code' => '210178', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'PAK TEGUH', 'code' => '210179', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'HUSEIN', 'code' => '210180', 'volunteer_name' => 'RIZKY FAUZI'],
            ['name' => 'BEJO WALUYO', 'code' => '210182', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SOFYAN. HS', 'code' => '210184', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'PAK JARIN', 'code' => '210185', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'H. HAMBALI', 'code' => '210186', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'H. BAKHTIAR', 'code' => '210187', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SYARIFAH HN', 'code' => '210188', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BPK. SAHURI 2', 'code' => '210243', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'BPK. SAHURI 3', 'code' => '210244', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'ARFIAN', 'code' => '210246', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'YUNUS', 'code' => '210247', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'NANDANG', 'code' => '210248', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'ILHAM FAHLEVI', 'code' => '210249', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'YAN HENDARTO', 'code' => '210250', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'IBU AULIA', 'code' => '210251', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'AHMAD SUJANA', 'code' => '210252', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'SAMSUDIN (YOYOH)', 'code' => '210253', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'MOH. TUKIMAN (ALDO)', 'code' => '210261', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'HASBI', 'code' => '210262', 'volunteer_name' => 'BACHTIAR'],
            ['name' => 'AMSORI (H. SARI)', 'code' => '210263', 'volunteer_name' => 'BACHTIAR'],
        ];

        foreach ($munfiqunsData as $data) {
            $volunteer = \App\Models\Volunteer::where('name', $data['volunteer_name'])->first();
            if ($volunteer) {
                \App\Models\Munfiqun::firstOrCreate(
                    ['code' => $data['code']],
                    [
                        'name' => $data['name'],
                        'volunteer_id' => $volunteer->id,
                    ]
                );
            }
        }
    }
}
