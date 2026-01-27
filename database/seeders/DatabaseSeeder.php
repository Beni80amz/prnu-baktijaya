<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SettingsSeeder::class,
            ProfileDataSeeder::class,
            MasterDataSeeder::class,
            MosqueSeeder::class,
            ContentSeeder::class,
            KasDigitalSeeder::class,
            ManualTransactionSeeder::class,
            ManualExpenseSeeder::class,
        ]);
    }
}
