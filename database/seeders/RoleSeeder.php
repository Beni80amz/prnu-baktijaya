<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Content management
            'manage_news',
            'manage_articles',
            'manage_gallery',
            'manage_dawuh',
            'manage_sliders',

            // Financial management
            'manage_kas',
            'manage_transactions',
            'export_reports',

            // Service management
            'manage_tanya_kiai',
            'manage_prayer_requests',
            'manage_umkm',
            'manage_mosques',

            // User management
            'manage_users',
            'manage_roles',

            // Settings
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Super Admin role with all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Create Admin Konten role
        $adminKonten = Role::firstOrCreate(['name' => 'admin_konten']);
        $adminKonten->givePermissionTo([
            'manage_news',
            'manage_articles',
            'manage_gallery',
            'manage_dawuh',
            'manage_sliders',
        ]);

        // Create Admin Bendahara role
        $adminBendahara = Role::firstOrCreate(['name' => 'admin_bendahara']);
        $adminBendahara->givePermissionTo([
            'manage_kas',
            'manage_transactions',
            'export_reports',
        ]);

        // Create Admin Layanan role
        $adminLayanan = Role::firstOrCreate(['name' => 'admin_layanan']);
        $adminLayanan->givePermissionTo([
            'manage_tanya_kiai',
            'manage_prayer_requests',
            'manage_umkm',
            'manage_mosques',
        ]);

        // Create Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@prnubaktijaya.or.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdminUser->assignRole('super_admin');
    }
}
