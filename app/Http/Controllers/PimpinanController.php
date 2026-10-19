<?php

namespace App\Http\Controllers;

use App\Models\DataArsip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function dashboard(): View
    {
        $today = now()->startOfDay();

        return view('pimpinan.dashboard', [
            'archiveCount' => DataArsip::query()->count(),
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'todayArchiveCount' => DataArsip::query()->where('created_at', '>=', $today)->count(),
            'approvedTodayCount' => DataArsip::query()->where('status', 'disetujui')->where('updated_at', '>=', $today)->count(),
            'rejectedArchiveCount' => DataArsip::query()->where('status', 'ditolak')->count(),
            'recentArchives' => DataArsip::query()->with('category')->latest()->limit(5)->get(),
            'recentArchiveRows' => DataArsip::query()->where('status', 'menunggu')->latest()->limit(5)->get()->map(fn (DataArsip $archive): array => [
                'id' => $archive->id,
                'name' => $archive->document_name,
                'date' => $archive->created_at->diffForHumans(),
            ])->values(),
        ]);
    }

    public function approvals(): View
    {
        return view('pimpinan.persetujuan', [
            'archives' => DataArsip::query()->with('category')->where('status', 'menunggu')->latest()->get(),
            'pendingArchiveCount' => DataArsip::query()->where('status', 'menunggu')->count(),
            'approvedTodayCount' => DataArsip::query()->where('status', 'disetujui')->where('updated_at', '>=', now()->startOfDay())->count(),
            'rejectedArchiveCount' => DataArsip::query()->where('status', 'ditolak')->count(),
        ]);
    }

    public function approve(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        $dataArsip->update(['status' => 'disetujui']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Arsip berhasil disetujui.',
            ]);
        }

        return back()->with('success', 'Arsip berhasil disetujui.');
    }

    public function reject(Request $request, DataArsip $dataArsip): RedirectResponse|JsonResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:1000']]);
        $dataArsip->update(['status' => 'ditolak']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Arsip berhasil ditolak.',
            ]);
        }

        return back()->with('success', 'Arsip berhasil ditolak.');
    }
}
