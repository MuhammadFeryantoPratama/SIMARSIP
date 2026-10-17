<?php

namespace App\Http\Controllers;

use App\Models\DataArsip;
use Illuminate\Contracts\View\View;

class AuditorController extends Controller
{
    public function dashboard(): View
    {
        $archives = DataArsip::query()->with('category')->latest()->limit(8)->get();

        return view('auditor.dashboard', [
            'archiveCount' => DataArsip::query()->count(),
            'approvedArchiveCount' => DataArsip::query()->where('status', 'disetujui')->count(),
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'rejectedArchiveCount' => DataArsip::query()->where('status', 'ditolak')->count(),
            'recentArchives' => $archives,
            'recentArchiveRows' => $archives->map(fn (DataArsip $archive): array => [
                'id' => $archive->id,
                'name' => $archive->document_name,
                'status' => $archive->status,
                'date' => $archive->created_at->format('d M Y H:i'),
                'url' => route('archives.download', $archive),
            ])->values(),
        ]);
    }
}
