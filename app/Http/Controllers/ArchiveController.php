<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataArsip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request): View
    {
        $role = strtolower((string) $request->user()->role?->name);
        $uploadRoute = str_contains($role, 'admin')
            ? route('admin.archives.store')
            : (str_contains($role, 'staff') || str_contains($role, 'editor')
                ? route('staff.archives.upload')
                : null);

        $archives = DataArsip::query()
            ->with('category')
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
            'archives' => $archives,
            'archiveRows' => $archives->getCollection()->map(fn (DataArsip $archive): array => [
                'id' => $archive->id,
                'name' => $archive->document_name,
                'status' => $archive->status,
                'category' => $archive->category?->name ?? 'Tanpa Kategori',
                'date' => $archive->created_at->format('d M Y'),
                'downloadUrl' => route('archives.download', $archive),
            ])->values(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }
}
