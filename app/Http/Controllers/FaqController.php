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

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'urutan'     => 'nullable|integer|min:0',
        ]);

        Faq::create($validated);

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
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
