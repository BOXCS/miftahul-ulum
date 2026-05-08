<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $faqs = Faq::orderBy('urutan')->get()->toArray();

        $kategoris = array_unique(array_column($faqs, 'kategori'));
        sort($kategoris);

        $grouped = [];
        foreach ($faqs as $faq) {
            $grouped[$faq['kategori']][] = $faq;
        }

        return view('faqs.index', compact('faqs', 'kategoris', 'grouped'));
    }

    public function create(): \Illuminate\View\View
    {
        $kategoris = Faq::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->toArray();

        // Auto next position = max urutan + 1
        $nextUrutan = (Faq::max('urutan') ?? 0) + 1;

        return view('faqs.create', compact('kategoris', 'nextUrutan'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'urutan'     => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Resolve urutan conflict: if the chosen position already exists, shift others down
        $urutan = $validated['urutan'] ?? (Faq::max('urutan') + 1);
        $validated['urutan'] = $urutan;

        if (Faq::where('urutan', $urutan)->exists()) {
            // Shift all FAQs with urutan >= chosen value down by 1
            Faq::where('urutan', '>=', $urutan)->orderBy('urutan', 'desc')->each(function ($faq) {
                $faq->update(['urutan' => $faq->urutan + 1]);
            });
        }

        Faq::create($validated);

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $faq = Faq::findOrFail($id);

        $kategoris = Faq::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->toArray();

        return view('faqs.edit', compact('faq', 'kategoris'));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'urutan'     => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Resolve conflict only if urutan changed
        $newUrutan = $validated['urutan'] ?? $faq->urutan;
        if ($newUrutan != $faq->urutan && Faq::where('urutan', $newUrutan)->where('id', '!=', $id)->exists()) {
            Faq::where('urutan', '>=', $newUrutan)
                ->where('id', '!=', $id)
                ->orderBy('urutan', 'desc')
                ->each(function ($f) {
                    $f->update(['urutan' => $f->urutan + 1]);
                });
        }
        $validated['urutan'] = $newUrutan;

        $faq->update($validated);

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }
}
