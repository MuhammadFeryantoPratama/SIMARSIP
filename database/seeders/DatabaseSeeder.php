<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DataArsip;
use App\Models\Role;
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
        foreach (['Keuangan', 'Legal', 'Operasional', 'SDM'] as $categoryName) {
            Category::query()->firstOrCreate(['name' => $categoryName]);
        }
        $defaultCategory = Category::query()->orderBy('id')->first();
        DataArsip::query()->whereNull('category_id')->update(['category_id' => $defaultCategory?->id]);
        DataArsip::query()->whereNull('title')->get()->each(function (DataArsip $archive): void {
            $archive->update(['title' => basename($archive->document_path)]);
        });

        $staffRole = Role::query()->firstOrCreate(
            ['name' => 'Staff'],
            ['description' => 'Pengguna operasional arsip.'],
        );
        $pimpinanRole = Role::query()->firstOrCreate(
            ['name' => 'Pimpinan'],
            ['description' => 'Pengguna dengan hak akses persetujuan arsip.'],
        );
        $auditorRole = Role::query()->firstOrCreate(
            ['name' => 'Auditor'],
            ['description' => 'Pengguna dengan hak akses audit arsip.'],
        );
        $adminRole = Role::query()->firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Pengelola pengguna, peran, dan konfigurasi sistem.'],
        );

        $rolePermissions = [
            $staffRole->id => ['arsip.view', 'arsip.create', 'arsip.upload'],
            $pimpinanRole->id => ['arsip.view', 'arsip.approve', 'arsip.reject', 'arsip.audit', 'arsip.export'],
            $auditorRole->id => ['arsip.view', 'arsip.audit', 'arsip.export'],
            $adminRole->id => [
                'arsip.view', 'arsip.create', 'arsip.upload', 'arsip.edit', 'arsip.delete',
                'arsip.restore', 'arsip.approve', 'arsip.reject', 'arsip.audit', 'arsip.export',
                'users.create', 'roles.edit',
            ],
        ];

        foreach ($rolePermissions as $roleId => $permissions) {
            $role = Role::query()->findOrFail($roleId);
            foreach ($permissions as $permission) {
                $role->permissions()->firstOrCreate(['izin' => $permission]);
            }
        }

        User::query()->updateOrCreate(['email' => 'test@example.com'], [
            'username' => 'test-staff',
            'password' => bcrypt('12345678'),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $staffRole->id,
        ]);
        User::query()->updateOrCreate(['email' => 'pimpinan@example.com'], [
            'username' => 'test-pimpinan',
            'password' => bcrypt('12345678'),
            'name' => 'Test Pimpinan',
            'email' => 'pimpinan@example.com',
            'role_id' => $pimpinanRole->id,
        ]);
        User::query()->updateOrCreate(['email' => 'auditor@example.com'], [
            'username' => 'test-auditor',
            'password' => bcrypt('12345678'),
            'name' => 'Test Auditor',
            'email' => 'auditor@example.com',
            'role_id' => $auditorRole->id,
        ]);
        User::query()->updateOrCreate(['email' => 'admin@example.com'], [
            'username' => 'admin',
            'password' => bcrypt('12345678'),
            'name' => 'Administrator Sistem',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);
    }
}
