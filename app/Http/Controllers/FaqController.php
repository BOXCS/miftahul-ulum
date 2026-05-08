<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Menampilkan semua FAQ
    public function index()
    {
        $faqs = Faq::all(); // Mengambil semua data FAQ
        return view('faq', compact('faqs')); // Mengirimkan data FAQ ke view
    }

    // Menyimpan atau mengupdate jawaban FAQ
    public function update(Request $request, $id)
    {
        // Validasi untuk memastikan jawaban ada
        $request->validate([
            'answer' => 'required', // Jawaban harus diisi
        ]);

        // Menemukan FAQ berdasarkan ID yang diberikan
        $faq = Faq::findOrFail($id);

        // Memperbarui jawaban FAQ dengan data yang diberikan dari form
        $faq->update([
            'answer' => $request->answer, // Update jawaban
        ]);

        // Setelah berhasil memperbarui jawaban, redirect kembali dengan pesan sukses
        return redirect()->route('faq.index')->with('success', 'Jawaban berhasil diperbarui!');
    }
}
