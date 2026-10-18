<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataArsip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $archives = DataArsip::query()->with(['category', 'user'])->latest()->limit(5)->get();

        return view('staff.dashboard', [
            'archiveCount' => DataArsip::query()->count(),
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'approvedArchiveCount' => DataArsip::query()->where('status', 'disetujui')->count(),
            'recentArchives' => $archives,
            'categories' => Category::query()->orderBy('name')->get(),
            'recentArchiveRows' => $archives->map(fn (DataArsip $archive): array => [
                'id' => $archive->id,
                'name' => $archive->document_name,
                'status' => $archive->status,
                'category' => $archive->category?->name ?? 'Tanpa Kategori',
                'date' => $archive->created_at->format('d M Y H:i'),
                'url' => route('archives.download', $archive),
                'deleteUrl' => route('archives.destroy', $archive),
                'canDelete' => $archive->user_id !== null && $archive->user_id === $user->id,
            ])->values(),
        ]);
    }

    public function upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,zip,xls,xlsx', 'max:51200'],
            'title' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        DataArsip::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'] ?? $validated['document']->getClientOriginalName(),
            'document_path' => $validated['document']->store('archives'),
            'category_id' => $validated['category_id'] ?? Category::query()->orderBy('id')->value('id'),
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Berkas berhasil diunggah dan menunggu persetujuan.');
    }

    public function destroy(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        abort_unless($dataArsip->user_id === $request->user()->id, 403, 'Anda hanya dapat menghapus arsip yang Anda unggah sendiri.');

        Storage::disk('local')->delete($dataArsip->document_path);
        $dataArsip->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Arsip berhasil dihapus.']);
        }

        return back()->with('success', 'Arsip berhasil dihapus.');
    }
}
