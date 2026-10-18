<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataArsip;
use App\Models\HakAkses;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $archives = DataArsip::query()->get(['document_path', 'created_at']);
        $storageUsageBytes = $archives->sum(function (DataArsip $archive): int {
            $path = storage_path('app/private/'.$archive->document_path);

            return is_file($path) ? (int) filesize($path) : 0;
        });

        return view('admin.dashboard', [
            'userCount' => User::query()->count(),
            'archiveCount' => DataArsip::query()->count(),
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'newArchiveCount' => $archives->where('created_at', '>=', now()->startOfDay())->count(),
            'activeUserCount' => User::query()->where('updated_at', '>=', now()->startOfDay())->count(),
            'storageUsageBytes' => $storageUsageBytes,
            'users' => User::query()
                ->with('role')
                ->latest()
                ->paginate(5)
                ->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function roles(): View
    {
        $roles = Role::query()->withCount('users')->with('permissions')->orderBy('name')->get();

        return view('admin.roles', [
            'roles' => $roles,
            'roleRows' => $roles->mapWithKeys(fn (Role $role): array => [
                strtolower($role->name) => [
                    'id' => $role->id,
                    'title' => $role->name,
                    'description' => $role->description,
                    'users' => $role->users_count.' Personil',
                    'permissions' => $role->permissions->pluck('izin')->values(),
                ],
            ]),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function storeArchive(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,zip,xls,xlsx', 'max:51200'],
            'title' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['nullable', 'in:menunggu,disetujui,ditolak'],
        ]);

        $path = $this->storeDocument($validated['document']);
        DataArsip::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'] ?? $validated['document']->getClientOriginalName(),
            'document_path' => $path,
            'category_id' => $validated['category_id'] ?? Category::query()->orderBy('id')->value('id'),
            'status' => $validated['status'] ?? 'menunggu',
        ]);

        return back()->with('success', 'Arsip berhasil diunggah.');
    }

    public function updateArchive(Request $request, DataArsip $dataArsip): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,disetujui,ditolak'],
        ]);

        $dataArsip->update($validated);

        return back()->with('success', 'Status arsip berhasil diperbarui.');
    }

    public function destroyArchive(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        Storage::disk('local')->delete($dataArsip->document_path);
        $dataArsip->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Arsip berhasil dihapus.']);
        }

        return back()->with('success', 'Arsip berhasil dihapus.');
    }

    public function downloadArchive(DataArsip $dataArsip): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($dataArsip->document_path), 404, 'Berkas arsip tidak ditemukan.');

        return Storage::disk('local')->download($dataArsip->document_path);
    }

    public function exportArchives(): StreamedResponse
    {
        $archives = DataArsip::query()->latest()->get();

        return response()->streamDownload(function () use ($archives): void {
            $output = fopen('php://output', 'wb');
            fputcsv($output, ['ID', 'Nama Dokumen', 'Status', 'Tanggal Arsip']);

            foreach ($archives as $archive) {
                fputcsv($output, [
                    $archive->id,
                    $archive->document_name,
                    $archive->status,
                    $archive->created_at->toDateTimeString(),
                ]);
            }

            fclose($output);
        }, 'simarsip-arsip.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'preset' => ['nullable', 'in:staff,auditor,pimpinan,minimal'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $permissions = [
            'staff' => ['arsip.view', 'arsip.create', 'arsip.upload'],
            'auditor' => ['arsip.view', 'arsip.audit', 'arsip.export'],
            'pimpinan' => ['arsip.view', 'arsip.approve', 'arsip.reject'],
            'minimal' => ['arsip.view'],
        ];

        foreach ($permissions[$validated['preset'] ?? 'minimal'] as $permission) {
            HakAkses::create(['role_id' => $role->id, 'izin' => $permission]);
        }

        return back()->with('success', 'Peran berhasil dibuat.');
    }

    public function updateRolePermissions(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', 'max:100'],
        ]);

        $role->permissions()->delete();
        $role->permissions()->createMany(
            collect($validated['permissions'])
                ->unique()
                ->map(fn (string $permission): array => ['izin' => $permission])
                ->all(),
        );

        return response()->json(['message' => 'Hak akses berhasil diperbarui.']);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:users,username'],
            'role' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $role = Role::query()->firstOrCreate(
            ['name' => $validated['role']],
            ['description' => 'Peran instansi.'],
        );
        $email = $validated['email'] ?? $validated['nip'].'@internal.simarsip.go.id';

        User::create([
            'username' => $validated['nip'],
            'name' => $validated['name'],
            'email' => $email,
            'role_id' => $role->id,
            'password' => Hash::make($validated['password'] ?? $validated['nip']),
        ]);

        return back()->with('success', 'Akun berhasil dibuat.');
    }

    public function updateUserPassword(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update(['password' => $validated['password']]);

        return response()->json(['message' => 'Kata sandi berhasil direset.']);
    }

    public function destroyUser(Request $request, User $user): RedirectResponse|JsonResponse
    {
        abort_if(auth()->id() === $user->id, 422, 'Akun yang sedang digunakan tidak dapat dihapus.');

        $user->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Akun berhasil dihapus.']);
        }

        return back()->with('success', 'Akun berhasil dihapus.');
    }

    private function storeDocument(UploadedFile $document): string
    {
        return $document->store('archives');
    }

    private function uniqueUsername(string $email): string
    {
        $base = str($email)->before('@')->slug()->value() ?: 'user';
        $username = $base;
        $suffix = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.'-'.$suffix;
            $suffix++;
        }

        return $username;
    }
}
