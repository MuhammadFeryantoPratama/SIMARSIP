<?php

namespace Tests\Feature;

use App\Models\DataArsip;
use App\Models\HakAkses;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('redirects unauthenticated users away from protected archive pages', function (): void {
    $this->get(route('staff.arsip'))
        ->assertRedirect(route('login'));
});

it('allows staff to upload an archive for approval', function (): void {
    Storage::fake('local');
    $role = Role::create(['name' => 'Staff', 'description' => 'Staff']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $response = $this->actingAs($user)->post(route('staff.archives.upload'), [
        'document' => UploadedFile::fake()->create('surat-keputusan.pdf', 100, 'application/pdf'),
    ]);

    $response->assertRedirect();
    expect(DataArsip::count())->toBe(1);
    expect(DataArsip::first()->status)->toBe('menunggu');
    Storage::disk('local')->assertExists(DataArsip::first()->document_path);
});

it('allows only pimpinan to approve an archive', function (): void {
    $staffRole = Role::create(['name' => 'Staff', 'description' => 'Staff']);
    $pimpinanRole = Role::create(['name' => 'Pimpinan', 'description' => 'Pimpinan']);
    $staff = User::factory()->create(['role_id' => $staffRole->id]);
    $pimpinan = User::factory()->create(['role_id' => $pimpinanRole->id]);
    $archive = DataArsip::create(['document_path' => 'archives/test.pdf']);

    $this->actingAs($staff)
        ->patch(route('pimpinan.archives.approve', $archive))
        ->assertForbidden();

    $this->actingAs($pimpinan)
        ->patch(route('pimpinan.archives.approve', $archive))
        ->assertRedirect();

    expect($archive->fresh()->status)->toBe('disetujui');
});

it('logs a user in with either email or username', function (): void {
    $role = Role::create(['name' => 'Staff', 'description' => 'Staff']);
    $user = User::factory()->create([
        'username' => 'pegawai-001',
        'role_id' => $role->id,
        'password' => 'password',
    ]);

    $this->post(route('login'), [
        'email' => $user->username,
        'password' => 'password',
    ])->assertRedirect(route('staff.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('persists role permission changes for administrators', function (): void {
    $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);
    $role = Role::create(['name' => 'Custom', 'description' => 'Custom']);

    $this->actingAs($admin)
        ->patchJson(route('admin.roles.permissions.update', $role), [
            'permissions' => ['arsip.view', 'arsip.export', 'arsip.view'],
        ])
        ->assertOk();

    expect(HakAkses::query()->where('role_id', $role->id)->pluck('izin')->all())
        ->toBe(['arsip.view', 'arsip.export']);
});
