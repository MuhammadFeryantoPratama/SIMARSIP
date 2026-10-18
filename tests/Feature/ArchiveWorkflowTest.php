<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DataArsip;
use App\Models\HakAkses;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
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
    $category = Category::create(['name' => 'Legal']);

    $response = $this->actingAs($user)->post(route('staff.archives.upload'), [
        'document' => UploadedFile::fake()->create('surat-keputusan.pdf', 100, 'application/pdf'),
        'category_id' => $category->id,
        'status' => 'disetujui',
    ]);

    $response->assertRedirect();
    expect(DataArsip::count())->toBe(1);
    expect(DataArsip::first()->status)->toBe('menunggu');
    expect(DataArsip::first()->category_id)->toBe($category->id);
    expect(DataArsip::first()->user_id)->toBe($user->id);
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

it('renders public pages and redirects each role to its dashboard', function (): void {
    $this->get(route('home'))->assertOk()->assertViewIs('landing.index');
    $this->get(route('login'))->assertOk()->assertViewIs('auth.login');
    $this->get(route('register'))->assertOk()->assertViewIs('auth.register');
    $this->get(route('subscriptions'))->assertOk()->assertViewIs('subscriptions.index');

    $roles = [
        'Admin' => 'admin.dashboard',
        'Pimpinan' => 'pimpinan.dashboard',
        'Auditor' => 'auditor.dashboard',
        'Staff' => 'staff.dashboard',
    ];

    foreach ($roles as $roleName => $dashboardRoute) {
        $role = Role::firstOrCreate(['name' => $roleName], ['description' => $roleName]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route($dashboardRoute));

        $this->post(route('logout'))->assertRedirect(route('login'));
    }
});

it('registers a staff user with a unique username', function (): void {
    $response = $this->post(route('register'), [
        'name' => 'New Employee',
        'institution' => 'Government Office',
        'email' => 'new.employee@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => '1',
    ]);

    $response->assertRedirect(route('staff.dashboard'));
    $user = User::query()->where('email', 'new.employee@example.com')->firstOrFail();

    expect($user->username)->toBe('newemployee');
    expect($user->role->name)->toBe('Staff');
    expect(Hash::check('password', $user->password))->toBeTrue();
});

it('rejects invalid credentials and invalid archive uploads', function (): void {
    $role = Role::create(['name' => 'Staff', 'description' => 'Staff']);
    $user = User::factory()->create(['role_id' => $role->id, 'password' => 'password']);

    $this->from(route('login'))
        ->post(route('login'), ['email' => $user->email, 'password' => 'wrong-password'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email')
        ->assertSessionHas('error');

    Storage::fake('local');
    $this->actingAs($user)
        ->post(route('staff.archives.upload'), [
            'document' => UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream'),
        ])
        ->assertSessionHasErrors('document');

    expect(DataArsip::count())->toBe(0);
});

it('allows administrators to create, update, download, export, and delete archives', function (): void {
    Storage::fake('local');
    $role = Role::create(['name' => 'Admin', 'description' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $role->id]);
    $category = Category::create(['name' => 'Keuangan']);

    $response = $this->actingAs($admin)->post(route('admin.archives.store'), [
        'document' => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
        'title' => 'Laporan Tahunan',
        'category_id' => $category->id,
        'status' => 'disetujui',
    ]);

    $response->assertRedirect()->assertSessionHas('success');
    $archive = DataArsip::query()->firstOrFail();
    expect($archive->status)->toBe('disetujui');
    Storage::disk('local')->assertExists($archive->document_path);

    $this->actingAs($admin)
        ->patch(route('admin.archives.update', $archive), ['status' => 'ditolak'])
        ->assertRedirect();
    expect($archive->fresh()->status)->toBe('ditolak');

    $updatedCategory = Category::create(['name' => 'Legal']);
    $this->actingAs($admin)
        ->patchJson(route('archives.update', $archive), [
            'title' => 'Laporan Tahunan Diperbarui',
            'category_id' => $updatedCategory->id,
            'status' => 'disetujui',
        ])
        ->assertOk();
    expect($archive->fresh()->only(['title', 'category_id', 'status']))
        ->toMatchArray([
            'title' => 'Laporan Tahunan Diperbarui',
            'category_id' => $updatedCategory->id,
            'status' => 'disetujui',
        ]);

    $this->actingAs($admin)
        ->get(route('archives.download', $archive))
        ->assertOk()
        ->assertHeader('content-disposition');

    $this->actingAs($admin)
        ->get(route('archives.export'))
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $this->actingAs($admin)
        ->delete(route('admin.archives.destroy', $archive))
        ->assertRedirect();
    expect(DataArsip::count())->toBe(0);
    Storage::disk('local')->assertMissing($archive->document_path);
});

it('allows pimpinan to reject pending archives and blocks other roles', function (): void {
    $staffRole = Role::create(['name' => 'Staff', 'description' => 'Staff']);
    $pimpinanRole = Role::create(['name' => 'Pimpinan', 'description' => 'Pimpinan']);
    $staff = User::factory()->create(['role_id' => $staffRole->id]);
    $pimpinan = User::factory()->create(['role_id' => $pimpinanRole->id]);
    $archive = DataArsip::create(['document_path' => 'archives/pending.pdf', 'status' => 'menunggu']);

    $this->actingAs($staff)
        ->patch(route('pimpinan.archives.reject', $archive), ['reason' => 'Tidak lengkap'])
        ->assertForbidden();

    $this->actingAs($pimpinan)
        ->patch(route('pimpinan.archives.reject', $archive), ['reason' => 'Tidak lengkap'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($archive->fresh()->status)->toBe('ditolak');
});

it('allows administrators to manage roles, users, and passwords', function (): void {
    $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    $this->actingAs($admin)
        ->post(route('admin.roles.store'), [
            'name' => 'Reviewer',
            'description' => 'Archive reviewer',
            'preset' => 'auditor',
        ])
        ->assertRedirect();

    $reviewer = Role::query()->where('name', 'Reviewer')->firstOrFail();
    expect($reviewer->permissions()->pluck('izin')->all())
        ->toBe(['arsip.view', 'arsip.audit', 'arsip.export']);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Created User',
            'nip' => 'NIP-001',
            'role' => 'Reviewer',
            'email' => 'created@example.com',
            'password' => 'password',
        ])
        ->assertRedirect();

    $createdUser = User::query()->where('username', 'NIP-001')->firstOrFail();
    $this->actingAs($admin)
        ->patchJson(route('admin.users.password.update', $createdUser), ['password' => 'new-password'])
        ->assertOk();
    expect(Hash::check('new-password', $createdUser->fresh()->password))->toBeTrue();
    $this->post(route('logout'));
    $this->post(route('login'), [
        'email' => $createdUser->username,
        'password' => 'new-password',
    ])->assertRedirect(route('staff.dashboard'));

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $createdUser))
        ->assertRedirect();
    expect(User::query()->whereKey($createdUser->id)->exists())->toBeFalse();
});

