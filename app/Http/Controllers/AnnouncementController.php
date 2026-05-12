<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private array $kategoriList = [
        'umum'     => ['label' => 'Umum',     'color' => 'blue'],
        'kegiatan' => ['label' => 'Kegiatan', 'color' => 'green'],
        'akademik' => ['label' => 'Akademik', 'color' => 'purple'],
        'darurat'  => ['label' => 'Darurat',  'color' => 'red'],
    ];

    public function index(): \Illuminate\View\View
    {
        $announcements = Announcement::orderByDesc('published_at')
            ->get()
            ->map(fn($a) => [
                'id'           => $a->id,
                'judul'        => $a->judul,
                'konten'       => $a->konten,
                'kategori'     => $a->kategori,
                'is_published' => $a->is_published,
                'published_at' => $a->published_at?->format('Y-m-d H:i:s'),
                'created_at'   => $a->created_at->format('Y-m-d H:i:s'),
            ])
            ->toArray();

        $kategoriList  = $this->kategoriList;

        $stats = [
            'total'     => count($announcements),
            'published' => collect($announcements)->where('is_published', true)->count(),
            'draft'     => collect($announcements)->where('is_published', false)->count(),
            'darurat'   => collect($announcements)->where('kategori', 'darurat')->where('is_published', true)->count(),
        ];

        return view('announcements.index', compact('announcements', 'kategoriList', 'stats'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'konten'   => 'required|string',
            'kategori' => 'required|in:umum,kegiatan,akademik,darurat',
        ]);

        $published = $request->has('publish');
        $validated['is_published'] = $published;
        if ($published) {
            $validated['published_at'] = now();
        }

        $announcement = Announcement::create($validated);

        // Realtime: broadcast ke semua mobile client jika published langsung
        if ($announcement->is_published) {
            broadcast(new \App\Events\AnnouncementCreated($announcement));
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
    public function create(): \Illuminate\View\View
    {
        return view('announcements.create');
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $announcement = Announcement::findOrFail($id);
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'konten'   => 'required|string',
            'kategori' => 'required|in:umum,kegiatan,akademik,darurat',
        ]);

        $published = $request->has('publish');
        $validated['is_published'] = $published;

        if ($published && !$announcement->is_published) {
            // Baru dipublish sekarang
            $validated['published_at'] = now();
        } elseif (!$published) {
            // Dikembalikan ke draft
            $validated['published_at'] = null;
        }

        $wasPublished  = (bool) $announcement->is_published;
        $announcement->update($validated);

        // Realtime: broadcast hanya jika baru saja dipublish (transisi draft → published)
        if (!$wasPublished && $announcement->is_published) {
            broadcast(new \App\Events\AnnouncementCreated($announcement->fresh()));
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }
}
