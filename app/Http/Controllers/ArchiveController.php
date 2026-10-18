<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataArsip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $role = strtolower((string) $user->role?->name);
        $isAdmin = str_contains($role, 'admin');
        $isPimpinan = str_contains($role, 'pimpin') || str_contains($role, 'validator');
        $uploadRoute = $isAdmin
            ? route('admin.archives.store')
            : (str_contains($role, 'staff') || str_contains($role, 'editor')
                ? route('staff.archives.upload')
                : null);

        $archives = DataArsip::query()
            ->with(['category', 'user'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', $search)
                        ->orWhere('document_path', 'like', $search)
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', $search));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('arsips', [
            'layout' => 'layouts.app',
            'uploadRoute' => $uploadRoute,
            'canUpload' => $uploadRoute !== null,
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'archives' => $archives,
            'archiveRows' => $archives->getCollection()->map(fn (DataArsip $archive): array => [
                'id' => $archive->id,
                'name' => $archive->document_name,
                'status' => $archive->status,
                'category' => $archive->category?->name ?? 'Tanpa Kategori',
                'date' => $archive->created_at->format('d M Y'),
                'uploader' => $archive->user?->name ?? 'Sistem',
                'canDelete' => $isAdmin || $isPimpinan || ($archive->user_id !== null && $archive->user_id === $user->id),
                'canEdit' => $isAdmin || $isPimpinan || ($archive->user_id !== null && $archive->user_id === $user->id),
                'canChangeStatus' => $isAdmin || $isPimpinan,
                'categoryId' => $archive->category_id,
                'downloadUrl' => route('archives.download', $archive),
                'deleteUrl' => route('archives.destroy', $archive),
            ])->values(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $role = strtolower((string) $user->role?->name);
        $isAdmin = str_contains($role, 'admin');
        $isPimpinan = str_contains($role, 'pimpin') || str_contains($role, 'validator');
        $isOwner = $dataArsip->user_id !== null && $dataArsip->user_id === $user->id;

        abort_unless($isAdmin || $isPimpinan || $isOwner, 403, 'Anda tidak memiliki hak untuk mengubah arsip ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['sometimes', 'required', 'in:menunggu,disetujui,ditolak'],
        ]);

        if (! $isAdmin && ! $isPimpinan) {
            unset($validated['status']);
        }

        $dataArsip->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Metadata arsip berhasil diperbarui.']);
        }

        return back()->with('success', 'Metadata arsip berhasil diperbarui.');
    }

    public function destroy(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $role = strtolower((string) $user->role?->name);
        $isAdmin = str_contains($role, 'admin');
        $isPimpinan = str_contains($role, 'pimpin') || str_contains($role, 'validator');
        $isOwner = $dataArsip->user_id !== null && $dataArsip->user_id === $user->id;

        abort_unless($isAdmin || $isPimpinan || $isOwner, 403, 'Anda tidak memiliki hak untuk menghapus arsip ini.');

        Storage::disk('local')->delete($dataArsip->document_path);
        $dataArsip->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Arsip berhasil dihapus.']);
        }

        return back()->with('success', 'Arsip berhasil dihapus.');
    }
}
