<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\IncomeType;
use App\Models\ExpenseType;
use App\Models\Region;
use App\Models\Volunteer;
use Illuminate\Support\Str;

class ManualTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            ['2024-07-30', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.026', 'Ust. Zainal Abidin', 199000, 'Infaq/Shodaqoh RW.026'],
            ['2024-09-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rohadi Kurnia', 5010000, 'Penghimpunan KOIN NU RW.021'],
            ['2024-09-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Riansyah', 1238800, 'Penghimpunan KOIN NU RW.020'],
            ['2024-09-10', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 551500, 'Penghimpunan KOIN NU RW.026'],
            ['2024-09-10', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Ainul Yaqin', 505300, 'Penghimpunan KOIN NU RW.020'],
            ['2024-09-11', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.022', 'Romdi', 557100, 'Penghimpunan KOIN NU RW.022'],
            ['2024-10-06', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 2297200, 'Penghimpunan KOIN NU RW.021'],
            ['2024-10-06', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2551200, 'Penghimpunan KOIN NU RW.021'],
            ['2024-10-06', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1901800, 'Penghimpunan KOIN NU RW.026'],
            ['2024-10-14', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 502400, 'Penghimpunan KOIN NU RW.026'],
            ['2024-10-18', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 83000, 'Penghimpunan KOIN NU RW.026'],
            ['2024-10-22', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 750000, 'Penghimpunan KOIN NU RW.004'],
            ['2024-11-05', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.022', 'Romdi', 483300, 'Penghimpunan KOIN NU RW.022'],
            ['2024-11-07', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2539800, 'Penghimpunan KOIN NU RW.021'],
            ['2024-11-10', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1812600, 'Penghimpunan KOIN NU RW.026'],
            ['2024-11-10', 'Pemasukan', 'Infaq/Shodaqoh', 'Personal', 'Bpk. Heru', 154500, 'Infaq/Shodaqoh Personal'],
            ['2024-11-11', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 2482400, 'Penghimpunan KOIN NU RW.021'],
            ['2024-11-13', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.021', 'Lazisnu Depok', 100000, 'Infaq/Shodaqoh RW.021'],
            ['2024-11-13', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Ahmad Jaya', 149000, 'Penghimpunan KOIN NU RW.026'],
            ['2024-11-14', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Ainul Yaqin', 753100, 'Penghimpunan KOIN NU RW.020'],
            ['2024-11-15', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rohadi Kurnia', 80900, 'Penghimpunan KOIN NU RW.021'],
            ['2024-12-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 925000, 'Penghimpunan KOIN NU RW.026'],
            ['2024-12-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Nazmudin', 2047400, 'Penghimpunan KOIN NU RW.021'],
            ['2024-12-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1908800, 'Penghimpunan KOIN NU RW.021'],
            ['2024-12-09', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 19100, 'Penghimpunan KOIN NU RW.004'],
            ['2024-12-20', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 420000, 'Penghimpunan KOIN NU RW.004'],
            ['2024-12-21', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.022', 'Romdi', 480800, 'Penghimpunan KOIN NU RW.022'],
            ['2024-12-24', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Ainul Yaqin', 532300, 'Penghimpunan KOIN NU RW.020'],
            ['2025-01-11', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 2149400, 'Penghimpunan KOIN NU RW.021'],
            ['2025-01-12', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1515100, 'Penghimpunan KOIN NU RW.026'],
            ['2025-01-12', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Najmudin', 2274600, 'Penghimpunan KOIN NU RW.021'],
            ['2025-01-13', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 700000, 'Penghimpunan KOIN NU RW.004'],
            ['2025-01-17', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.005', 'Deden Gunawan', 320500, 'Penghimpunan KOIN NU RW.005'],
            ['2025-02-09', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Najmudin', 2106500, 'Penghimpunan KOIN NU RW.021'],
            ['2025-02-09', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1423300, 'Penghimpunan KOIN NU RW.026'],
            ['2025-02-10', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1728500, 'Penghimpunan KOIN NU RW.021'],
            ['2025-02-21', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Ainul Yaqin', 508000, 'Penghimpunan KOIN NU RW.020'],
            ['2025-03-16', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1328600, 'Penghimpunan KOIN NU RW.026'],
            ['2025-03-16', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2475300, 'Penghimpunan KOIN NU RW.021'],
            ['2025-03-18', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1735700, 'Penghimpunan KOIN NU RW.021'],
            ['2025-03-29', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.021', 'UD. CT Group', 50000, 'Infaq/Shodaqoh RW.021'],
            ['2025-04-25', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 1703000, 'Penghimpunan KOIN NU RW.021'],
            ['2025-04-07', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1729700, 'Penghimpunan KOIN NU RW.021'],
            ['2025-05-01', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.021', 'Hamba Allah', 150000, 'Infaq/Shodaqoh RW.021'],
            ['2025-05-01', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.021', 'AMDK Santri Sukmajaya', 100000, 'Infaq/Shodaqoh RW.021'],
            ['2025-05-02', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.006', 'Bachtiar', 215000, 'Penghimpunan KOIN NU RW.006'],
            ['2025-05-05', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 735000, 'Penghimpunan KOIN NU RW.004'],
            ['2025-05-14', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1919600, 'Penghimpunan KOIN NU RW.026'],
            ['2025-05-26', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1633200, 'Penghimpunan KOIN NU RW.021'],
            ['2025-06-03', 'Pemasukan', 'Infaq/Shodaqoh', 'RW.021', 'AMDK Santri Sukmajaya', 100000, 'Infaq/Shodaqoh RW.021'],
            ['2025-06-12', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2735100, 'Penghimpunan KOIN NU RW.021'],
            ['2025-06-15', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1077400, 'Penghimpunan KOIN NU RW.026'],
            ['2025-06-25', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1314500, 'Penghimpunan KOIN NU RW.021'],
            ['2025-07-08', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2212000, 'Penghimpunan KOIN NU RW.021'],
            ['2025-08-03', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.005', 'Deden Gunawan', 330000, 'Penghimpunan KOIN NU RW.005'],
            ['2025-08-04', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.004', 'Dwi Purnomo Subekti', 910000, 'Penghimpunan KOIN NU RW.004'],
            ['2025-08-13', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1854000, 'Penghimpunan KOIN NU RW.021'],
            ['2025-08-17', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1495900, 'Penghimpunan KOIN NU RW.026'],
            ['2025-08-17', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 2217000, 'Penghimpunan KOIN NU RW.021'],
            ['2025-09-07', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.025', 'Romdi', 691300, 'Penghimpunan KOIN NU RW.025'],
            ['2025-09-21', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1650400, 'Penghimpunan KOIN NU RW.021'],
            ['2025-10-11', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 1908000, 'Penghimpunan KOIN NU RW.021'],
            ['2025-10-25', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1144900, 'Penghimpunan KOIN NU RW.021'],
            ['2025-11-09', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.026', 'Hadi Ali', 1547000, 'Penghimpunan KOIN NU RW.026'],
            ['2025-11-09', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 1694300, 'Penghimpunan KOIN NU RW.021'],
            ['2025-11-11', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.006', 'Najmudin', 561000, 'Penghimpunan KOIN NU RW.006'],
            ['2025-11-13', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.005', 'Deden Gunawan', 305000, 'Penghimpunan KOIN NU RW.005'],
            ['2025-11-28', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1497600, 'Penghimpunan KOIN NU RW.021'],
            ['2025-12-07', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Bachtiar', 1343100, 'Penghimpunan KOIN NU RW.021'],
            ['2025-12-26', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.020', 'Ainul Yaqin', 1259600, 'Penghimpunan KOIN NU RW.020'],
            ['2026-01-04', 'Pemasukan', 'Penghimpunan KOIN NU', 'RW.021', 'Rizky Fauzi', 1872100, 'Penghimpunan KOIN NU RW.021'],
        ];

        foreach ($transactions as $data) {
            $transactionDate = $data[0];
            $type = $data[1] == 'Pemasukan' ? 'income' : 'expense';
            $categoryName = $data[2];
            $regionName = $data[3];
            $volunteerName = $data[4];
            $amount = $data[5];
            $description = $data[6];

            // Resolve Income Type
            $incomeTypeId = null;
            if ($type === 'income') {
                $incomeType = IncomeType::firstOrCreate(
                    ['name' => $categoryName],
                    ['code' => Str::slug($categoryName)]
                );
                $incomeTypeId = $incomeType->id;
            }

            // Resolve Region
            $regionId = null;
            if ($regionName && $regionName !== 'Personal') {
                $region = Region::firstOrCreate(
                    ['name' => $regionName],
                    ['code' => Str::slug($regionName)]
                );
                $regionId = $region->id;
            }

            // Resolve Volunteer
            $volunteerId = null;
            if ($volunteerName) {
                $volunteer = Volunteer::where('name', $volunteerName)->first();
                if (!$volunteer) {
                    // If Region ID is null (e.g. Personal), create a placeholder region 'Global' or 'Personal'
                    if (!$regionId) {
                        $personalRegion = Region::firstOrCreate(
                            ['name' => 'Personal'],
                            ['code' => 'personal']
                        );
                        $regionId = $personalRegion->id;
                    }

                    $volunteer = Volunteer::create([
                        'name' => $volunteerName,
                        'region_id' => $regionId
                    ]);
                }
                $volunteerId = $volunteer->id;
            }

            Transaction::updateOrCreate(
                [
                    'transaction_date' => $transactionDate,
                    'type' => $type,
                    'amount' => $amount,
                    'description' => $description,
                ],
                [
                    'income_type_id' => $incomeTypeId,
                    'expense_type_id' => null,
                    'region_id' => $regionId,
                    'volunteer_id' => $volunteerId,
                    'user_id' => 1,
                ]
            );
        }
    }
}