it('paginates users on the admin dashboard', function (): void {
    $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin']);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);
    User::factory()->count(6)->create(['role_id' => $adminRole->id]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard', ['page' => 2]))
        ->assertOk()
        ->assertViewHas('users', fn ($users): bool => $users->currentPage() === 2
            && $users->perPage() === 5
            && $users->total() === 7);
});

it('shows the pending archive count and enum statuses on the archive page', function (): void {
    $pimpinanRole = Role::create(['name' => 'Pimpinan', 'description' => 'Pimpinan']);
    $pimpinan = User::factory()->create(['role_id' => $pimpinanRole->id]);
    DataArsip::create(['document_path' => 'archives/pending.pdf', 'status' => 'menunggu']);
    DataArsip::create(['document_path' => 'archives/approved.pdf', 'status' => 'disetujui']);

    $this->actingAs($pimpinan)
        ->get(route('pimpinan.arsip'))
        ->assertOk()
        ->assertViewHas('pendingArchiveCount', 1)
        ->assertSee('value="menunggu"', false)
        ->assertSee('value="disetujui"', false)
        ->assertSee('value="ditolak"', false);
});

it('protects role-specific pages and renders the shared archive view for allowed roles', function (): void {
    $roles = [
        ['Staff', 'staff.arsip'],
        ['Pimpinan', 'pimpinan.arsip'],
        ['Auditor', 'auditor.arsip'],
        ['Admin', 'admin.arsip'],
    ];

    foreach ($roles as [$roleName, $routeName]) {
        $role = Role::firstOrCreate(['name' => $roleName], ['description' => $roleName]);
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($user)
            ->get(route($routeName))
            ->assertOk()
            ->assertViewIs('arsips')
            ->assertViewHas('layout', 'layouts.app');
    }

    $staffRole = Role::query()->where('name', 'Staff')->firstOrFail();
    $staff = User::factory()->create(['role_id' => $staffRole->id]);
    $this->actingAs($staff)->get(route('admin.dashboard'))->assertForbidden();
});

it('allows staff to delete archives they uploaded', function (): void {
    Storage::fake('local');
    $staffRole = Role::firstOrCreate(['name' => 'Staff'], ['description' => 'Staff']);
    $staff = User::factory()->create(['role_id' => $staffRole->id]);

    Storage::disk('local')->put('archives/my-file.pdf', 'content');
    $archive = DataArsip::create([
        'user_id' => $staff->id,
        'title' => 'My File',
        'document_path' => 'archives/my-file.pdf',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($staff)
        ->delete(route('archives.destroy', $archive));

    $response->assertRedirect();
    expect(DataArsip::whereKey($archive->id)->exists())->toBeFalse();
    Storage::disk('local')->assertMissing('archives/my-file.pdf');
});

it('allows staff to delete archives via staff route with json response', function (): void {
    Storage::fake('local');
    $staffRole = Role::firstOrCreate(['name' => 'Staff'], ['description' => 'Staff']);
    $staff = User::factory()->create(['role_id' => $staffRole->id]);

    Storage::disk('local')->put('archives/my-file-json.pdf', 'content');
    $archive = DataArsip::create([
        'user_id' => $staff->id,
        'title' => 'My File JSON',
        'document_path' => 'archives/my-file-json.pdf',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($staff)
        ->deleteJson(route('staff.archives.destroy', $archive));

    $response->assertOk()
        ->assertJson(['message' => 'Arsip berhasil dihapus.']);
    expect(DataArsip::whereKey($archive->id)->exists())->toBeFalse();
    Storage::disk('local')->assertMissing('archives/my-file-json.pdf');
});

it('forbids staff from deleting archives uploaded by others', function (): void {
    Storage::fake('local');
    $staffRole = Role::firstOrCreate(['name' => 'Staff'], ['description' => 'Staff']);
    $staff1 = User::factory()->create(['role_id' => $staffRole->id]);
    $staff2 = User::factory()->create(['role_id' => $staffRole->id]);

    Storage::disk('local')->put('archives/other-file.pdf', 'content');
    $archive = DataArsip::create([
        'user_id' => $staff1->id,
        'title' => 'Other File',
        'document_path' => 'archives/other-file.pdf',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($staff2)
        ->delete(route('archives.destroy', $archive));

    $response->assertForbidden();
    expect(DataArsip::whereKey($archive->id)->exists())->toBeTrue();
    Storage::disk('local')->assertExists('archives/other-file.pdf');
});

it('allows admin to delete archives uploaded by any user', function (): void {
    Storage::fake('local');
    $staffRole = Role::firstOrCreate(['name' => 'Staff'], ['description' => 'Staff']);
    $adminRole = Role::firstOrCreate(['name' => 'Admin'], ['description' => 'Admin']);
    $staff = User::factory()->create(['role_id' => $staffRole->id]);
    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    Storage::disk('local')->put('archives/staff-doc.pdf', 'content');
    $archive = DataArsip::create([
        'user_id' => $staff->id,
        'title' => 'Staff Doc',
        'document_path' => 'archives/staff-doc.pdf',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($admin)
        ->deleteJson(route('archives.destroy', $archive));

    $response->assertOk()
        ->assertJson(['message' => 'Arsip berhasil dihapus.']);
    expect(DataArsip::whereKey($archive->id)->exists())->toBeFalse();
    Storage::disk('local')->assertMissing('archives/staff-doc.pdf');
});

it('allows pimpinan to delete archives uploaded by staff while forbidding another staff', function (): void {
    Storage::fake('local');
    $staffRole = Role::firstOrCreate(['name' => 'Staff'], ['description' => 'Staff']);
    $pimpinanRole = Role::firstOrCreate(['name' => 'Pimpinan'], ['description' => 'Pimpinan']);

    $staffA = User::factory()->create(['role_id' => $staffRole->id]);
    $staffB = User::factory()->create(['role_id' => $staffRole->id]);
    $pimpinan = User::factory()->create(['role_id' => $pimpinanRole->id]);

    Storage::disk('local')->put('archives/staff-a-doc.pdf', 'content');
    $archive = DataArsip::create([
        'user_id' => $staffA->id,
        'title' => 'Berkas Salah Upload Staff A',
        'document_path' => 'archives/staff-a-doc.pdf',
        'status' => 'menunggu',
    ]);

    // Staff B coba hapus -> DITOLAK (403)
    $this->actingAs($staffB)
        ->delete(route('archives.destroy', $archive))
        ->assertForbidden();
    expect(DataArsip::whereKey($archive->id)->exists())->toBeTrue();

    // Pimpinan hapus -> BERHASIL
    $this->actingAs($pimpinan)
        ->deleteJson(route('archives.destroy', $archive))
        ->assertOk()
        ->assertJson(['message' => 'Arsip berhasil dihapus.']);

    expect(DataArsip::whereKey($archive->id)->exists())->toBeFalse();
    Storage::disk('local')->assertMissing('archives/staff-a-doc.pdf');
});
