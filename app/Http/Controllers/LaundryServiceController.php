<?php

namespace App\Http\Controllers;

use App\Models\LaundryService;
use Illuminate\Http\Request;

class LaundryServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = LaundryService::latest()->get();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:laundry_services,name',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LaundryService::create($validated);

        return redirect()->route('services.index')->with('success', 'Layanan laundry berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaundryService $service)
    {
        return redirect()->route('services.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaundryService $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaundryService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:laundry_services,name,' . $service->id,
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Layanan laundry berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaundryService $service)
    {
        // Instead of hard deleting (which breaks order history due to foreign keys), toggle active status or deactivate
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('services.index')->with('success', "Layanan laundry berhasil {$status}.");
    }
}
