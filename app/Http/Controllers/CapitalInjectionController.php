<?php

namespace App\Http\Controllers;

use App\Models\CapitalInjection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CapitalInjectionController extends Controller
{
    /**
     * Menampilkan daftar modal masuk DAN formulir tambah.
     */
    public function index(Request $request)
    {
        $query = CapitalInjection::with('user')->latest('date');

        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('date', '>=', Carbon::parse($request->date_from));
        });
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('date', '<=', Carbon::parse($request->date_to));
        });

        $capitalInjections = $query->paginate(20)->withQueryString();

        return view('capital_injections.index', [
            'capitalInjections' => $capitalInjections
        ]);
    }

    /**
     * Menyimpan modal masuk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $dataToSave = array_merge($validated, [
            'user_id' => Auth::id(),
        ]);

        CapitalInjection::create($dataToSave);

        return redirect()->route('capital-injections.index')
            ->with('success', 'Catatan modal berhasil disimpan.');
    }

    /**
     * Menampilkan formulir untuk mengedit modal masuk.
     */
    public function edit(CapitalInjection $capitalInjection)
    {
        return view('capital_injections.edit', [
            'capital' => $capitalInjection
        ]);
    }

    /**
     * Memperbarui modal masuk di database.
     */
    public function update(Request $request, CapitalInjection $capitalInjection)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $capitalInjection->update($validated);

        return redirect()->route('capital-injections.index')
            ->with('success', 'Catatan modal berhasil diperbarui.');
    }

    /**
     * Menghapus modal masuk dari database.
     */
    public function destroy(CapitalInjection $capitalInjection)
    {
        $capitalInjection->delete();

        return redirect()->route('capital-injections.index')
            ->with('success', 'Catatan modal berhasil dihapus.');
    }
    /**
     * Menampilkan halaman cetak modal masuk.
     */
    public function print(Request $request)
    {
        // --- LOGIKA FILTER ---
        $query = CapitalInjection::with('user')->latest('date');

        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('date', '>=', Carbon::parse($request->date_from));
        });
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('date', '<=', Carbon::parse($request->date_to));
        });
        // --- AKHIR LOGIKA FILTER ---

        $capitalInjections = $query->get();

        return view('capital_injections.print', [
            'capitalInjections' => $capitalInjections,
            'filters' => $request->all(),
            'printDate' => now()->format('d M Y, H:i:s'),
        ]);
    }
}
