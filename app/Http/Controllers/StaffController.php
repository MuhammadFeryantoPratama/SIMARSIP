<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataArsip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard(): View
    {
        $archives = DataArsip::query()->latest()->limit(5)->get();

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
            'title' => $validated['title'] ?? $validated['document']->getClientOriginalName(),
            'document_path' => $validated['document']->store('archives'),
            'category_id' => $validated['category_id'] ?? Category::query()->orderBy('id')->value('id'),
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Berkas berhasil diunggah dan menunggu persetujuan.');
    }
}
