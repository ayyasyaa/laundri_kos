<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('activeTenant')->orderBy('room_number')->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:kosong,terisi,maintenance',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', "Kamar {$validated['room_number']} berhasil ditambahkan.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:kosong,terisi,maintenance',
        ]);

        // If status changes to kosong, check out any active tenant
        if ($validated['status'] === 'kosong' && $room->status === 'terisi') {
            $room->activeTenant()->update(['status' => 'selesai']);
        }

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', "Kamar {$room->room_number} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if (auth()->user()->isStaff()) {
            return redirect()->back()->with('error', 'Akses Ditolak: Staff tidak diperbolehkan menghapus data master.');
        }

        if ($room->status === 'terisi') {
            return redirect()->route('rooms.index')->with('error', 'Gagal Hapus: Kamar masih terisi oleh penghuni aktif.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', "Kamar {$room->room_number} berhasil dihapus.");
    }
}
