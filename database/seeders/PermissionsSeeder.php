<?php
// database/seeders/PermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard_access',

            // Branches
            'branches_access',
            'branches_create',
            'branches_edit',
            'branches_delete',

            // Services
            'services_access',
            'services_create',
            'services_edit',
            'services_delete',

            // Doctors
            'doctors_access',
            'doctors_create',
            'doctors_edit',
            'doctors_delete',

            // Appointments
            'appointments_access',
            'appointments_create',
            'appointments_edit',
            'appointments_delete',

            // Prescriptions
            'prescriptions_access',
            'prescriptions_create',
            'prescriptions_edit',
            'prescriptions_delete',

            // Pages
            'pages_access',
            'pages_create',
            'pages_edit',
            'pages_delete',

            // Testimonials
            'testimonials_access',
            'testimonials_create',
            'testimonials_edit',
            'testimonials_delete',

            // Settings
            'settings_access',
            'settings_edit',

            // Users
            'users_access',
            'users_create',
            'users_edit',
            'users_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $doctorRole = Role::create(['name' => 'doctor']);

        // Super Admin has all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin has all permissions except user management
        $adminRole->givePermissionTo(
            Permission::whereNotIn('name', [
                'users_access',
                'users_create',
                'users_edit',
                'users_delete'
            ])->get()
        );

        // Doctor has limited permissions
        $doctorRole->givePermissionTo([
            'dashboard_access',
            'appointments_access',
            'appointments_edit',
            'prescriptions_access',
            'prescriptions_create',
            'prescriptions_edit',
        ]);

        // Assign roles to default admin user
        $admin = User::where('email', 'admin@misrikhandental.com')->first();
        if ($admin) {
            $admin->assignRole('super_admin');
        }
    }
}