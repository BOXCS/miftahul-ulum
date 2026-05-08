<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Santri;
use App\Models\Staff;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ortu=OrangTua::with('santri')->get();
        $santri=Santri::with('ortu')->get();
        $staff=Staff::get();
        return view('management', compact('ortu', 'santri', 'staff'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}